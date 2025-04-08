<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function index(Request $request, Response $response, $args){
    $db = Db::getInstance();
    $results = $db->select("alunni");

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function show(Request $request, Response $response, $args){
    $db = Db::getInstance();
    $results = $db->select("alunni", "id = " . $args['id'] . "");

     $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function create(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $body = json_decode($request->getBody()->getContents(), true);
    $nome = $body["nome"];
    $cognome = $body["cognome"];
    $result = $mysqli_connection->query("INSERT INTO alunni(nome, cognome) VALUES('$nome', '$cognome')");

    $response->getBody()->write(json_encode($result));
    return $response->withHeader("Content-Length", "0")->withStatus(201);

  }

  public function update(Request $request, Response $response, $args){
    $db = Db::getInstance();
    $body = json_decode($request->getBody()->getContents(), true);
    $nome = $body["nome"];
    $cognome = $body["cognome"];
    $query = "UPDATE alunni SET nome = '$nome', cognome = '$cognome' WHERE id = " . $args['id'] . "";

    $result = $mysqli_connection->query("UPDATE alunni SET nome = '$nome', cognome = '$cognome' WHERE id = " . $args['id'] . "");

    $result = $db->query($query);
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function destroy(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("DELETE FROM alunni WHERE id = " . $args['id'] . "");

    $response->getBody()->write(json_encode($result));
    return $response->withHeader("Content-Length", "0")->withStatus(200);
  
  }

  public function search(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE cognome like '%" . $args['cognome'] . "%'");
    if($result->num_rows > 0){
      $results = $result->fetch_all(MYSQLI_ASSOC);
      $response->getBody()->write(json_encode($results));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
    return $response->withHeader("Content-length", "0")->withStatus(404);
  }

  public function sort(Request $request, Response $response, $args){
    $db = Db::getInstance();
    $results = $db->query("describe alunni");

    $found = false;
    $columns = $results-> fetch_all(MYSQLI_ASSOC);
    foreach ($columns as $col){
      if($col['Field'] == $args['column']){
        $found = true;
        break;
      }
    }

    if(!$found){
      $response->getBody()->write(json_encode(["msg"=> "colonna non trovata"]));
      return $response->withHeader("Content-Type", "application/json")->withStatus(404);
    }
    $query = "SELECT * FROM alunni ORDER BY " . $args['column'] . " ASC";
    $result = $db->query($query);
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }
   
  

}
