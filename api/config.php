<?php
// config.php
$host = 'bmimupwfdoqyre4hevac-mysql.services.clever-cloud.com';
$dbname = 'bmimupwfdoqyre4hevac';
$username = 'uucxjcyiwmavxdqv';
$password = 'ElqnDXicqp2HzNxLd0lb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
?>
