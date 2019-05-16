<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Restapi\TurmaDAO;
use App\Models\MySQL\Restapi\TurmaModel;

final class TurmaController {

    public function getTurmas (Request $request, Response $response, array $args): Response {
        $a = new TurmaDAO(); 
        $b = $a->getAllTurmas(); 
        $response = $response->withJson($b); 
        return $response;
    }
    
    public function getTurmaID (Request $request, Response $response, array $args): Response {
        $a = new TurmaDAO();
        $b = $a->getTurma_ID($args['id']);
        $response = $response->withJson($b); 
        return $response;
    }
    
    public function insereTurma(Request $request, Response $response, array $args): Response {
       $dados = $request->getParsedBody(); 
       $a = new TurmaDAO(); 
       $b = new TurmaModel(); 
       $b->setId($dados['codDisc'], $dados['ano'], $dados['sem'], $dados['turno'])
         ->setCod($dados['codDisc'])             
         ->setAno($dados['ano'])
         ->setSem($dados['sem'])   
         ->setTurno($dados['turno'])   
         ->setProf($dados['prof']);
       $a->inserirTurma($b); 
       $response = $response->withJson(['mensagem' => 'Turma inserida com sucesso!']); 
       return $response;
   }

   public function alteraTurma(Request $request, Response $response, array $args): Response {
    $dados = $request->getParsedBody(); 
    $a = new TurmaDAO();
    $b = new TurmaModel();
    $b->setId($dados['codDisc'], $dados['ano'], $dados['sem'], $dados['turno'])
      ->setCod($dados['codDisc']) 
      ->setAno($dados['ano'])
      ->setSem($dados['sem'])
      ->setTurno($dados['turno'])
      ->setProf($dados['prof']);
    $a->alterarTurma($b);
    $response = $response->withJson(['mensagem' => 'Turma alterada com sucesso!']);
    return $response;
    }

    public function deletaTurma(Request $request, Response $response, array $args): Response {
        $params = $request->getQueryParams();
        $id = $params['id'];
        $a = new TurmaDAO();
        $a->deletarTurma($id);
        $response = $response->withJson(['message' => 'Turma excluída com sucesso!']);
        return $response;
    }

}