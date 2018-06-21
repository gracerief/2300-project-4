<?php

$password = $_GET["password"];
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;

?>
