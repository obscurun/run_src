<?php
// ****************************************************************************************************************************
class PostgreQuery{
	private $query_string	= "";
	private $query_transaction  = true;
	private $query_result	= false;
	private $array_return	= false;
	private $_line 			="";
	private $_function		="";
	private $_class			="";
	private $_file			="";
	private $connection		=false;
	private $returnID		=false;
	public $postgre			="";
	public static $sql		="";
	//*************************************************************************************************************************
	function PostgreQuery($id){
		$this->query_string = "";
		$this->_line 		= __LINE__;
		$this->_function 	= __FUNCTION__;
		$this->_class 		= __CLASS__;
		$this->_file 		= __FILE__;
		$this->postgre 		= Postgre::getInstance($id);
	}
	//*************************************************************************************************************************
	function setLog($_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__){
		$this->_line 		= $_line;
		$this->_function 	= $_function;
		$this->_class 		= $_class;
		$this->_file 		= $_file;
		return $this;
	}
	//*************************************************************************************************************************
	function setConnection($connection){
		$this->connection 		= $connection;
		return $this;
	}
	//*************************************************************************************************************************
	function setReturnId(){
		$this->returnID 		= true;
		return $this;
	}
	//*************************************************************************************************************************
	function execute($sql=""){
		if($sql == "") $sql = $this->query_string;
		self::$sql = $sql;
		$this->query_result = $this->postgre->query($sql, $this->returnID, $this->_line, $this->_function, $this->_class, $this->_file, $this->connection);
		if(pg_last_error($this->query_result)){ 
			Debug::log("Query->execute: ".pg_last_error($this->query_result), $this->_line, $this->_function, $this->_class, $this->_file);
			Error::show(5200, "Model-> Erro no Query->Result ".__FUNCTION__, __FILE__, __LINE__, '');
		}
		return $this;
	}
	//*************************************************************************************************************************
	function returnFetchAssoc($resultObj=false, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__){
		return $this->postgre->returnFetchAssoc($resultObj, $n); 
	}
	//*************************************************************************************************************************
	function returnFetchArray($resultObj=false, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__){
		return $this->postgre->returnFetchArray($resultObj, $n); 
	}
	//*************************************************************************************************************************
	function returnFetchRow($resultObj=false, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__){
		return $this->postgre->returnFetchRow($resultObj, $n); 
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function resultSeek($resultQuery=false, $n=0){
		return $this->postgre->resultSeek($resultQuery, $n);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function returnNumRows($resultQuery=false, $n=0){
		return $this->postgre->returnNumRows($resultQuery, $n);
	}
	//*************************************************************************************************************************
	function returnAssoc($_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__){
		$this->array_return = array();
		$n=0;
		if($this->query_result){
			while($row = pg_fetch_assoc($this->query_result)){
				foreach($row as $k => $v){
					$this->array_return[$n][$k] = $v;
				}
				$n++;
			}
		}
		return $this->array_return;
	}
	//*************************************************************************************************************************
	function setSql($sql=""){
		Debug::log("Query->set: $sql", $this->_line, $this->_function, $this->_class, $this->_file);
		$this->query_string .= $sql;
		return $this;
	}
	//*************************************************************************************************************************
	function get(){
		Debug::log("Query->get: {$this->query_string} ", $this->_line, $this->_function, $this->_class, $this->_file);
		return $this->query_string;
	}
	//*************************************************************************************************************************
	function getID(){
		Debug::log("Query->getID: ", $this->_line, $this->_function, $this->_class, $this->_file);
		return $this->postgre->getID();
	}
	//*************************************************************************************************************************
	function getResult(){
		Debug::log("Query->getResult: {$this->_function} / {$this->_class} ", $this->_line, $this->_function, $this->_class, $this->_file);
		return $this->query_result;
	}
	//*************************************************************************************************************************
	function getAffecteds(){
		Debug::log("Query->getAffecteds: ", $this->_line, $this->_function, $this->_class, $this->_file);
		return $this->postgre->getAffecteds();
	}
	//*************************************************************************************************************************
	function getUniqueResult($query_obj="", $row=0, $index=0){
		if($query_obj=="") $postgrei_result = $this->query_result;
		else $postgrei_result = $query_obj;
		if(!is_object($postgrei_result)){ Error::show(5200, "Model-> Erro no Query->Result /".__FUNCTION__, __FILE__, __LINE__, ''); }
		else{
			$postgrei_result->resultSeek($row);
			$postgrei_result = $postgrei_result->fetch_row();
			$postgrei_result = $postgrei_result[$index];
			Debug::log("Query->getUniqueResult: ", $this->_line, $this->_function, $this->_class, $this->_file);
			return $postgrei_result;
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	public function getTotal($query_str="", $conn=false){
		Debug::log("Query->getTotal:", $this->_line, $this->_function, $this->_class, $this->_file);
		if($query_str == "") Error::show(0, "Query:: Não foi declarado o QUERY em {$this->_file} na linha: {$this->_line}.");
		$query_str = "SELECT COUNT(1) FROM (
						$query_str
					) AS total";
		$query_obj = $this->postgre->query($query_str, __LINE__, __FUNCTION__, __CLASS__, __FILE__, $conn);
		return $this->getUniqueResult($query_obj, 0, 0);
	}
	//*************************************************************************************************************************
	function select($fields=""){
		if($fields == "") Error::show(0, "Query:: Não foi declarado os Fields para o query em {$this->_file} na linha: {$this->_line}.");
		Debug::log("Query->select", $this->_line, $this->_function, $this->_class, $this->_file);
		if(is_array($fields)) $fields = implode(', ', $fields);
		$this->query_string = "SELECT ".$fields." \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function insert($table="", $_use_prefix=true){
		Debug::log("Query->insert", $this->_line, $this->_function, $this->_class, $this->_file);
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		$this->query_string = "INSERT INTO ".$table." \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function replace($table="", $_use_prefix=true){
		Debug::log("Query->replace", $this->_line, $this->_function, $this->_class, $this->_file);
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		$this->query_string = "REPLACE INTO ".$table." \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function fields($fields=""){
		Debug::log("Query->insert", $this->_line, $this->_function, $this->_class, $this->_file);
		if(is_array($fields)) $fields = implode(",\r\n ", $fields);
		if($fields == "") Error::show(0, "Query:: Não foi declarado os FIELDS para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= " ($fields) \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function values($values=""){
		Debug::log("Query->insert", $this->_line, $this->_function, $this->_class, $this->_file);
		if(is_array($values)) $values = implode(",\r\n ",  $values);
		if(!$values) Error::show(0, "Query:: Não foi declarado os VALUES para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= " VALUES(".$values.") \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function update($table="", $_use_prefix=true){
		Debug::log("Query->update", $this->_line, $this->_function, $this->_class, $this->_file);
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		//if(Config::QUERY_USE_PREFIX_TABLE) $table = Config::QUERY_PREFIX.$table;
		if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		$this->query_string = "UPDATE ".$table." \r\n";
		return $this;
	}
	//*************************************************************************************************************************
	function delete($table=""){
		Debug::log("Query->delete", $this->_line, $this->_function, $this->_class, $this->_file);
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		$this->query_string = "DELETE FROM ".$table." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function set($fields=""){
		if(is_array($fields)) $fields = implode(', ', $fields);
		$this->query_string .= "SET ".$fields." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function from($table="", $nick="", $_use_prefix=true){
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query.");
		if(is_array($table)){
			if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix)$table = implode(', '.$this->postgre->schema.Config::QUERY_PREFIX, $table);
			else			$table = implode(', ', $this->postgre->schema.$table);
		}
		else{
			if($nick != "") $table .= " ".$nick;
			if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix) $table = Config::QUERY_PREFIX.$table;
			$table = $this->postgre->schema.$table;
		}
		$this->query_string .= "FROM ". $table ." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function join($table="", $on="", $nick="", $_use_prefix=true){
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE && $_use_prefix) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		if($nick != "") $table .= " ".$nick;
		$this->query_string .= "JOIN ".$table." ON(". $on .") \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function leftjoin($table="", $on="", $nick=""){
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		if($nick != "") $table .= " ".$nick;
		$this->query_string .= "LEFT JOIN ".$table." ON(". $on .") \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function rightjoin($table="", $on="", $nick=""){
		if($table == "") Error::show(0, "Query:: Não foi declarado o TABLE para o query em {$this->_file} na linha: {$this->_line}.");
		if(Config::QUERY_USE_PREFIX_TABLE) $table = Config::QUERY_PREFIX.$table;
		$table = $this->postgre->schema.$table;
		if($nick != "") $table .= " ".$nick;
		$this->query_string .= "RIGHT JOIN ".$table." ON(". $on .") \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function where($where=""){
		if($where == "") Error::show(0, "Query:: Não foi declarado o WHERE para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= "WHERE ".$where." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function having($where=""){
		if($where == "") Error::show(0, "Query:: Não foi declarado o WHERE para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= "HAVING ".$where." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function group($fields=""){
		if(is_array($fields)) $fields = implode(', ', $fields);
		if($fields == "") Error::show(0, "Query:: Não foi declarado o GROUP BY para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= "GROUP BY ".$fields." \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function order($fields="", $mode=""){
		if(is_array($fields)) $fields = implode(', ', $fields);
		//if($where == "") Error::show(0, "Query:: Não foi declarado o ORDER BY para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= "ORDER BY ".$fields." $mode \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function limit($ini="", $qtd=""){
		if($ini === "" || $qtd==="") Error::show(0, "Query:: Não foi declarado o LIMIT para o query em {$this->_file} na linha: {$this->_line}.");
		$this->query_string .= "LIMIT ".$ini.", $qtd \r\n";
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
    function begin(){
        $this->postgre->autocommit(FALSE);
        return $this;
    }
	//-------------------------------------------------------------------------------------------------------------------------
    function queue(){
    	if(!$this->query_transaction) return $this;
        $result = $this->postgre->transactionQuery($this->query_string);
    	
        if(!$result){
        	Error::show(5200, "Model->query->queue() Erro na Transaction: ".$this->postgre->getpostgreError().__FUNCTION__, __FILE__, __LINE__, '');
        	$this->query_transaction = false;
        }
        
        return $this;
    }
	//-------------------------------------------------------------------------------------------------------------------------
    function commit(){
        if($this->query_transaction === false)
            $this->postgre->rollback();
        else
            $this->query_result = $this->postgre->commit();
        
        $this->postgre->autocommit(TRUE);
        return $this->query_result;
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public function getToken($id="default"){
		if(!is_array(Run::$session->get('TOKENS'))) Run::$session->set('TOKENS', array());
		$tk = Run::$session->get(array('TOKENS', ($id)));
		if($tk == "") Run::$session->set(array('TOKENS', $id), uniqid($id, true));
		return Run::$session->get(array('TOKENS', ($id)));
	}
	//--------------------------------------------------------------------------------------------------------------------------
	public function checkToken($id="default", $tk="--"){
		$token_session = Run::$session->get(array('TOKENS', ($id)));
		if($token_session == "" || $token_session != $tk){
			return false;
		}
		else{
			Run::$session->set(array('TOKENS', $id), "");
			return true;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>