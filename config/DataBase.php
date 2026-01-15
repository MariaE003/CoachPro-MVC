<?php
class DataBase{
    private static $host="localhost";
    private static $dbname="fitcoachpro-mvc";
    private  static $username="postgres";
    private static $pass="maria";
    private static $port="5432";

    private static $pdo = null;

    public static function connect(){

        try{
            if (self::$pdo === null) {
            self::$pdo=new PDO("pgsql:host=" . self::$host . ";port=". self::$port . ";dbname=" . self::$dbname, self::$username, self::$pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            // echo "khedama";
            return self::$pdo;
            
            }
            return self::$pdo;
        }catch(PDOException $e){
            die("connection faild:".$e->getMessage());
        }
    }
}




?>