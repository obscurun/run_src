<?php
require(RUN_PATH."core/model/mysql.php");
require(RUN_PATH."core/model/postgre.php");
require(RUN_PATH."core/model/mysql_query.php");
require(RUN_PATH."core/model/postgre_query.php");
//*****************************************************************************************************************************
class Model{
	public 	 	 	 $mysql				= "";
	public 	 	 	 $postgre			= "";
	public 		 	 $sqlite			= "";
	public 			 $query				= "";
	public 			 $form				= "";
	protected static $connectionData	= array();
	//*************************************************************************************************************************
	function Model(){
		Debug::log("Iniciando Core/Model.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->startPostgre();
		$this->startMysql();
	}
	//*************************************************************************************************************************
	function startMysql(){
		if(Run::MYSQL === true){
			$this->dataBase = Mysql::getInstance();
			$this->query 	= new MysqlQuery();
		}
	}
	//*************************************************************************************************************************
	function startPostgre(){
		if(Run::POSTGRE === true){
			$this->dataBase = Postgre::getInstance();
			$this->query 	= new PostgreQuery();
		}
	}
	//*************************************************************************************************************************
	static public function getInstance($type="mysql"){
		if(Run::MYSQL === true && $type == "mysql"){
			return Mysql::getInstance();
		}
		else if(Run::POSTGRE === true && $type == "postgre"){
			return Postgre::getInstance();
		}else{
			return false;
		}
	}
	//*************************************************************************************************************************
	static public function setConnectionData($type_db,$host,$name,$user,$pass,$index){
		self::$connectionData[$index]["host"] 		= $host;
		self::$connectionData[$index]["name"] 		= $name;
		self::$connectionData[$index]["user"]  		= $user;
		self::$connectionData[$index]["pass"]  		= $pass;
		self::$connectionData[$index]["type_db"]	= $type_db;
		Debug::log("setConnectionData($host/$name/$user/$pass/$index)", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//*************************************************************************************************************************
	static public function getConnectionData($id){
		return self::$connectionData[$id];
	}
	//*************************************************************************************************************************
	static public function getConnectionsData(){
		return self::$connectionData;
	}
}
//*****************************************************************************************************************************
?>