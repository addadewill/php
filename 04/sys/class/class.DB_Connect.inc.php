<?php 
  /**
  *需要连接数据的类，只要继承这个类就可以自动连接数据库
	*这个类建立并储存数据库连接
	*/
class DB_Connect {
  //这个属性保存数据库对象
	protected $db;
  /**
	 *$dbo 是一个数据库对象
	 */
	protected function __construct($dbo=NULL){
		//因为初始文件
	  if ( is_object($db)){
		   $this->db = $db;
		}
		else{
		  $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
			try{
			  $this->db = new PDO($dsn, DB_USER, DB_PASS);
			}
			catch ( Exception $e ){
			  die ( $e->getMessage() );
			}
		}
	}
}
	
?>
