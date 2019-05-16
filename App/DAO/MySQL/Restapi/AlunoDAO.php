<?php

/* ESTE ARQUIVO POSSUI OS MÉTODOS DO ALUNO PARA CONEXÃO COM BANCO DE DADOS*/

namespace App\DAO\MySQL\Restapi;

use App\Models\MySQL\Restapi\AlunoModel;

class AlunoDAO extends Conexao {

    public function __construct() {
        parent::__construct();
    }

    public function getAllAlunos(): array {
        $alunos = $this->pdo
            ->query('SELECT matricula, nome FROM api_alunos;')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $alunos;
    }

    public function getAluno_MAT(string $mat): array {
        $aluno = $this->pdo
            ->query("SELECT matricula, nome FROM api_alunos WHERE matricula = '$mat';")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $aluno;
    }

    public function inserirAluno(AlunoModel $aluno): void {
        $alunos = $this->pdo
            ->prepare('INSERT INTO api_alunos VALUES(:_mat, :_nom);');
        $alunos->execute([
            '_mat'=>$aluno->getMatricula(),
            '_nom'=>$aluno->getNome()        
        ]);
    }
    
    public function alterarAluno(AlunoModel $aluno): void {
        $dados = $this->pdo
            ->prepare('UPDATE api_alunos SET nome=:_nom WHERE matricula = :_mat;');
        $dados->execute([
            '_mat' => $aluno->getMatricula(),
            '_nom' => $aluno->getNome()
        ]);
    }

    public function deletarAluno(string $mat): void {
        $dados = $this->pdo
            ->prepare(' DELETE FROM api_aprov WHERE aluno = :_mat;
                        DELETE FROM api_alunos WHERE matricula = :_mat;');
        $dados->execute([ '_mat' => $mat ]);
    }
}