<?php
       class Conexion extends PDO{
            private $tipo_de_base='mysql';
            private $host='bpmj4rqdpbyvehsnomco-mysql.services.clever-cloud.com';
            private $nombre_bd='bpmj4rqdpbyvehsnomco';
            private $usuario='utmaij9fnwrx5jgi';
            private $contra='qfvml5cDr4Byc8SOUMQl';
            public function __construct(){
                try {
                    parent::__construct("{$this->tipo_de_base}:dbname={$this->nombre_bd};host={$this->host};charset=utf8",$this->usuario,$this->contra);
                } catch (PDOException $e) {
                    echo 'Existe un error: '.$e->getMessage();
                }
            }
       }
?>