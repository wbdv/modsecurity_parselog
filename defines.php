<?php
$host 	 = 'localhost';
$db   	 = 'modsec';
$user 	 = 'modsec';
$pass 	 = '';
$charset = 'utf8mb4';

$dsn 	 = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $q = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

use MaxMind\Db\Reader;
$reader = new Reader('/usr/share/GeoIP/GeoLite2-Country.mmdb');

?>
