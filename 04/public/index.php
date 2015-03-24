<?php
 
  //
  include_once '../sys/core/init.inc.php';

	//
	$cal = new Calendar($dbo, "2010-01-01 12:00:00");

	echo $cal->buildCalendar();
?>

