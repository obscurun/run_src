<?php
// ****************************************************************************************************************************
require_once("database.php");
// ############################################################################################################################
class Postgre extends Database{
	static private $instance;
	static private $connection 	= array();
	static public  $active	 	= false;
	//*************************************************************************************************************************
	private function __construct(){
		$connectionData = Model::getConnectionsData();
		if(count($connectionData)>0){
			Debug::log("Iniciando POSTGRE.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			foreach($connectionData as $conn => $data){
				if($data["type_db"] !== "postgre") continue;
				if(self::$active == false){
					self::$active = $conn;
				}
				$host = $data["host"];
				$name = $data["name"];
				$user = $data["user"];
				$pass = $data["pass"];
				if($host && $name && $user){
					//echo "$host, $user, $pass, $name";
					try {
						self::$connection[$conn] = new PDO('pgsql:host='.$host.';dbname='.$name, $user, $pass);
					}
					catch(Exception $e) {
						Error::show(5200, "Erro ao conectar ao POSTGRE. Mensagem:". $e->getMessage(), __FILE__, __LINE__, '' );						
						return -2;
					}
					if(self::$connection[$conn]->connect_error){
						ob_flush();
				        flush();
						Error::show(5200, "Erro ao conectar ao POSTGRE. Código:". Run::$control->string->encoding(self::$connection[$conn]->connect_errno .' -- Mensagem:'. self::$connection[$conn]->connect_error), __FILE__, __LINE__, '' );
						return -2;
					}
				}else{
					Error::show(5200, "Não conecta no POSTGRE: ".$host ." / ". $name ." / ". $user ." / ", __FILE__, __LINE__, '');
					return -2;
				}
			}
		}else{
			Error::show(0, "Dados de conexão não foram definidos ", __FILE__, __LINE__, '');
			exit;
		}
	}
	//*************************************************************************************************************************
	static public function getInstance(){
		Debug::log("getInstance Postgre.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!isset(self::$instance) || !is_object(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
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
		if (self::$connection[self::$active]->errorInfo()) { 
			$e = self::$connection[self::$active]->errorInfo(); 
		    $e = implode(" / ", $e);
		} 
		return $e;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function query($sql, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Postgre->query: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(isset(self::$connection[$conn])){
			//echo "<br><br>sql: ".$sql;
			$sql = $this->treatSpecials($sql);
			self::$connection[$conn]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			try { 
			   	$result = self::$connection[$conn]->exec($sql);
				return $result;
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
	public function transactionQuery($sql){
		return self::$connection[self::$active]->query($sql);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getID($conn=false){
		if($conn == false) $conn = self::$active;
		return self::$connection[$conn]->lastInsertId();
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
		if($conn == false) $conn = self::$active;
		Debug::log("Postgre->multi_query: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$return = array();
		$result = false;
		if((self::$connection[$conn])){
			if (POSTGRE_multi_query(self::$connection[$conn], $sql)) {
				do {
					if ($result = POSTGRE_store_result(self::$connection[$conn])) {
						while ($row = POSTGRE_fetch_row($result)) {
							array_push($return, $row[0]);
						}
						POSTGRE_free_result($result);
					}
					if (POSTGRE_more_results(self::$connection[$conn])){
						//printf("-----------------\n");
					}
				} while (POSTGRE_next_result(self::$connection[$conn]));
				return $return;
			}
			else{
				Error::sqlError("Erro ao executar QUERY."." na linha ".$_line." / ".$_function." / ".$_class." ", self::$connection[$conn]->error, $result);
				return -2;
			}
		} else{
			Error::show(5553, "A conexão ".$conn." não está disponível. Por favor, tente mais tarde. (".self::$connection[$conn].")", $_file, $_line, '');
			return -2;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function escape($str, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Postgre->escape: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		return self::$connection[$conn]->real_escape_string($str);
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
				$this->connection[$connection_name]->close();
				$this->connection[$connection_name] = false;
			}
		}else{
			foreach($this->connection as $nome=>$inst){
				$this->connection[$nome]->close();
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