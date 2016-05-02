<?php
/**
 * Arquivo de conexão com a base de dados.
 */

class Db
{
    protected static $instance;

    /**
     * @var MongoDB\Driver\Manager Gerenciador da conexão com o MongoDB.
     */
    protected $manager;

    protected $dbHost = '127.0.0.1';
    protected $dbPort = 27017;
    protected $dbName = 'blog';
    protected $dbDefaultCollection = 'blog';

    private function __construct()
    {
        $this->checaConfiguracaoDb();
        $connString = sprintf(
            "mongodb://%s:%d/%s",
            $this->dbHost,
            $this->dbPort,
            $this->dbName
        );

        $this->manager = new MongoDB\Driver\Manager($connString);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Db();
        }

        return self::$instance;
    }

    protected function checaConfiguracaoDb()
    {
        if (!$this->dbHost || !$this->dbName) {
            throw new Exception('As informações de conexão com o banco estão incompletas.');
        }
    }

    /**
     *
     * @param MongoDB\Driver\Query $query
     * @param type $collection
     * @return type
     * @todo Receber apenas o filter e criar o query dentro
     */
	public function query(MongoDB\Driver\Query $query, $collection = null)
	{
		return $this->manager->executeQuery(
            $this->getNamespace($collection),
            $query
        );
	}

    public function insert(array $data, $collection = null)
    {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert($data);

        return $this->manager->executeBulkWrite(
            $this->getNamespace($collection),
            $bulk
        );
    }

    public function update($filter, array $data, $collection = null)
    {

    }

    public function delete($filter)
    {

    }

    protected function getNamespace($collection)
    {
        if (!is_null($collection)) {
            return "{$this->dbName}.{$collection}";
        }

        return "{$this->dbName}.{$this->dbDefaultCollection}";
    }
}
