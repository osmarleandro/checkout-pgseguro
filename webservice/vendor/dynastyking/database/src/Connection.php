<?php

/**
 * Contem a classe de conexão com o banco de dados mysql
 */
namespace dynastyking\database;

use PDO;

/**
 * Classe de conexão com o banco de dados mysql.
 *
 * @author andre
 * @namespace database
 * @version 1.0.0
 *
 */
class Connection
{

    /**
     * Host
     *
     * @var string Host
     */
    const HOST = "localhost";

    /**
     * Nome do database
     *
     * @var string DB_NAME
     */
    const DB_NANE = "vendas_web2";

    /**
     * Usuário do mysql
     *
     * @var string USER
     */
    const USER = "vendas_web2";

    /**
     * Senha do usuário USER
     *
     * @var string PASS
     */
    const PASS = "fXRJKTrFwRUt628B";

    /**
     * Conexão com o banco de dados
     *
     * @var \PDO $connection
     */
    private $connection;

    /**
     * Conecta com o banco de dados
     */
    public function __construct()
    {
        try {
            if (version_compare(PHP_VERSION, '5.4') >= 0) {
                $this->connection = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DB_NANE . ";charset=utf8",
                    self::USER,
                    self::PASS
                );
            } else {
                $this->connection = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DB_NANE,
                    self::USER,
                    self::PASS,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
                );
            }
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $exception) {
            print $exception->getTrace();
        }
    }

    /**
     * Metodo usado apenas para SELECT.
     *
     * Exemplo:<br>
     * <pre>
     * selectQuery(
     * "SELECT * FROM exemplo WHERE id=:id OR nome=:nome",
     * array(
     * ":id"=>1,
     * ":nome"=>"Exemplo"
     * )
     * );
     * </pre>
     *
     * Exemplo 2:<br>
     * <pre>
     * selectQuery(
     * "SELECT * FROM exemplo WHERE id=? OR nome=?",
     * array(1, "Exemplo")
     * );
     * </pre>
     *
     * @param string $query
     *            SQL query
     * @param array $array
     *            Array de parametros
     * @return array Retorna array de valores associados às colunas da tabela
     */
    public function selectQuery($query, $array = NULL)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($array);
        $registros = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $registros;
    }

    /**
     * Metodo usado apenas para INSERT, UPDATE, DELETE.
     *
     * @param string $query
     *            SQL query
     * @param array $array
     *            Array de parametros
     * @return boolean Retorna true em caso de sucesso e false em caso de erro
     * @see \database\Connection::selectQuery() selectQuery
     */
    public function execQuery($query, $array = NULL)
    {
        $stmt = $this->connection->prepare($query);
        return $stmt->execute($array);
    }

    /**
     * Retorna o ultimo id inserido,
     * porém apenas retorna se tiver sido inserido nesta mesma conexão.
     *
     * @return integer Ultimo id inserido
     */
    public function getLastID()
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }

    /**
     * Fecha a conexão com o banco de dados
     */
    public function close()
    {
        $this->connection = null;
    }
}
