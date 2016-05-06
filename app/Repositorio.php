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
     * @param array $filter filtro de restrição dos posts listados
     * @return array
     */
    public function listPosts(array $filter = [])
    {
        return $this->db->query(
            [],
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
        return $this->db->aggregate([
            [
                '$group' => [
                    '_id' => [
                        'month' => ['$month' => '$when'],
                        'year' => ['$year' => '$when']
                    ],
                    'qtd' => ['$sum' => 1]
                ]
            ],
            ['$project' => ['_id' => 0, 'when' => '$_id', 'qtd' => 1]]
        ])->toArray();
    }
}
