<?php

/* ESTE ARQUIVO POSSUI OS MÉTODOS DA DISPLCINA PARA CONEXÃO COM BANCO DE DADOS*/

namespace App\DAO\MySQL\Restapi;

use App\Models\MySQL\Restapi\DisciplinaModel;

class DisciplinaDAO extends Conexao {

    public function __construct() {
        parent::__construct();    
    }

    public function verDisciplinas(): array {
        $disciplinas = $this->pdo
            ->query('SELECT * FROM api_disciplina;')
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $disciplinas;
    }
    public function verDisciplinasPorCodigo(string $cod): array {
        $disciplina = $this->pdo
            ->query("SELECT * FROM api_disciplina WHERE cod = '$cod';
                     ")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $disciplina;
    }

    public function verTurmasPorDisciplina(string $cod): array {
        $disciplina = $this->pdo
            ->query("SELECT ano, sem, prof, turno FROM api_turmas WHERE CodDisc = '$cod';
                     ")
            ->fetchAll(\PDO::FETCH_ASSOC);
        return $disciplina;
    }

    public function inserirDisciplina(DisciplinaModel $disciplina): void {
        $dados = $this->pdo
            ->prepare('INSERT INTO api_disciplina VALUES(:_cod, :_depto);');
        $dados->execute([
            '_cod'=>$disciplina->getCod(), 
            '_depto'=>$disciplina->getDepto()
        ]);
    }

    public function alterarDisciplina(DisciplinaModel $disciplina): void {
        $dados = $this->pdo
            ->prepare('UPDATE api_disciplina SET cod=:_cod, departamento=:_depto WHERE cod=:_cod;');
        $dados->execute([
            '_cod'=>$disciplina->getCod(), 
            '_depto'=>$disciplina->getDepto()
        ]);
    }
    
    public function deletarDisciplina(string $cod): void {
        $dados = $this->pdo
            ->prepare(' DELETE FROM api_turmas WHERE codDisc = :c; 
                        DELETE FROM api_disciplina WHERE cod = :c;
            ');
        $dados->execute([ 'c' => $cod ]);
    }

    public function restaurarSistema(): void {
        $dados = $this->pdo
            ->query('   DELETE FROM api_aprov;
                        DELETE FROM api_alunos; 
                        DELETE FROM api_turmas; 
                        DELETE FROM api_disciplina;
                        ');
    }

}