<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Restapi\AlunoDAO;
use App\Models\MySQL\Restapi\AlunoModel;

final class AlunoController {

    //MÉTODO CHAMADO PELA INDEX DA ROTA PARA VISUALIZAR OS ALUNOS
    public function getAlunos (Request $request, Response $response, array $args): Response {
        $a = new AlunoDAO();
        $b = $a->getAllAlunos();
        $response = $response->withJson($b);
        return $response;
    }

    public function getAlunoMat (Request $request, Response $response, array $args): Response {
        $a = new AlunoDAO(); 
        $b = $a->getAluno_MAT($args['matricula']);
        $response = $response->withJson($b); 
        return $response;
    }

    //MÉTODO CHAMADO PELA INDEX DA ROTA PARA INSERIR OS ALUNOS
    public function insereAluno(Request $request, Response $response, array $args): Response {
        $dados = $request->getParsedBody(); 
        $a = new AlunoDAO(); 
        $b = new AlunoModel(); 
        $b->setMatricula($dados['matricula'])   
          ->setNome($dados['nome']);            
        $a->inserirAluno($b); 
        $response = $response->withJson(['mensagem' => 'Aluno inserido com sucesso!']);
        return $response;
    }

    public function insereAlunoTurma($request, Response $response, array $args): Response {
        $params = $request->getQueryParams();
        $mat = $params['matricula'];
        $tur = $params['cod'];
        return Response;
    }

    public function alteraAluno(Request $request, Response $response, array $args): Response {
        $dados = $request->getParsedBody(); 
        $a = new AlunoDAO();
        $b = new AlunoModel();
        $b->setMatricula($args['matricula1'])
        ->setNome($dados['nome']);         
        $a->alterarAluno($b);
        $response = $response->withJson(['mensagem' => 'Aluno alterado com sucesso!']);
        return $response;
    }

    public function deletaAluno(Request $request, Response $response, array $args): Response { 
        $params = $request->getQueryParams();
        $mat = $params['matricula'];
        $a = new AlunoDAO();
        $a->deletarAluno($mat);
        $response = $response->withJson(['message' => 'Aluno excluído com sucesso!']);
        return $response;
    }

}