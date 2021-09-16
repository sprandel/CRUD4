<?php

/**
 * <b>Aluno:</b>
 * Classe utilizada para registrar, editar ou excluir alunos do banco de dados
 * 
 * @author Pedro Sprandel
 * @version 4.0
 */

require_once "./classes/Database.php";

class Aluno {

    /** @var int Código Único do aluno auto-incrementado
     *  @access private
     */
    private $codigo;
    /** @var string Nome do aluno
     *  @access private
     */
    private $nome;
    /** @var int Número da matrículo do aluno
     *  @access private
     */
    private $matricula;
    /** @var string Turma do aluno do aluno
     *  @access private
     */
    private $turma;

    /**
     * <b>Atribuir valores as variáveis da classe aluno:</b>
     * @param int $codigo;
     * @param string $nome;
     * @param int $matricula;
     * @param string $turma;
     */
    public function setAluno($codigo, $nome, $matricula, $turma) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->turma = $turma;
    }

    /**
     * <b>Pegar código do aluno:</b>
     * @return int $codigo;
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * <b>Pegar Nome do aluno:</b>
     * @return string $nome;
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * <b>Pegar matrícula do aluno:</b>
     * @return int $matricula;
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * <b>Pegar turma do aluno:</b>
     * @return string $turma;
     */
    public function getTurma() {
        return $this->turma;
    }

    /**
     * <b>Salvar um registro de aluno novo no banco de dados</b>
     * @return boolean;
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO aluno (nome, matricula, turma_codigo) VALUES (:nome,:matricula,:turma)");
                $stm->execute(array(":nome" => $this->getNome(), ":matricula" => $this->getMatricula(), ":turma" => $this->getTurma()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE aluno SET nome=:nome,matricula=:matricula,turma_codigo=:turma_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":matricula" => $this->matricula, ":turma_codigo" => $this->turma->getCodigo(), ":codigo" => $this->codigo));
            }
            #pegar o id do registro no banco de dados
            #setar o id do objeto
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }

     /**
     * <b>Exibe na listagem todos os registros de aluno do banco de dados</b>
     * @return array $alunos;
     */
    public static function listar() {
        $db = Database::conexao();
        $alunos = null;
        $retorno = $db->query("SELECT * FROM aluno");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);

            $alunos[] = $aluno;
        }

        return $alunos;
    }

    /**
     * <b>Pega o aluno do banco de dados e exibe na tela para edição</b>
     * @return object $aluno;
     */
    public static function getAluno($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM aluno WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);
            return $aluno;
        }
        return false;
    }

    /**
     * <b>Exclui um registro de aluno do banco de dados</b>
     * @return boolean;
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        if ($db->query("DELETE FROM aluno WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}
