<?php 

/* ESTE ARQUIVO POSSUI TODOS OS MÉTODOS, GETTERS E SETTERS DO ALUNO */

namespace App\Models\MySQL\Restapi;

final class AproveitaModel {
    private $matricula;
    private $disc;
    private $tur;
    private $mda;

    public function getMatricula(): string {
        return $this->matricula;
    }
    public function setMatricula($_mat): AproveitaModel {
        $this->matricula = $_mat;
        return $this;
    }

    public function getDisciplina(): string {
        return $this->disc;
    }
    public function setDisciplina($_disc): AproveitaModel {
        $this->disc = $_disc;
        return $this;
    }

    public function getTurma(): string {
        return $this->tur;
    }
    public function setTurma($_tur): AproveitaModel {
        $this->tur = $_tur;
        return $this;
    }

    public function getMda(): float {
        return $this->mda;
    }
    public function setMda($_mda): AproveitaModel {
        $this->mda = $_mda;
        return $this;
    }
}