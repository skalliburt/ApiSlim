<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
$app = new \Slim\App;

//Login de los usuarios
$app->post('/login', function (Request $request, Response $response) {
   
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

//HTTP de los productos.

$app->group('/V1',function(\Slim\App $app){

  // GET Todos los productos 
  $app->get('/routes/productos', function(Request $request, Response $response){
    $sql = "SELECT * FROM productos";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
  
      if ($resultado->rowCount() > 0){
        $productos = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($productos);
      }else {
        echo json_encode("No existen productos en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 
  
  // GET Recueperar cliente por ID 
  $app->get('/routes/productos/{id}', function(Request $request, Response $response){
    $id_producto = $request->getAttribute('id');
    $sql = "SELECT * FROM productos WHERE id = $id_producto";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
  
      if ($resultado->rowCount() > 0){
        $productos = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($productos);
      }else {
        echo json_encode("No existen productos en la BBDD con este ID.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 
  
  
  // POST Crear nuevo cliente 
  $app->post('/routes/nuevo', function(Request $request, Response $response){
     $nombreProducto = $request->getParam('nombreProducto');
     $precioProductp = $request->getParam('precioProductp');
     $categoria = $request->getParam('categoria');
     
    
    $sql = "INSERT INTO productos (nombreProducto, precioProductp, categoria) VALUES 
            (:nombreProducto, :precioProductp, :categoria)";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->prepare($sql);
  
      $resultado->bindParam(':nombreProducto', $nombreProducto);
      $resultado->bindParam(':precioProductp', $precioProductp);
      $resultado->bindParam(':categoria', $categoria);
  
      $resultado->execute();
      echo json_encode("Nuevo producto guardado.");  
  
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 
  
  // PUT Modificar cliente 
  $app->put('/routes/modificar/{id}', function(Request $request, Response $response){
     $id_producto = $request->getAttribute('id');
     $nombreProducto = $request->getParam('nombreProducto');
     $precioProductp = $request->getParam('precioProductp');
     $categoria = $request->getParam('categoria'); 
    
    $sql = "UPDATE productos SET
            nombreProducto = :nombreProducto,
            precioProductp = :precioProductp,
            categoria = :categoria
          WHERE id = $id_producto";
       
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->prepare($sql);
  
      $resultado->bindParam(':nombreProducto', $nombreProducto);
      $resultado->bindParam(':precioProductp', $precioProductp);
      $resultado->bindParam(':categoria', $categoria);
      
      $resultado->execute();
      echo json_encode("Producto modificado.");  
  
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 
  
  
  // DELETE borrar cliente 
  $app->delete('/routes/delete/{id}', function(Request $request, Response $response){
     $id_producto = $request->getAttribute('id');
     $sql = "DELETE FROM productos WHERE id = $id_producto";
       
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->prepare($sql);
       $resultado->execute();
  
      if ($resultado->rowCount() > 0) {
        echo json_encode("Producto eliminado.");  
      }else {
        echo json_encode("No existe productos con este ID.");
      }
  
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

});


