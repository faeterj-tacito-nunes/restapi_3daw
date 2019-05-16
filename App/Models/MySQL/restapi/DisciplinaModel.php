<?php

/* ESTE ARQUIVO POSSUI TODOS OS MÉTODOS, GETTERS E SETTERS DA TURMA */

namespace App\Models\MySQL\Restapi;

final class DisciplinaModel {
    private $cod;
    private $dep;

    //CODIGO DA DISCIPLINA
    public function getCod(): string {
        return $this->cod;
    }
    public function setCod($_cod): DisciplinaModel {
        $this->cod = $_cod;
        return $this;
    }
    //PERIODO
    public function getDepto(): string {
        return $this->dep;
    }
    public function setDepto($_dep): DisciplinaModel {
        $this->dep = $_dep;
        return $this;
    }
}