<?php
namespace App;
use SQLite3;
class DataBase{
    private static ?SQLite3 $db = null;
    private function __construct(){
    }

    public static function getData(){
        if (self::$db === null){
            $conf = require __DIR__ . '/../config.php';
            self::$db = new SQLite3($conf['db']);
        }
        return self::$db;
    }

    private function __clone () {}
    private function __wakeup () {}

}