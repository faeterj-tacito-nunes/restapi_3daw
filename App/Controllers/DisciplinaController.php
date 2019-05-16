<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\DAO\MySQL\Restapi\DisciplinaDAO;
use App\Models\MySQL\Restapi\DisciplinaModel;

final class DisciplinaController {

    public function getDisciplinas (Request $request, Response $response, array $args): Response {
        $a = new DisciplinaDAO(); 
        $b = $a->verDisciplinas(); 
        $response = $response->withJson($b); 
        return $response;
    }
    
    public function getDisciplinaCOD (Request $request, Response $response, array $args): Response {
        $a = new DisciplinaDAO();
        $b = $a->verDisciplinasPorCodigo($args['cod']);
        $response = $response->withJson($b); 
        return $response;
    }

    public function getTurmasDisciplina (Request $request, Response $response, array $args): Response {
        $a = new DisciplinaDAO();
        $b = $a->verTurmasPorDisciplina($args['cod']);
        $response = $response->withJson($b); 
        return $response;
    }

    public function insereDisciplina(Request $request, Response $response, array $args): Response {
       $dados = $request->getParsedBody(); 
       $a = new DisciplinaDAO(); 
       $b = new DisciplinaModel(); 
       $b->setCod($dados['cod'])             
         ->setDepto($dados['depto']);    
       $a->inserirDisciplina($b); 
       $response = $response->withJson(['mensagem' => 'Disciplina inserida com sucesso!']); 
       return $response;
   }

   public function alteraDisciplina(Request $request, Response $response, array $args): Response {
    $dados = $request->getParsedBody(); 
    $a = new DisciplinaDAO();
    $b = new DisciplinaModel();
    $b->setCod($dados['cod']) 
      ->setDepto($dados['depto']);
    $a->alterarDisciplina($b);
    $response = $response->withJson(['mensagem' => 'Disciplina alterada com sucesso!']);
    return $response;
    }

    public function deletaDisciplina(Request $request, Response $response, array $args): Response {
        $params = $request->getQueryParams();
        $codigo = $params['cod'];
        $a = new DisciplinaDAO();
        $a->deletarDisciplina($codigo);
        $response = $response->withJson(['message' => 'Disciplina excluída com sucesso!']);
        return $response;
    }

    public function limparSistema(Request $request, Response $response, array $args): Response {
        $a = new DisciplinaDAO();
        $a->restaurarSistema();
        $response = $response->withJson(['message' => 'Sistema restaurado com sucesso!']);
        return $response;
    }

}