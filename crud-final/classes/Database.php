<?php

    /**
    * <b>Database:</b>
    * Realiza conexão com o banco de dados
    * 
    * @author Pedro Sprandel
    * @version 4.0
    */

class Database {

    /** @var object Banco de dados
     *  @access private
     */
    private static $db;

    private function __construct() {
        $host = "localhost";
        $name = "escola";
        $usuario = "root";
        $senha = "";
        $driver = "mysql";
        $sistema = "Aula Info";
        $email_sistema = "gsd@ggfgf.com.br";

        try {
            self::$db = new PDO("$driver:host=$host;dbname=$name", $usuario, $senha);
            #Garantir que execeções sejam lançadas em caso de erro.
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #Garantir que dados armazenados estejam na codificação UTF8
            self::$db->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die("connection Error: " . $e->getMessage());
        }
    }

    /**
     * <b>Estabelece a conexão com o banco de dados:</b>
     * @return object $db;
     */
    public static function conexao() {
        if (!self::$db) {
            new Database();
        }
        return self::$db;
    }
}
