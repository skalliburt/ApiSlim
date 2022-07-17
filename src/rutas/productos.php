<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// GET Todos los productos 
$app->get('/api/productos', function(Request $request, Response $response){
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
$app->get('/api/productos/{id}', function(Request $request, Response $response){
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
$app->post('/api/productos/nuevo', function(Request $request, Response $response){
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
$app->put('/api/productos/modificar/{id}', function(Request $request, Response $response){
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


// DELETE borar cliente 
$app->delete('/api/productos/delete/{id}', function(Request $request, Response $response){
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
