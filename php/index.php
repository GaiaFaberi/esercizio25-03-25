<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';
require __DIR__ . '/includes/Db.php';

$app = AppFactory::create();


//curl -X GET http://localhost:8080/alunni
$app->get('/alunni', "AlunniController:index");

//curl -X POST http://localhost:8080/alunni/id
$app->get('/alunni/{id:\d+}', "AlunniController:show");

//curl -X POST http://localhost:8080/alunni/pezzoCognome
$app->get('/alunni/{cognome}', "AlunniController:search");

//curl -X POST http://localhost:8080/alunni H "Content-Type: application/json" -d "{'nome':'nome', 'cognome':'cognome'}
$app->post('/alunni', "AlunniController:create");

//curl -X Put http://localhost:8080/alunni/id -H "Content-Type: application/json" -d "{'nome':'nome', 'cognome':'cognome'}
$app->put('/alunni/{id}', "AlunniController:update");

//curl -X DELETE http://localhost:8080/alunni/id
$app->delete('/alunni/{id}', "AlunniController:destroy");

$app->get('/alunni/sort/{column}:{order}', "AlunniController:sort");

$app->run();
