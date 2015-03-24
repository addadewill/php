<?php
$pdo = new PDO("mysql:host=localhost;dbname=php-app","root","mysql");
$rs = $pdo -> query("select * from events");
while($row = $rs -> fetch()){
	print_r($row); 
}
?>
