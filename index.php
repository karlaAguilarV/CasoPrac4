<?php
require_once 'conexion.php';
require_once 'lib/nusoap.php';
//Insertar Compu
function insertCompu($modelo,$pulgadas,$ram,$almacenamiento){
    try {
        $conexion= new Conexion();
        $consulta=$conexion->prepare("INSERT INTO caracteristicas(Modelo,Pulgadas,RAM,Almacenamiento)
        VALUES(:modelo, :pulgadas, :ram, :almacenamiento)");
        $consulta-> bindParam(":modelo",$modelo, PDO::PARAM_STR);
        $consulta-> bindParam(":pulgadas",$pulgadas, PDO::PARAM_STR);
        $consulta-> bindParam(":ram",$ram, PDO::PARAM_STR);
        $consulta-> bindParam(":almacenamiento",$almacenamiento, PDO::PARAM_STR);

        $consulta->execute();
        $ultimoID=$conexion->lastInsertId();
        return join(",",array($ultimoID));
    } catch (Exception $e) {
        return join(",",array(false)); 
    }    
}

//ELIMINAR COMPUTADORA
function EliminarCompu($id_compu){
    try {
        $conexion= new Conexion();
        $consulta=$conexion->prepare("DELETE FROM caracteristicas WHERE id_computadora = :id_computadora");
        $consulta-> bindParam(":id_computadora",$id_compu, PDO::PARAM_INT);
        $consulta->execute();
        $sql= $consulta->fetch(PDO::FETCH_ASSOC);
        return join(",",array(true));
    } catch (Exception $e) {
        return join(",",array(false)); 
    } 
} 
//CONSULTAR COMPUTADORA
function ConsultarCompu(){
    try {
        $conexion= new Conexion();
        $consulta=$conexion->prepare("SELECT Modelo,Pulgadas,RAM,Almacenamiento FROM caracteristicas"); //WHERE id_computadora = '$id_compu'");
        
        /*  $consulta-> bindParam(":id_computadora",$id_compu, PDO::PARAM_INT);
        $consulta-> bindParam(":Modelo",$modelo, PDO::PARAM_STR);
        $consulta-> bindParam(":Pulgadas",$pulgadas, PDO::PARAM_STR);
        $consulta-> bindParam(":RAM",$ram, PDO::PARAM_STR);
        $consulta-> bindParam(":Almacenamiento",$almacenamiento, PDO::PARAM_STR);*/
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        
        foreach($resultado as $res)
      {
        
        echo "<tr>";
        echo "<td>".$res["id_computadora"]."</td>";
        echo "<td>".$res["Modelo"]."</td>";
        echo "<td>".$res["Pulgadas"]."</td>";
        echo "<td>".$res["RAM"]."</td>";
        echo "<td>".$res["Almacenamiento"]."</td>";
        echo "</tr>";
      } 
     
      
        
    } catch (Exception $e) {
        return join(",",array(false)); 
    } 
} 

//ACTUALIZAR COMPUTADORA
function UpdateCompu($id_compu,$modelo,$pulgadas,$ram,$almacenamiento){
    try {
        $conexion= new Conexion();
        $consulta=$conexion->prepare("UPDATE caracteristicas 
        SET Modelo='$modelo',Pulgadas='$pulgadas',RAM='$ram',Almacenamiento='$almacenamiento'
        WHERE id_computadora = '$id_compu'");
        $consulta-> bindParam(":id_computadora",$id_compu, PDO::PARAM_INT);
        $consulta-> bindParam(":modelo",$modelo, PDO::PARAM_STR);
        $consulta-> bindParam(":pulgadas",$pulgadas, PDO::PARAM_STR);
        $consulta-> bindParam(":ram",$ram, PDO::PARAM_STR);
        $consulta-> bindParam(":almacenamiento",$almacenamiento, PDO::PARAM_STR);
        
        $consulta->execute();
        return join(",",array(true));
        
    } catch (Exception $e) {
        return join(",",array(false)); 
    } 
} 

//REGISTRO insertar
$server=new nusoap_server();
$server->configureWSDL("index","urn:index");
$server->register("insertCompu",
      array("modelo"=>"xsd:string","pulgadas"=>"xsd:string","ram"=>"xsd:string","almacenamiento"=>"xsd:string"),
      array("return" =>"xsd:string"),
      "urn:index",
      "urn:index#insertCompu",
      "rpc",
      "encoded",
      "Inserta una caracteristica de computadora");

      $server->register("EliminarCompu",
      array("id_computadora"=>"xsd:integer"),
      array("return" =>"xsd:integer"),
      "urn:index",
      "urn:index#EliminarCompu",
      "rpc",
      "encoded",
      "Elimina una caracteristica de computadora");

      $server->register("ConsultarCompu",
       array("id_computadora"=>"xsd:integer"),
      array("return" =>"xsd:integer"),
      "urn:index",
      "urn:index#ConsultarCompu",
      "rpc",
      "encoded",
      "Consultar una caracteristica de computadora");
      $server->register("UpdateCompu",
      array("id_computadora"=>"xsd:integer","modelo"=>"xsd:string","pulgadas"=>"xsd:string","ram"=>"xsd:string","almacenamiento"=>"xsd:string"),
      array("return" =>"xsd:string"),
      "urn:index",
      "urn:index#UpdateCompu",
      "rpc",
      "encoded",
      "Actualiza una caracteristica de computadora");
      $post=file_get_contents('php://input');
      $server->service($post);
     
?>