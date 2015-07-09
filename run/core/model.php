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
	public 	  static $query				= NULL;
	public 			 $form				= "";
	protected static $connectionData	= array();
	//*************************************************************************************************************************
	function Model(){
		Debug::log("Iniciando Core/Model.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
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
	static public function connect($id){
		return self::getInstance($id);
	}
	//*************************************************************************************************************************
	static public function getInstance($id=false){
		if(!$id)	foreach(self::$connectionData as $k=>$v){ $idC = $k; break; }
		else $idC = $id;
		$connection = Model::getConnectionData($idC);
		$database 	= NULL;
		// Debug::print_r("getInstance $id ", $connection);
		if(Run::MYSQL === true && $connection['type_db'] == "mysql"){
			$database = Mysql::getInstance($id);
			if($id) Mysql::setActive($id);
			self::$query 	= new MysqlQuery($id);
			return $database;
		}
		else if(Run::POSTGRE === true && $connection['type_db'] == "postgre"){
			$database = Postgre::getInstance($id);
			if($id) Postgre::setActive($id);
			self::$query 	= new PostgreQuery($id);
			return $database;
		}else{
			return false;
		}
	}
	//*************************************************************************************************************************
	static public function setConnection($type_db, $id, $host, $database, $schema="", $user, $pass){
		if(!$useSchemaPrefix) $useSchemaPrefix = Config::QUERY_USE_PREFIX_SCHEMA;
		self::$connectionData[$id]["type_db"]		= $type_db;
		self::$connectionData[$id]["host"] 			= $host;
		self::$connectionData[$id]["database"] 		= $database;
		self::$connectionData[$id]["user"]  		= $user;
		self::$connectionData[$id]["pass"]  		= $pass;
		self::$connectionData[$id]["schema"]  		= $schema;
		if($schema != "") self::$connectionData[$id]["schema"] = $schema.".";
		Debug::log("setConnection($host/$name/$user/$pass/$index)", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//*************************************************************************************************************************
	static public function getConnectionData($id="default"){
		if(isset(self::$connectionData[$id])) return self::$connectionData[$id];
		else{
			foreach(self::$connectionData as $k=>$v){ return self::$connectionData[$k]; }
		}
	}
	//*************************************************************************************************************************
	static public function getConnectionsData(){
		return self::$connectionData;
	}
}
//*****************************************************************************************************************************
?>