<?php

/* ESTE ARQUIVO POSSUI OS MÉTODOS DO ALUNO PARA CONEXÃO COM BANCO DE DADOS*/

namespace App\DAO\MySQL\Restapi;

use App\Models\MySQL\Restapi\AproveitaModel;

class AproveitaDAO extends Conexao {

    public function __construct() {
        parent::__construct();
    }

    public function getAlunosTUR(string $tur): array {
        $aluno = $this->pdo
            ->query("SELECT aluno, media FROM api_aprov WHERE turma = '$tur';")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $aluno;
    }

    public function getAlunosDisc(string $disc): array {
        $aluno = $this->pdo
            ->query("SELECT aluno, media FROM api_aprov WHERE disc = '$disc';")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $aluno;
    }

    public function inserirAlunoTUR(AproveitaModel $aluno): void {
        $alunos = $this->pdo
            ->prepare('INSERT INTO api_aprov VALUES(:_mat, :_disc, :_tur, :_mda);');
        $alunos->execute([
            '_mat'=>$aluno->getMatricula(),
            '_disc'=>$aluno->getDisciplina(), 
            '_tur'=>$aluno->getTurma(),
            '_mda'=>$aluno->getMda()           
        ]);
    }
    
    public function alterarAlunoTUR(AproveitaModel $aluno): void {
        $dados = $this->pdo
            ->prepare('UPDATE api_aprov SET media = :_mda WHERE (aluno=:_mat AND turma=:_tur);');
        $dados->execute([
            '_mat' => $aluno->getMatricula(),
            '_tur' => $aluno->getTurma(),
            '_mda' => $aluno->getMda()
        ]);
    }

    public function deletarAlunoTurma($mat, $tur): void {
        $dados = $this->pdo
            ->prepare('DELETE FROM api_aprov WHERE (aluno=:_mat AND turma=:_tur);');
        $dados->execute([ '_mat' => $mat, '_tur' => $tur ]);
    }
}