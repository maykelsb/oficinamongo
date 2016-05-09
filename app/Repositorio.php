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
    public function listPosts($filter = [])
    {
        if (is_null($filter) || !is_array($filter)) {
            $filter = [];
        }

        if (isset($filter['when'])) {
            $dateStart = new DateTime($filter['when']);
            $dateEnd = clone $dateStart;
            $dateEnd->add(DateInterval::createFromDateString('1 day'));

            $filter['when'] = [
                '$gte' => $dateStart->getTimestamp(),
                '$lt' => $dateEnd->getTimestamp()
            ];
        }

        return $this->db->query(
            $filter,
            ['sort' => ['when' => -1]]
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
            ['$sort' => ['qtd' => -1]]
        ])->toArray();
    }

    /**
     *
     * @return type
     * @todo Limitar para 5 shouters
     */
    public function topShouters()
    {
        return $this->db->aggregate([
            ['$group' => [ '_id' => '$shouter', 'qtd' => ['$sum' => 1]]],
            ['$project' => ['_id' => 0, 'shouter' => '$_id', 'qtd' => 1]],
            ['$sort' => ['qtd' => -1]]
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
}
