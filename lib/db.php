<?php
/**
 * Arquivo de conexão com a base de dados.
 */

class Db
{
    protected static $instance;

    protected $connection;

    protected $dbHost;
    protected $dbName;
    protected $dbPort;
    protected $dbDefaultCollection;
    protected $dbUsername;
    protected $dbPassword;

    private function __construct()
    {
        $this->checaConfiguracaoDb();
        $connString = sprintf(
            "mongodb://%s:%s@%s:%d/%s",
            $this->dbUsername,
            $this->dbPassword,
            $this->dbHost,
            $this->dbPort,
            $this->dbName
        );

        $this->connection = new MongoDB\Driver\Manager($connString);
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
        if ($this->dbHost || $this->dbName || $this->dbUsername || $this->dbPassword) {
            throw new Exception('As informações de conexão com o banco estão incompletas.');
        }
    }

    public function getCollection($collection = null)
    {
        if (empty($collection)) {
            if (empty($this->dbDefaultCollection)) {
                throw new Exception('Informe uma collection.');
            }

            $collection = $this->dbDefaultCollection;
        }

        return $this
            ->connection
            ->{$this->dbName}
            ->$collection;
    }

}
