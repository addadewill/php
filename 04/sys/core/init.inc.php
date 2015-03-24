<?php
    error_reporting(E_ALL & ~E_NOTICE);
  //把常量文件包含进来
  include_once '../sys/config/db-cred.inc.php';

	//定义常量
	foreach ($C as $name => $val ){ define($name, $val); }
  /**
	 *初始化一个PDO对象，不初始化了不要紧，DB_Connect 
	 *的构告函数会因为没有数据库对象传入而新建一个PDO对象
	 */
	$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
	$dbo = new PDO($dsn, DB_USER, DB_PASS);

	//自动载入类，类名注意大小写
	function __autoload($class){
    $filename = "../sys/class/class." . $class . ".inc.php";
		if ( file_exists($filename) ){
      include_once $filename;
		}
  }
?>
