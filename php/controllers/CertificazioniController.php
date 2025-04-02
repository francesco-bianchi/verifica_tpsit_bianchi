<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CertificazioniController
{
  public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $parametri = $request->getQueryParams();
    $id = $args['id'];
    
    $query = "SELECT * FROM certificazioni WHERE alunno_id = '$id'";
    
    if(isset($_GET["titolo"]) || isset($_GET["votazione"])){
      $titolo = $parametri['titolo'];
      $query .= " AND titolo LIKE '%$titolo%'";
    }
    if(isset($_GET["votazione"])){
      $votazione = $parametri['votazione'];
      $query .= " AND votazione = $votazione";
    }
    if(isset($_GET["sortCol"])){
      $sortCol = $parametri["sortCol"];
      $query .= " ORDER BY $sortCol";
    }
    if(isset($_GET["sort"])){
      $sort = $parametri["sort"] ?? "asc";
      $query .= " $sort";
    }

    $result = $mysqli_connection->query($query);

    if($result && $mysqli_connection->affected_rows > 0){
      //$response->getBody()->write(json_encode(array("message"=>"success")));
      $results = $result->fetch_all(MYSQLI_ASSOC);
      $response->getBody()->write(json_encode($results));
      $status = 200;
    }
    else{
      $response->getBody()->write(json_encode(array("message"=> "Not Found")));
      $status = 404;
    }
    
    return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }

  public function view(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM certificazioni WHERE id = '$args[id]'");
    
    if($result && $mysqli_connection->affected_rows > 0){
      //$response->getBody()->write(json_encode(array("message"=>"success")));
      $results = $result->fetch_all(MYSQLI_ASSOC);
  
      $response->getBody()->write(json_encode($results));
      $status = 200;
    }
    else{
      $response->getBody()->write(json_encode(array("message"=> "Not Found")));
      $status = 404;
    }
    return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }

  public function create(Request $request, Response $response, $args){
    $body = json_decode($request->getBody()->getContents(), true);
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $query_inserimento = "INSERT INTO certificazioni(alunno_id, titolo, votazione, ente) VALUES('$args[id]'";

    if(isset($body["titolo"]) && isset($body["votazione"]) && isset($body["ente"])){
      $query_inserimento .= ", '$body[titolo]', '$body[votazione]', '$body[ente]');";
      $result = $mysqli_connection->query($query_inserimento);

      if($result){
        $response->getBody()->write(json_encode(array("message"=>"Created")));
        $status = 201;
      }
      else{
        $response->getBody()->write(json_encode(array("message"=> "Not Found")));
        $status = 404;
      }
    }
    else{
      $response->getBody()->write(json_encode(array("message"=> "Not Found")));
      $status = 404;
    }
    

    return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }

  public function update(Request $request, Response $response, $args){
    $body = json_decode($request->getBody()->getContents(), true);
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $primo_inserimento = true;
    $query_inserimento = "UPDATE certificazioni SET";
    if(isset($body["titolo"])){
      if($primo_inserimento){
        $primo_inserimento = false;
      }
      $query_inserimento .= " titolo='$body[titolo]'";
    }

    if(isset($body["votazione"])){
      if(!$primo_inserimento){
        $query_inserimento .= ",";
      }
      $primo_inserimento = false;
      $query_inserimento .= " votazione='$body[votazione]'";
    }

    if(isset($body["ente"])){
      if(!$primo_inserimento){
        $query_inserimento .= ",";
      }
      $primo_inserimento = false;
      $query_inserimento .= " ente='$body[ente]'";
    }

    $query_inserimento .= " WHERE id = '$args[id]'";

    $result = $mysqli_connection->query($query_inserimento);
    if($result && $mysqli_connection->affected_rows > 0){
      $response->getBody()->write(json_encode(array("message"=>"Modified")));
      $status = 200;
    }
    else{
      $response->getBody()->write(json_encode(array("message"=> "Not Found")));
      $status = 404;
    }
    

    return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }

  public function destroy(Request $request, Response $response, $args){
    $body = json_decode($request->getBody()->getContents(), true);
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $query_inserimento = "DELETE FROM certificazioni WHERE id = '$args[id]'";
    $result = $mysqli_connection->query($query_inserimento);
    if($result && $mysqli_connection->affected_rows > 0){
      $response->getBody()->write(json_encode(array("message"=>"success")));
      $status = 200;
    }
    else{
      $response->getBody()->write(json_encode(array("message"=> "Not Found")));
      $status = 404;
    }
    

    return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }
}
