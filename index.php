<?php ini_set( 'default_charset', 'utf-8');
/**
 * TÁCITO NUNES RIBEIRO
 * REST API 3DAW191 v1.1.7
 */

//INDEX APENAS DELEGANDO AS INFORMAÇÕES E AÇÕES NO SISTEMA

//CARREGA TODAS AS BIBLIOTECAS
require_once './vendor/autoload.php';

// DETERMINA AS VARIÁVEIS DO BANCO DE DADOS
require_once './env.php';

// DETERMINA COMO OS ERROS APARECEM NA TELA
require_once './src/slimConfiguration.php';
require_once './src/basicAuth.php';

//DETERMINA TODAS AS ROTAS
require_once './routes/index.php';

/**
 * heroku login
 * heroku config
 * composer update
 * git init
 * heroku git:remote -a restapi-faeterj
 * git remote add ghrestapi https://github.com/tacitonunes/restapi.git
 * git add .
 * git commit -m "v1.1.7"
 * git push heroku master
 * git push ghrestapi master
 */