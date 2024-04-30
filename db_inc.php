<?php
$host = 'localhost';
$user = 'root';
$passwd = 'heslo';
$schema = 'social_network';
$pdo = NULL;
$dsn = 'mysql:host=' . $host . ';dbname=' . $schema;
try
{  
   $pdo = new PDO($dsn, $user,  $passwd);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
   echo 'Database connection failed<br>';
   print_r($e);
   die();
}
?>