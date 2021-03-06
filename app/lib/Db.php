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

    /**
     *
     * @return Db
     */
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

	public function query(array $filter = [], array $options = [], $collection = null)
	{
        $query = new MongoDB\Driver\Query($filter, $options);

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

    public function update($filter, array $data, array $options = [], $collection = null)
    {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update($filter, $data, $options);

        return $this->manager->executeBulkWrite(
            $this->getNamespace($collection),
            $bulk
        );
    }

    public function delete($filter)
    {

    }

    public function command(array $command)
    {
        $command = new MongoDB\Driver\Command($command);

        return $this->manager->executeCommand(
            $this->dbName,
            $command
        );
    }

    public function aggregate(array $pipeline, $collection = '', $cursorClass = 'stdClass')
    {
        $collection = empty($collection)?$this->dbDefaultCollection:$collection;

        $command = [
            'aggregate' => $collection,
            'pipeline' => $pipeline,
            'cursor' => new $cursorClass()
        ];

        return $this->command($command);
    }

    protected function getNamespace($collection)
    {
        if (!is_null($collection)) {
            return "{$this->dbName}.{$collection}";
        }

        return "{$this->dbName}.{$this->dbDefaultCollection}";
    }
}
