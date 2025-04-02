<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';
require __DIR__ . '/controllers/CertificazioniController.php';

$app = AppFactory::create();

//alunni
$app->get('/alunni', "AlunniController:index");

$app->get('/alunni/{id}', "AlunniController:view");

// curl -X POST http://localhost:8080/alunni -H "Content-Type: application/json" -d '{"nome": "ciccio"}'
$app->post('/alunni', "AlunniController:create");

// curl -X PUT http://localhost:8080/alunni/2 -H "Content-Type: application/json" -d '{"nome": "franko"}'
$app->put('/alunni/{id}', "AlunniController:update");

// curl -X DELETE http://localhost:8080/alunni/2
$app->delete('/alunni/{id}', "AlunniController:destroy");

//certificazioni
$app->get('/alunni/{id}/certificazioni', "CertificazioniController:index");

$app->get('/certificazioni/{id}', "CertificazioniController:view");

// curl -X POST http://localhost:8080/alunni/1/certificazioni -H "Content-Type: application/json" -d '{"titolo": "Certificazione Angular", "votazione": 50, "ente": "Udemy"}'
$app->post('/alunni/{id}/certificazioni', "CertificazioniController:create");

// curl -X PUT http://localhost:8080/certificazioni/2 -H "Content-Type: application/json" -d '{"titolo": "Certificazione Node"}'
$app->put('/certificazioni/{id}', "CertificazioniController:update");

// curl -X DELETE http://localhost:8080/certificazioni/4
$app->delete('/certificazioni/{id}', "CertificazioniController:destroy");

$app->run();

//MY_UID=$(id -u) MY_GID=$(id -g) docker-compose up -d  --> avviareMY_UID=$(id -u) MY_GID=$(id -g) docker-compose up -d container docker
