<?php 
   function __autoload($class){
	 $filename = "../sys/class/class." . $class . ".inc.php";
       echo $filename;
			if ( file_exists($filename) ){
				      include_once $filename;
			}
	 
	 }
   $cal = new Calendar($dbo, "2010-01-01 12:00:00");
?>
