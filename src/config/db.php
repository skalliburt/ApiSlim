<?php
  class db{
    private $dbHost ='localhost';
    private $dbUser = 'root';
    private $dbPass = '';
    private $dbName = 'supermercado';
    //conexión 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnecion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnecion;
    }
  }