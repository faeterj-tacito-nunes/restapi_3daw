<?php

/* ESTE ARQUIVO POSSUI TODOS OS MÉTODOS, GETTERS E SETTERS DA TURMA */

namespace App\Models\MySQL\Restapi;

final class TurmaModel {
    private $id; 
    private $cod_disc;
    private $ano;
    private $sem;
    private $prof;
    private $turno;

    //ID DA TURMA
    public function getId(): string {
        return $this->id;
    }
    public function setId($a, $b, $c, $d): TurmaModel {
        $this->id .= $a;
        $this->id .= $b;
        $this->id .= $c;
        $this->id .= $d;
        return $this;
    }
    //CODIGO DA DISCIPLINA
    public function getCod(): string {
        return $this->cod_disc;
    }
    public function setCod($_cod): TurmaModel {
        $this->cod_disc = $_cod;
        return $this;
    }
    //PERIODO
    public function getAno(): string {
        return $this->ano;
    }
    public function setAno($_ano): TurmaModel {
        $this->ano = $_ano;
        return $this;
    }
    //SEMESTRE
    public function getSem() {
        return $this->sem;
    }
    public function setSem($_sem) {
        $this->sem = $_sem;
        return $this;
    }
    //PROFESSOR
    public function getProf(): string {
        return $this->prof;
    }
    public function setProf($_prof): TurmaModel {
        $this->prof = $_prof;
        return $this;
    }
    //TURNO
    public function getTurno() {
        return $this->turno;
    }
    public function setTurno($_turno) {
        $this->turno = $_turno;
        return $this;
    }
}