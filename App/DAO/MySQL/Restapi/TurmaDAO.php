<?php

/* ESTE ARQUIVO POSSUI OS MÉTODOS DA DISPLCINA PARA CONEXÃO COM BANCO DE DADOS*/

namespace App\DAO\MySQL\Restapi;

use App\Models\MySQL\Restapi\TurmaModel;

class TurmaDAO extends Conexao {

    public function __construct() {
        parent::__construct();    
    }

    public function getAllTurmas(): array {
        $turmas = $this->pdo
            ->query('SELECT * FROM api_turmas;')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $turmas;
    }
    public function getTurma_ID(string $id): array {
        $turma = $this->pdo
            ->query("SELECT * FROM api_turmas where id = '$id';")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $turma;
    }

    public function inserirTurma(TurmaModel $turma): void {
        $dados = $this->pdo
            ->prepare('INSERT INTO api_turmas VALUES(:_id, :_cod, :_ano, :_sem, :_tur, :_pro);');
        $dados->execute([
            '_id'=>$turma->getId(),
            '_cod'=>$turma->getCod(),
            '_ano'=>$turma->getAno(),
            '_sem'=>$turma->getSem(),
            '_tur'=>$turma->getTurno(),
            '_pro'=>$turma->getProf()
        ]);
    }

    public function alterarTurma(TurmaModel $turma): void {
        $dados = $this->pdo
            ->prepare('UPDATE api_turmas SET prof=:_pro WHERE id=:_id;');
        $dados->execute([
            '_id' => $turma->getCod(),
            '_pro' => $turma->getProf()
        ]);
    }
    
    public function deletarturma(string $id): void {
        $dados = $this->pdo
            ->prepare('DELETE FROM api_turmas WHERE id = :a;');
        $dados->execute([ 'a' => $id ]);
    }

}