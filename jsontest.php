<?php
/*require_once("includes/config.php");

$user='harshal';
$pw=hash('sha512', $salt1.'HAKj ss@9575-8959-221133'.$salt2);

$q = $con->prepare("INSERT INTO authenticate(username, password) VALUES(:un, :pw)");
$q->bindParam(':un', $user);
$q->bindParam(':pw', $pw);

$q->execute();*/

echo isset($_FILES['image']) ? 'true':'false';
?>