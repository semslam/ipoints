<?php

class Pdolib
{
  private $ci;
  private static $pdo;
  private $username;
  private $password;
  private $dbname;
  private $host;
  private $port = 3306;
  private $charset = 'utf8mb4';

  private $options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];

  public function __construct()
  {
    $this->ci =& get_instance();
    $this->init();
    if (ENVIRONMENT !== 'development') {
      $this->options += [
        PDO::MYSQL_ATTR_SSL_CA => '/home/ipoints/._ssl/BaltimoreCyberTrustRoot.crt.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
      ];
    }
    SELF::$pdo = new PDO($this->makeDSN(),$this->username,$this->password,$this->options);
    /*self::$pdo = new PDO(

      "mysql:host=localhost;dbname=celd_db_master;port=3306;charset=utf8mb4",
      "root",
      "",

      /*
      "mysql:host=localhost;dbname=celd_db_uat;port=3306;charset=utf8mb4",
      "root",
      "e8b7bbbd9506251c5b68fd0d03208b14a88ef2d51dba0ee9",
      */
      /*"mysql:host=localhost;dbname=celd_db;port=3306;charset=utf8mb4",
      "root",
      "f6d0f5759b05ac7e02f03ead242d13b7d91b88e9aab0bfff",
      */
      /*array(
        PDO::ATTR_EMULATE_PREPARESOC => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASS
      )
    );*/
  }

  private function init(){
    $this->ci->load->database();
    $this->username = $this->ci->db->username;
    $this->password = $this->ci->db->password;
    $this->dbname = $this->ci->db->database;
    $this->host = $this->ci->db->hostname;
    $this->makeDSN();
  }

  public function makeDSN(){
    return 'mysql:host='.$this->host.';dbname='.$this->dbname.';port='.$this->port.';charset='.$this->charset;
  }

  public static function getPDO()
  {
    if (! self::$pdo)
    {
      $lib = new Pdolib();
      return $lib->pdo;
    }
    return self::$pdo;
  }
}

?>