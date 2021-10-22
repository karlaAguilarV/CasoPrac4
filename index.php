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
        $conexion = new Conexion();
        $consulta = $conexion->prepare("SELECT id_computadora, Modelo, Pulgadas, RAM, Almacenamiento FROM caracteristicas");
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        $caracteristicas=array();
        $i=0;
        foreach($resultado as $res)
        {
            $caracteristicas[$i]["id"] = $res["id_computadora"];
            $caracteristicas[$i]["modelo"] = $res["Modelo"];
            $caracteristicas[$i]["pulgadas"] = $res["Pulgadas"];
            $caracteristicas[$i]["ram"] = $res["RAM"];
            $caracteristicas[$i]["almacenamiento"] = $res["Almacenamiento"];
            $i++;
        }
        
        return $caracteristicas;
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
    array(),
    array('return'=> 'xsd:Array'),
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