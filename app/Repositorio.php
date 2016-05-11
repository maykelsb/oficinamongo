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
        $sort = [];

        if (is_null($filter) || !is_array($filter)) {
            $filter = [];
        }

        if (isset($filter['when'])) {
            return $this->listShoutsByWhen($filter, $sort);
        }

        if (isset($filter['search'])) {
            return $this->listShoutsBySearch($filter, $sort);
        }

        if (isset($filter['shouter'])) {
            return $this->listShoutsByShouter($filter, $sort);
        }

        return [];
    }

    protected function listShoutsByWhen(array $filter, array $sort)
    {
        return [];
    }

    protected function listShoutsBySearch(array $filter, array $sort)
    {
        return [];
    }

    protected function listShoutsByShouter(array $filter, array $sort)
    {
        return [];
    }

    /**
     * Faz uma contagem de tags ocorrência das tags em cada post e as ordena
     * por maior quantidade de ocorrências.
     *
     * @return array
     */
    public function topTags()
    {
        return [];
    }

    /**
     *
     * @return type
     * @todo Limitar para 5 shouters
     */
    public function top5Shouters()
    {
        return [];
    }

    public function listTimeline()
    {
        return [];
    }

    public function top10Pitacados()
    {
        return [];
    }

    public function top10Likes()
    {
        return [];
    }
}
