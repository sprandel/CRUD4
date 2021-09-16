<?php
require_once "./classes/Database.php";

   /**
    * <b>Professor:</b>
    * Classe utilizada pra registrar, editar ou excluir professores do banco de dados
    * 
    * @author Pedro Sprandel
    * @version 4.0
    */

class Professor {

    /** @var int Código Único do professor auto-incrementado
     *  @access private
     */
    private $codigo;
    /** @var string Nome do professor 
     *  @access private
     */
    private $nome;


    public function __construct() {
    }

    /**
     * <b>Atribuir valores as variáveis da classe aluno:</b>
     * @param int $codigo;
     * @param string $nome;
     */
    public function setProfessor($codigo, $nome) {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }

    /**
     * <b>Pegar código do professor:</b>
     * @return int $codigo;
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * <b>Pegar nome do professor:</b>
     * @return string $codigo;
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * <b>Salvar um registro de professor novo no banco de dados</b>
     * @return boolean;
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO professor (nome) VALUES (:nome)");
                $stm->execute(array(":nome" => $this->getNome()));
            } else {
                $stm = $db->prepare("UPDATE professor SET nome=:nome WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":codigo" => $this->codigo));
            }
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

    /**
     * <b>Exibe na listagem todos os registros de professores do banco de dados</b>
     * @return array $professores;
     */
    public static function listar() {
        $db = Database::conexao();
        $professores = null;
        $retorno = $db->query("SELECT * FROM professor");
        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);

            $professores[] = $professor;
        }

        return $professores;
    }

    /**
     * <b>Pega o professor do banco de dados e exibe na tela para edição</b>
     * @return object $professor;
     */
    public static function getProfessor($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM professor WHERE codigo= $codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);
            return $professor;
        }
        return false;
    }

    /**
     * <b>Exclui um registro de professor do banco de dados</b>
     * @return boolean;
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $professor = null;
        if ($db->query("DELETE FROM professor WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}
