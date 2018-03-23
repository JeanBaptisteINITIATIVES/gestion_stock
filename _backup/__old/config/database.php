<?php

class Database
{
    private static $dbHost          = "localhost";
    private static $dbName          = "gestion_stock";
    private static $dbUser          = "root";
    private static $dbUserPassword  = 'root';

    private static $connection = null;
    
    public static function connect()
    {
        try
        {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbUserPassword, array(PDO::ATTR_PERSISTENT => true));
            self::$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection -> exec("SET CHARACTER SET utf8");
        }
        catch(PDOException $e)
        {
            die('ERROR: ' . $e -> getMessage());
        }
        return self::$connection;
    }
    
    public static function disconnect()
    {
        self::$connection = null;
    }
}

$db = Database::connect();