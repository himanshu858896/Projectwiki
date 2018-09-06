
<?php
/* This file is used for setting connection between mongodb and phpmyadmin.
    Database name: newdata.
*/

class DBConnection
{
const HOST = 'localhost';
const PORT = 27017;
const DBNAME = 'newdata'; 
private static $instance;
public $connection;
public $newdata;
private function __construct()
{
//Setting up path for connection 
$connectionString = sprintf('mongodb://%s:%d', DBConnection::HOST,
DBConnection::PORT);
try {
$this->connection = new Mongo($connectionString);
$this->newdata = $this->connection->
selectDB(DBConnection::DBNAME);
} catch (MongoConnectionException $e) {
throw $e;
}
}
static public function instantiate()
{
if (!isset(self::$instance)) {
$class = __CLASS__;
self::$instance = new $class;
}
return self::$instance;
}
public function getCollection($name)
{
return $this->newdata->selectCollection($name);
echo "hi";
}
}