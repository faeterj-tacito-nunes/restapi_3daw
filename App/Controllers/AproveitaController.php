<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Restapi\AproveitaDAO;
use App\Models\MySQL\Restapi\AproveitaModel;

final class AproveitaController {

    //MÉTODO CHAMADO PELA INDEX DA ROTA PARA VISUALIZAR OS ALUNOS
    public function getAlunosTurma (Request $request, Response $response, array $args): Response {
        $a = new AproveitaDAO(); 
        $b = $a->getAlunosTUR($args['idTurma']);
        $response = $response->withJson($b); 
        return $response;
    }

    public function getAlunosDisciplina (Request $request, Response $response, array $args): Response {
        $a = new AproveitaDAO(); 
        $b = $a->getAlunosDisc($args['cod']);
        $response = $response->withJson($b); 
        return $response;
    }

    public function insereAlunoTurma($request, Response $response, array $args): Response {
        $dados = $request->getParsedBody();
        $a = new AproveitaDAO(); 
        $b = new AproveitaModel(); 
        $b->setMatricula($dados['matricula'])   
          ->setDisciplina($args['cod'])
          ->setTurma($args['idTurma'])         
          ->setMda($dados['media']);
        $a->inserirAlunoTUR($b);
        $response = $response->withJson(['mensagem' => 'Aluno inserido com sucesso!']);  
        return $response;
    }

    public function alteraAlunoTurma($request, Response $response, array $args): Response {
        $dados = $request->getParsedBody();
        $a = new AproveitaDAO(); 
        $b = new AproveitaModel(); 
        $b->setMatricula($dados['matricula'])   
          ->setDisciplina($args['cod'])
          ->setTurma($args['idTurma'])         
          ->setMda($dados['media']);
        $a->alterarAlunoTUR($b);
        $response = $response->withJson(['mensagem' => 'Aluno inserido com sucesso!']);  
        return $response;
    }

    public function deletaAlunoTurma(Request $request, Response $response, array $args): Response { 
        $mat = $args['matricula'];
        $tur = $args['idTurma'];
        $a = new AproveitaDAO();
        $a->deletarAlunoTurma($mat, $tur);
        $response = $response->withJson(['message' => 'Aluno desalocado com sucesso!']);
        return $response;
    }

}