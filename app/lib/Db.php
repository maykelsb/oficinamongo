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

	public function query(MongoDB\Driver\Query $query, $collection = null)
	{
		$namespace = "{$this->dbName}." . (is_null($collection)
            ?$this->dbDefaultCollection
            :$collection
        );

		return $this->manager->executeQuery($namespace, $query);
	}

    protected function command(MongoDB\Driver\Command $command)
    {
        $this->manager->executeCommand($this->dbName, $command);
        return $this;
    }

    public function inserir(MongoDB\Driver\Command $command)
    {
        return $this->command($command);
    }

    public function alterar(MongoDB\Driver\Command $command)
    {
        return $this->command($command);
    }

    public function criarCommand(array $comando)
    {

        http://php.net/manual/en/class.mongodb-driver-bulkwrite.php

        return new MongoDB\Driver\Command($comando);
    }
}