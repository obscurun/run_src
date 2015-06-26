<?php
// ****************************************************************************************************************************
require_once("database.php");
// ############################################################################################################################
class Postgre extends Database{
	static private 	$instance;
	static private 	$connection 	= array();
	static public  	$active	 		= false;
	public  		$schema	 		= "";
	public			$resultQuery	= NULL;
	public			$lastId			= NULL;
	//*************************************************************************************************************************
	private function __construct($id){		
		$this->newConnection($id);
	}
	//*************************************************************************************************************************
	private function newConnection($id){
		$connectionData = Model::getConnectionsData();
		// Run::$DEBUG_PRINT = 1;
		if(!$id)	foreach($connectionData as $k=>$v){ if($v['type_db'] == "postgre"){ $id = $k; break; }  }
		if(isset($connectionData[$id])){
			Debug::log("Iniciando POSTGRE.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			$data = $connectionData[$id] ;
			Debug::print_r("data", $data);
			if(self::$active == false){
				self::$active = $id;
			}
			$host = $data["host"];
			$name = $data["database"];
			$user = $data["user"];
			$pass = $data["pass"];
			$this->schema = $data["schema"];
			if($host && $name && $user){
				try {
					self::$connection[$id] = pg_connect('host='.$host.' dbname='.$name.' user='.$user.' password='.$pass);
				}
				catch(Exception $e) {
					Error::show(5200, "Erro ao conectar ao POSTGRE. Mensagem:". $e->getMessage(), __FILE__, __LINE__, '' );						
					return -2;
				}
				if(self::$connection[$id]->connect_error){
					ob_flush();
			        flush();
					Error::show(5200, "Erro ao conectar ao POSTGRE. Código:". Run::$control->string->encoding(pg_last_error(self::$connection[$id]) .' -- Mensagem:'. pg_last_error(self::$connection[$id]) ), __FILE__, __LINE__, '' );
					return -2;
				}
			}else{
				Error::show(5200, "Não conecta no POSTGRE: ".$host ." / ". $name ." / ". $user ." / ", __FILE__, __LINE__, '');
				return -2;
			}
		}else{
			Error::show(0, "Dados de conexão <b>$id</b> não foram definidos ", __FILE__, __LINE__, '');
			exit;
		}
	}
	//*************************************************************************************************************************
	static public function getInstance($id){
		if(!$id)	foreach(Model::getConnectionsData() as $k=>$v){ if($v['type_db'] == "postgre"){ $id = $k; break; }  }
		Debug::log("getInstance Postgre.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!isset(self::$instance) || !is_object(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class($id);
        }
		if(!isset(self::$connection[$id]) ) {
            self::$instance->newConnection($id);
        }
        return self::$instance;
	}
	//*************************************************************************************************************************
	//-------------------------------------------------------------------------------------------------------------------------
	public function getError(){
		$e = "";
		if (self::$connection[self::$active]->errorInfo()) { 
			$e = self::$connection[self::$active]->errorInfo(); 
		    $e = $e[0];
		} 
		return $e;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getWarning(){
		$e = "";
		if (pg_get_notify(self::$connection[self::$active])) { 
			$e = pg_get_notify(self::$connection[self::$active]); 
		 //   $e = implode(" / ", $e);
		} 
		return $e;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function query($sql, $returnId=false, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Postgre->query: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(isset(self::$connection[$conn])){
			$sql = $this->treatSpecials($sql);
			//self::$connection[$conn]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			try { 
				if($returnId != false) $sql .= " RETURNING ".$returnId;
				//echo "<br><br>sql: ".$sql;
			   	$result = pg_query(self::$connection[$conn], $sql) or Error::show(5552, "Ocorreu um erro na query da conexão ". $conn.". ".pg_result_error($this->resultQuery)." / ". pg_last_error(self::$connection[$conn]) .". Por favor, tente mais tarde. \r\n $sql", $_file, $_line, '');
				$id = pg_fetch_row($result);
				$this->lastId = $id[0];
			   	$this->resultQuery = $result;
				return $this->resultQuery;
			} catch(PDOException $e) {
				Debug::log("Postgre->query: ERRO: ".$e->getMessage() ." na linha ".$_line." / ".$_function." / ".$_class." ", $this->_line, $this->_function, $this->_class, $this->_file);
				Error::sqlError("Erro ao executar QUERY."." na linha ".$_line." / ".$_function." / ".$_class." ", $e->getMessage(), $sql);
				return -1;
			}
		} else{
			Error::show(5552, "A conexão ". $conn." não está disponível. Por favor, tente mais tarde.", $_file, $_line, '');
			return -2;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getID($resultQuery=false){
		if($resultQuery == false) $resultQuery = $this->resultQuery;
		$id = pg_last_oid($resultQuery);
		if($id == "" || $id == "0") $id = $this->lastId;
		return $id;
	}
	//*************************************************************************************************************************
	public function returnFetchAssoc($resultObj=false){
		if(!$resultObj) $resultObj = $this->resultQuery;
		return pg_fetch_assoc($resultObj); 
	}
	//*************************************************************************************************************************
	public function returnFetchArray($resultObj=false){
		if(!$resultObj) $resultObj = $this->resultQuery;
		return pg_fetch_array($resultObj); 
	}
	//*************************************************************************************************************************
	public function returnFetchRow($resultObj=false){
		if(!$resultObj) $resultObj = $this->resultQuery;
		return pg_fetch_row($resultObj); 
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function resultSeek($resultQuery=false, $n=0){
		if($resultQuery == false) $resultQuery = $this->resultQuery;
		pg_result_seek($resultQuery, $n);
		return $resultQuery;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function returnNumRows($resultObj=false){
		if(!$resultObj) $resultObj = $this->resultQuery;
		return pg_num_rows($resultObj); 
	}















	//-------------------------------------------------------------------------------------------------------------------------
	public function transactionQuery($sql){
		return self::$connection[self::$active]->query($sql);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getAffecteds($conn=false){
		if($conn == false) $conn = self::$active;
		return POSTGRE_affected_rows(self::$connection[$conn]);
	}
	//-------------------------------------------------------------------------------------------------------------------------
    public function autocommit($val){
        Debug::log("Postgre->autocommit", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        POSTGRE_autocommit(self::$connection[self::$active], $val);
        return;
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function commit(){
        Debug::log("Postgre->autocommit", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        return POSTGRE_commit(self::$connection[self::$active]);
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function rollback(){
        Debug::log("Postgre->rollback", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        return POSTGRE_rollback(self::$connection[self::$active]);
    }
    //-------------------------------------------------------------------------------------------------------------------------
	public function multi_query($sql, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__, $conn=false){
		Error::sqlError("Método multi_query não implementado."." na linha ".$_line." / ".$_function." / ".$_class." ");	
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function escape($str, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Postgre->escape: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		return pg_escape_string(self::$connection[$conn], $str);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function treatSpecials($str){
		$value = $str;
		/*
		$value = str_replace("“", "\\\"", $value);
		$value = str_replace("“", "\\\"", $value);
		$value = str_replace("”", "\\\"", $value);
		$value = str_replace("”", "\\\"", $value);
		$value = str_replace("–", "-", $value);
		$value = str_replace("–", "-", $value);
		$value = ereg_replace( chr(149), "&#8226;", $value );    # bullet •
		$value = ereg_replace( chr(150), "&ndash;", $value );    # en dash
		$value = ereg_replace( chr(151), "&mdash;", $value );    # em dash
		$value = ereg_replace( chr(153), "&#8482;", $value );    # trademark
		$value = ereg_replace( chr(169), "&copy;", $value );    # copyright mark
		$value = ereg_replace( chr(174), "&reg;", $value );        # registration mark
		*/
		return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function close($connection_name=false){
		if($connection_name == false) $connection_name = self::$active;
		Debug::log("Postgre->close: $connection_name", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if($connection_name){
			if($this->connection[$connection_name]){
				pg_close($this->connection[$connection_name]);
				$this->connection[$connection_name] = false;
			}
		}else{
			foreach($this->connection as $nome=>$inst){
				pg_close($this->connection[$nome]);
				$this->connection[$nome] = false;
			}
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function restartInstance(){
		$this->__construct();
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ############################################################################################################################
// http://br3.php.net/manual/en/POSTGRE.connect.php
// http://websec.wordpress.com/2010/03/19/exploiting-hard-filtered-sql-injections/
?>