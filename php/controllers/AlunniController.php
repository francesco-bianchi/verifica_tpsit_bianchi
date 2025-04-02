<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      $query = "SELECT * FROM alunni";
      $parametri = $request->getQueryParams();
      
      if(isset($_GET["search"])){
        $search = $parametri['search'];
        $query .= " WHERE nome LIKE '%$search%' OR cognome LIKE '%$search%'";
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
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE id = '$args[id]'");
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
    $query_inserimento = "INSERT INTO alunni(nome, cognome) VALUES('$body[nome]', '$body[cognome]');";
    $result = $mysqli_connection->query($query_inserimento);
    if($result && $mysqli_connection->affected_rows > 0){
      $response->getBody()->write(json_encode(array("message"=>"success")));
      $status = 201;
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
    $query_inserimento = "UPDATE alunni SET";
    if(isset($body["nome"])){
      if($primo_inserimento){
        $primo_inserimento = false;
      }
      $query_inserimento .= " nome='$body[nome]'";
    }

    if(isset($body["cognome"])){
      if(!$primo_inserimento){
        $query_inserimento .= ",";
      }
      $primo_inserimento = false;
      $query_inserimento .= " cognome='$body[cognome]'";
    }

    $query_inserimento .= " WHERE id = '$args[id]'";

    $result = $mysqli_connection->query($query_inserimento);

    if($result){
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
    $query_inserimento = "DELETE FROM alunni WHERE id = '$args[id]'";
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
