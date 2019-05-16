<?php ini_set( 'default_charset', 'utf-8');

use function src\{
    slimConfiguration,
    basicAuth,
    advAuth
};
use App\Controllers\{
    TurmaController,
    AlunoController,
    DisciplinaController,
    AproveitaController
};

$app = new \Slim\App(slimConfiguration());

$app->get('/', function() {
    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <style>
                *{
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 16pt;
                }
                </style>
                <meta charset="utf-8" />
                <title>API Rest - Tácito Nunes - FAETERJ-Rio</title>
            </head>
            <body>
                <p>Código para teste:</p>
                <code>< ? php //RETIRA OS ESPAÇOS <br/>
                    use Psr\Http\Message\ServerRequestInterface as Request;<br/>
                    use Psr\Http\Message\ResponseInterface as Response; <br/><br/>
                    
                    require ‘vendor/autoload.php’;  //DEPENDÊNCIAS COMPOSER<br/><br/>

                    $app = new \Slim\App; //CRIA UMA NOVA INSTÂNCIA DO SLIM <br/><br/>

                    $app->get(‘/ola/{name}’,<br/>
                        function (Request $request, Response $response, array $args) { <br/>
                    $name = $args[‘name’]; //atribui o nome passada como URI <br/>
                    $response->getBody()->write("Olá, $name"); <br/>
                    return $response; <br/>
                    }); <br/><br/>

                    $app->run(); <br/><br/>	
                    ?>
                </code>
            </body>
        </html>
    ';
});

$app->delete('/restaurar', DisciplinaController::class . ':limparSistema')->add(advAuth());;

//========================== DISCIPLINAS ====================================
$app->group('/disciplinas',function() use ($app){
/// GETS
        //LISTA TODAS AS DISCIPLINAS
    $app->get('[/]', DisciplinaController::class . ':getDisciplinas');
        //FILTRA DISCIPLINA POR CÓDIGO
    $app->get('/{cod}[/]', DisciplinaController::class . ':getDisciplinaCOD');
        //LISTA AS TURMAS DE UMA DETERMINADA DISCIPLINA
    $app->get('/{cod}/turmas[/]', DisciplinaController::class . ':getTurmasDisciplina');
        //LISTA TODOS ALUNOS DA DISCIPLINA
    $app->get('/{cod}/alunos[/]', AproveitaController::class . ':getAlunosDisciplina');
    
/// AUTENTICAÇÃO PARA CRIAR. ALTERAR OU DELETAR INFORMAÇÕES
    $app->group('',function() use ($app){
    /// POSTS    
            //INSERIR DISCIPLINA
        $app->post('[/]', DisciplinaController::class . ':insereDisciplina');
            //INSCREVE ALUNO EM UMA TURMA ESPECÍFICA
        $app->post('/{cod}/{idTurma}[/]', AproveitaController::class . ':insereAlunoTurma');
    /// PUTS
            //ALTERA INFORMAÇÕES DA DISCIPLINA NO SISTEMA
        $app->put('[/]', DisciplinaController::class . ':alteraDisciplina');
            //ALTERA MEDIA DO ALUNO NA TURMA
        $app->put('/{cod}/{idTurma}[/]', AproveitaController::class . ':alteraAlunoTurma');
    /// DELETES
            ///disciplina?cod=3ESD
        $app->delete('', DisciplinaController::class . ':deletaDisciplina');
    })->add(basicAuth());

});

//========================== TURMAS ====================================

$app->group('/turmas',function() use ($app){
/// GETS
        //LISTA TODAS AS TURMAS
    $app->get('[/]', TurmaController::class . ':getTurmas');
        //LISTA TODAS AS TURMAS POR ID DA DISCIPLINA
    $app->get('/{id}[/]', TurmaController::class . ':getTurmaID');
        //LISTA TODOS ALUNOS DA TURMA ESPECIFICA
    $app->get('/{idTurma}/alunos[/]', AproveitaController::class . ':getAlunosTurma');

/// AUTENTICAÇÃO PARA CRIAR. ALTERAR OU DELETAR INFORMAÇÕES
    $app->group('',function() use ($app){
    /// POSTS
            //INSERIR TURMA NO SISTEMA
        $app->post('[/]', TurmaController::class . ':insereTurma');
    /// PUTS
            //ALTERA INFORMAÇÕES DA TURMA NO SISTEMA
        $app->put('[/]', TurmaController::class . ':alteraTurma');
    /// DELETES
            //turma?id=3esd20191manha01
        $app->delete('', TurmaController::class . ':deletaTurma');
            //disciplinas/3ESD/3ESD20191manha01/1810478300087
        $app->delete('/{idTurma}/{matricula}', AproveitaController::class . ':deletaAlunoTurma');
    })->add(basicAuth());

});


//========================== ALUNOS ====================================

$app->group('/alunos',function() use ($app){
/// GETS
        //LISTA TODOS OS ALUNOS
    $app->get('[/]', AlunoController::class . ':getAlunos');
        //PROCURA O ALUNO POR MATRICULA
    $app->get('/{matricula}[/]', AlunoController::class . ':getAlunoMat');

//// AUTENTICAÇÃO PARA CRIAR. ALTERAR OU DELETAR INFORMAÇÕES
    $app->group('',function() use ($app){
    /// POSTS
            //INSERIR ALUNO NO SISTEMA
        $app->post('[/]', AlunoController::class . ':insereAluno');
    /// PUT
            //ALTERA AS INFORMAÇÕES DO ALUNO NO SISTEMA
        $app->put('/{matricula1}[/]', AlunoController::class . ':alteraAluno');
    /// DELETE
            //aluno?matricula=1810478300087
        $app->delete('', AlunoController::class . ':deletaAluno');
    })->add(basicAuth());
    
});

//========================= DOCUMENTAÇÃO SOBRE ROTAS ===========================

$app->get('/rotas[/]', function() use ($app) {
    echo '
        <!DOCTYPE html>
        <html>
            <head>
                <style>
                    table {
                        font-family: arial, sans-serif;
                        border-collapse: collapse;
                        width: 90%;
                        margin: 0 auto;
                    }
                    
                    td, th {
                        border: 1px dashed #000;
                        text-align: center;
                        padding: 8px;
                    }
                    
                    tr:nth-child(even) {
                        border: 1px none #000;
                        background-color: #eee;
                    }
                    
                    uri{
                        color: blue;
                        font-weight: bold;
                    }
                </style>
                <meta charset="utf-8" />
                <title>ROTAS: API Rest - Tácito Nunes - FAETERJ-Rio</title>
            </head>
            <body>
                <table>
                    <tr>
                        <th>Método</th>
                        <th>Rotas</th>
                        <th>Definição</th>
                        <th colspan="2">Hypermedia Request</th>
                    </tr>
                    <tr>
                        <td rowspan="9"><span style="font-weight:bold;">GET</span></td>
                        <td>/disciplinas</td>
                        <td>Lista todas as disciplinas presentes no sistema.</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/disciplinas/<uri>{codigo}</uri></td>
                        <td>Filtra as disciplinas por código</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/disciplinas/<uri>{codigo}</uri>/turmas</td>
                        <td>Lista todas as turmas de uma disciplina</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/disciplinas/<uri>{codigo}</uri>/alunos</td>
                        <td>Lista todos os alunos de uma disciplina</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/turmas</td>
                        <td>Lista todas as turmas</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/turmas/<uri>{id turma}</uri></td>
                        <td>Filtra turma por Id</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/alunos</td>
                        <td>Lista todos os aluno presentes no sistema</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/alunos/<uri>{matricula}</uri></td>
                        <td>Filtra os alunos pela matricula</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>/turmas/<uri>{id turma}</uri>/alunos</td>
                        <td>Filtra os alunos pela turma</td>
                        <td>URI</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td rowspan="5"><span style="font-weight:bold;">POST</span></td>
                        <!-- --><tr>
                        <td>/disciplinas</td>
                        <td>Insere uma disciplina no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{ <br/>
                                "cod": "3DAW", <br/>
                                "depto": "ANSI" <br/>
                            }</code>
                        </td>
                        </tr>
                        <!-- --> <tr>
                        <td>/turmas</td>
                        <td>Insere uma turma no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>"codDisc": "3DAW",<br/>"ano": "2019",<br/>"sem": "1",<br/>"turno": "Noite01",<br/>"prof": "Ricardo Marciano"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        <!-- --> <tr>
                        <td>/alunos</td>
                        <td>Insere um aluno no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>"matricula": "1810478300087",<br/>"nome": "Tácito Nunes"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        <!-- --> <tr>
                        <td>/disciplinas/<uri>{cod}</uri>/<uri>{id turma}</uri></td>
                        <td>Inscreve um aluno na turma.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>
                    "matricula": "1810478300087",
                    <br/>"media": "9.50"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        
                        <tr>
                        <td rowspan="5"><span style="font-weight:bold;">PUT</span></td>
                        <!-- --><tr>
                        <td>/disciplinas</td>
                        <td>Altera uma disciplina no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{ <br/>
                                "cod": "3DAW", <br/>
                                "depto": "ANSI" <br/>
                            }</code>
                        </td>
                        </tr>
                        <!-- --> <tr>
                        <td>/turmas</td>
                        <td>Altera uma turma no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>"id": "3DAW20191Noite01",<br/>"codDisc": "3DAW",<br/>"ano": "2019",<br/>"sem": "1",<br/>"turno": "Noite01",<br/>"prof": "Ricardo Marciano"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        <!-- --> <tr>
                        <td>/alunos/<uri>{matricula}</uri></td>
                        <td>Altera um aluno no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>"nome": "Tácito Nunes"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        <!-- --> <tr>
                        <td>disciplinas/<uri>{cod}</uri>/<uri>{id Turma}</uri></td>
                        <td>Altera um aluno no sistema.</td>
                        <td>app/json</td>
                        <td style="text-align:left;">
                        <code>{<br/>"matricula": "1810478300087",<br/>
                    "media": "10.00"<br/>}</code>
                        </td> 
                        <!-- --></tr>
                        
                        <!-- --><tr>
                        <td rowspan="5"><span style="font-weight:bold;">DELETE</span></td>
                        <!-- --><tr>
                        <td>/disciplinas<uri>?cod={codigo}</uri></td>
                        <td>Exclui uma disciplina no sistema.</td>
                        <td>Parametros</td>
                        <td style="text-align:left;">
                        <code>?cod=3ESD</code>
                        </td>
                        <!-- --></tr>
                        <!-- --><tr>
                        <td>/turmas<uri>?id={id Turma}</uri></td>
                        <td>Exclui uma disciplina no sistema.</td>
                        <td>Parametros</td>
                        <td style="text-align:left;">
                        <code>?id=3ESD20191Manha01</code>
                        </td>
                        <!-- --></tr>
                        <!-- --><tr>
                        <td>/alunos<uri>?matricula={matricula}</uri></td>
                        <td>Exclui um aluno do sistema.</td>
                        <td>Parametros</td>
                        <td style="text-align:left;">
                        <code>?matricula=1810478300087</code>
                        </td>
                        <!-- --></tr>
                        <!-- --><tr>
                        <td>/turmas/<uri>{turma}</uri>/<uri>{matricula}</uri></td>
                        <td>Exclui um aluno do sistema.</td>
                        <td>URI</td>
                        <td style="text-align:center;">
                        -
                        </td>
                        <!-- --></tr>
                    </tr>
                
                </table>
            </body>
        </html>
    ';
});


//======================== RODA A APLICAÇÃO ================================
$app->run();