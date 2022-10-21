<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

$app = new \Slim\App;

$app->post('/api/login', function (Request $request, Response $response) {
   
    $input = $request->getParsedBody();
    $Username=trim(strip_tags($input['username']));
    $Password=trim(strip_tags($input['password']));

  $sql = "SELECT id, username FROM `login` WHERE username='$Username' AND `password`='$Password'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam("username", $Username);
    $resultado->bindParam("password", $Password);
    $resultado->execute();
    $user = $resultado->fetchObject();  
    

    $settings = "secureKeylogin";       
    $token = array(
        'IdUser' =>  $user->id, 
        'Username' => $user->username
    );
    $token = JWT::encode($token, $settings, "HS256");
    print_r($token);
    //return $this->response->withJson(['status' => 'success','data'=>$user, 'token' => $token],200);

    /* if ($resultado->rowCount() > 0){
        
        //$login = $resultado->fetchAll(PDO::FETCH_OBJ);
        //echo json_encode($login);
      }else {
        echo json_encode("Credenciales invalídas.");
      }
      $resultado = null;
      $db = null; */
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});