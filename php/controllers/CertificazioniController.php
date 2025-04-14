<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CertificazioniController
{
  // GET /alunni/{id}/certificazioni
  public function index(Request $request, Response $response, $args) {
    $db = Db::getInstance();
    $alunno_id = $args['id'];

    $result = $db->query("SELECT * FROM certificazioni WHERE alunno_id = $alunno_id");

    if ($result->num_rows > 0) {
      $certificazioni = $result->fetch_all(MYSQLI_ASSOC);
      $response->getBody()->write(json_encode($certificazioni));
      return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    return $response->withStatus(404)->withHeader("Content-Length", "0");
  }

  // GET /alunni/{id}/certificazioni/{idCert}
  public function show(Request $request, Response $response, $args) {
    $db = Db::getInstance();
    $idCert = $args['idCert'];
    $alunno_id = $args['id'];

    $result = $db->query("SELECT * FROM certificazioni WHERE id = $idCert AND alunno_id = $alunno_id");

    if ($result->num_rows > 0) {
      $certificazione = $result->fetch_assoc();
      $response->getBody()->write(json_encode($certificazione));
      return $response->withHeader("Content-Type", "application/json")->withStatus(200);
    }

    return $response->withStatus(404)->withHeader("Content-Length", "0");
  }

  // POST /alunni/{id}/certificazioni
  public function create(Request $request, Response $response, $args) {
    $db = Db::getInstance();
    $alunno_id = $args['id'];
    $data = json_decode($request->getBody()->getContents(), true);

    $titolo = $data['titolo'];
    $votazione = $data['votazione'];
    $ente = $data['ente'];

    $stmt = $db->prepare("INSERT INTO certificazioni (alunno_id, titolo, votazione, ente) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $alunno_id, $titolo, $votazione, $ente);
    $success = $stmt->execute();

    if ($success) {
      return $response->withStatus(201)->withHeader("Content-Type", "application/json")
                      ->write(json_encode(["msg" => "Certificazione creata", "id" => $db->insert_id]));
    }

    return $response->withStatus(500)->withHeader("Content-Length", "0");
  }

  // PUT /alunni/{id}/certificazioni/{idCert}
  public function update(Request $request, Response $response, $args) {
    $db = Db::getInstance();
    $alunno_id = $args['id'];
    $idCert = $args['idCert'];
    $data = json_decode($request->getBody()->getContents(), true);

    $titolo = $data['titolo'];
    $votazione = $data['votazione'];
    $ente = $data['ente'];

    $stmt = $db->prepare("UPDATE certificazioni SET titolo = ?, votazione = ?, ente = ? WHERE id = ? AND alunno_id = ?");
    $stmt->bind_param("sisii", $titolo, $votazione, $ente, $idCert, $alunno_id);
    $success = $stmt->execute();

    if ($success) {
      return $response->withStatus(200)->withHeader("Content-Type", "application/json")
                      ->write(json_encode(["msg" => "Certificazione aggiornata"]));
    }

    return $response->withStatus(500)->withHeader("Content-Length", "0");
  }

  // DELETE /alunni/{id}/certificazioni or /alunni/{id}/certificazioni/{idCert}
  public function destroy(Request $request, Response $response, $args) {
    $db = Db::getInstance();
    $alunno_id = $args['id'];
    $idCert = $args['idCert'] ?? null;

    if ($idCert) {
      $stmt = $db->prepare("DELETE FROM certificazioni WHERE id = ? AND alunno_id = ?");
      $stmt->bind_param("ii", $idCert, $alunno_id);
    } else {
      $stmt = $db->prepare("DELETE FROM certificazioni WHERE alunno_id = ?");
      $stmt->bind_param("i", $alunno_id);
    }

    $success = $stmt->execute();

    if ($success) {
      return $response->withStatus(200)->withHeader("Content-Type", "application/json")
                      ->write(json_encode(["msg" => "Certificazione/e eliminata/e"]));
    }

    return $response->withStatus(500)->withHeader("Content-Length", "0");
  }
}
