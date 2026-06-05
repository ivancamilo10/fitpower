<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Nuevo hash para admin123: <br><textarea cols='100'>" . $hash . "</textarea>";
?>
