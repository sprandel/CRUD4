<?php

/**
 * <b>Turma:</b>
 * Classe utilizada para registrar, editar ou excluir turmas do banco de dados
 * 
 * @author Pedro Sprandel
 * @version 4.0
 */

require_once "./classes/Database.php";
require_once "./classes/Professor.php";

class Turma {

    /** @var int Código Único da turma auto-incrementado
     *  @access private
     */
    private $codigo;
    /** @var string Nome da turma
     *  @access private
     */
    private $curso;
    /** @var string Nome do curso
     *  @access private
     */
    private $nome;
    /** @var string Nome do professor
     *  @access private
     */
    private $professor;

    /**
     * <b>Atribuir valores as variáveis da classe Turma:</b>
     * @param int $codigo;
     * @param string $curso;
     * @param string $nome;
     * @param string $professor;
     */
    public function setTurma($codigo, $curso, $nome, $professor) {
        $this->codigo = $codigo;
        $this->curso = $curso;
        $this->nome = $nome;
        $this->professor = $professor;
    }

    /**
     * <b>Pegar código da turma:</b>
     * @return int $codigo;
     */
    public function getCodigo() {
        return $this->codigo;
    }
    /**
     * <b>Pegar nome da turma:</b>
     * @return string $nome;
     */
    public function getNome() {
        return $this->nome;
    }
    /**
     * <b>Pegar curso:</b>
     * @return string $curso;
     */
    public function getCurso() {
        return $this->curso;
    }
    /**
     * <b>Pegar professor da turma:</b>
     * @return string $professor;
     */
    public function getProfessor() {
        return $this->professor;
    }

    /**
     * <b>Salvar um registro de turma novo no banco de dados</b>
     * @return boolean;
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO turma (nome, curso, professor_codigo) VALUES (:nome,:curso,:professor)");
                $stm->execute(array(":nome" => $this->getNome(), ":curso" => $this->getCurso(), ":professor" => $this->getProfessor()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE turma SET nome=:nome,curso=:curso,professor_codigo=:professor_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":curso" => $this->curso, ":professor_codigo" => $this->professor->getCodigo(), ":codigo" => $this->codigo));
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
     * @return array $turmas;
     */
    public static function listar() {
        $db = Database::conexao();
        $turmas = null;
        $retorno = $db->query("SELECT * FROM turma");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);

            $turmas[] = $turma;
        }

        return $turmas;
    }

    /**
     * <b>Pega a turma do banco de dados e exibe na tela para edição</b>
     * @return object $turma;
     */
    public static function getTurma($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM turma WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);
            return $turma;
        }
        return false;
    }

    /**
     * <b>Exclui um registro de professor do banco de dados</b>
     * @return boolean;
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $turmas = null;
        if ($db->query("DELETE FROM turma WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}
