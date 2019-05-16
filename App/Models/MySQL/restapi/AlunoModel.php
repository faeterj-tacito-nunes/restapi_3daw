<?php 

/* ESTE ARQUIVO POSSUI TODOS OS MÉTODOS, GETTERS E SETTERS DO ALUNO */

namespace App\Models\MySQL\Restapi;

final class AlunoModel {
    private $matricula;
    private $nome;

//api_rest_3daw.aluno.matricula
    public function getMatricula(): string {
        return $this->matricula;
    }
    public function setMatricula($_mat): AlunoModel {
        $this->matricula = $_mat;
        return $this;
    }
//api_rest_3daw.aluno.nome
    public function getNome(): string {
        return $this->nome;
    }
    public function setNome($_nome): AlunoModel {
        $this->nome = $_nome;
        return $this;
    }
}