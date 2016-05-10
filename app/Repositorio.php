<?php
/**
 * Armazena as consultas enviadas ao MongoDB.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

class Repositorio
{
    /**
     *
     * @var Db
     */
    protected $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * Lista todos os posts da shoutbox, com filtro opcional.
     * A lista é, por padrão, com ordenação descendente baseada
     * no campo when.
     *
     * @return array
     */
    public function listShouts($filter = [])
    {
        $sort = ['sort' => ['when' => -1]];

        if (is_null($filter) || !is_array($filter)) {
            $filter = [];
        }

        if (isset($filter['when'])) {
            return $this->listShoutsByWhen($filter, $sort);
        }

        if (isset($filter['search'])) {
            return $this->listShoutsBySearch($filter, $sort);
        }

        return $this->db->query(
            $filter,
            $sort
        )->toArray();
    }

    protected function listShoutsByWhen($filter, $sort)
    {
        $dateStart = new DateTime($filter['when']);
        $dateEnd = clone $dateStart;
        $dateEnd->add(DateInterval::createFromDateString('1 day'));

        $filter['when'] = [
            '$gte' => new MongoDB\BSON\UTCDateTime($dateStart->getTimestamp()),
            '$lt' => new MongoDB\BSON\UTCDateTime($dateEnd->getTimestamp())
        ];

        return $this->db->query(
            $filter,
            $sort
        )->toArray();
    }

    protected function listShoutsBySearch($filter, $sort)
    {
        $filter = [
            '$text' => [
                '$search' => $filter['search'],
                '$caseSensitive' => false
            ]
        ];

        return $this->db->query(
            $filter,
            $sort
        )->toArray();
    }

    /**
     * Faz uma contagem de tags ocorrência das tags em cada post e as ordena
     * por maior quantidade de ocorrências.
     *
     * @return array
     */
    public function topTags()
    {
        return $this->db->aggregate([
            ['$project' => ['_id' => 0, 'tags' => 1]],
            ['$unwind' => '$tags'],
            ['$group' => ['_id' => '$tags', 'qtd' => ['$sum' => 1]]],
            ['$project' => ['_id' => 0, 'tag' => '$_id', 'qtd' => 1]],
            ['$sort' => ['qtd' => -1, 'tag' => 1]]
        ])->toArray();
    }

    /**
     *
     * @return type
     * @todo Limitar para 5 shouters
     */
    public function top5Shouters()
    {
        return $this->db->aggregate([
            ['$group' => [ '_id' => '$shouter', 'qtd' => ['$sum' => 1]]],
            ['$project' => ['_id' => 0, 'shouter' => '$_id', 'qtd' => 1]],
            ['$sort' => ['qtd' => -1, 'shouter' => 1]],
            ['$limit' => 5]
        ])->toArray();
    }

    public function listTimeline()
    {
//        return $this->db->aggregate([
//            [
//                '$group' => [
//                    '_id' => '$when.getMonth()',
//                    'qtd' => ['$sum' => 1]
//                ]
//            ]
//        ])->toArray();




        return $this->db->aggregate([
            ['$project' => ['_id' => 0, 'when2' => '$when.toString()']]




//            ['$group' => [
//                '_id' => ['$dateToString' => ['format' => '%m/%Y', 'date' => '$when']]
//            ]],
//            ['$project' => ['_id' => 0, 'when' => '$_id']]


//            ['$project' => [
//                '_id' => 0,
//                'when' => 1,
////                'when2' => 'ISODate($when)'
//            ]]
//            ['$project' => ['_id' => 0, 'when' => 1]]
//            ['$project' => ['_id' => 0, 'monyear' => ['$dateToString' => ['format' => '%m/%Y', 'date' => 'ISODate($when)']]]],
//            [
//                '$project' => [
//                    '_id' => [
//                        'month' => '$when.getMonth()',
//                        'year' => '$when.getYear()'
//                    ],
//
//                ]
//            ],
//            [
//                '$group' => [
////                    '_id' => '$monyear',
////                    '_id' => [
////                        'month' => ['$month' => '$when'],
////                        'year' => ['$year' => '$when']
////                    ],
//                    '_id' => '$_id',
//                    'qtd' => ['$sum' => 1]
//                ]
//            ],
//            ['$project' => ['_id' => 0, 'when' => '$_id', 'qtd' => 1]]
        ])->toArray();
    }

    public function top10Pitacados()
    {
        return $this->db->aggregate([
            ['$unwind' => ['path' => '$pitacos', 'preserveNullAndEmptyArrays' => true]],
            ['$group' => [
                '_id' => [
                    'id' => '$_id',
                    'when' => '$when',
                    'likes' => '$likes',
                    'shout' => '$shout',
                    'shouter' => '$shouter'
                ],
                'pitacos' => ['$push' => '$pitacos'],
                'size' => ['$sum' => 1]]],
            ['$project' => [
                '_id' => '$_id.id',
                'when' => '$_id.when',
                'likes' => '$_id.likes',
                'pitacos' => '$pitacos',
                'shout' => '$_id.shout',
                'shouter' => '$_id.shouter',
                'size' => '$size'
                ]
            ],
            ['$sort' => ['size' => -1, 'pitacos' => -1, 'when' => -1]],
            ['$limit' => 10],
        ])->toArray();
    }

    public function top10Likes()
    {
        return $this->db->query(
            [],
            ['limit' => 10, 'sort' => ['likes' => -1, 'when' => -1]]
        )->toArray();
    }
}
