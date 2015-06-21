<?php
// ****************************************************************************************************************************
// A classe Postgre utiliza o conceito de Singleton e só será instanciada uma vez.
// Para criar a instancia deverá ser utilizado o método getInstance()
// Exemplo: $conexao = Postgre::getInstance($dadosConexao);
//
// $dadosConexao deve ser um array com os dados host,user,password e dbname
// O array deve conter um indice, pois a classe permite a conexão com diversos banco de dados distintos
// Exemplo:
// $dadosConexao['server1']["host"]
// $dadosConexao['server1']["name"]
// $dadosConexao['server1']["user"]
// $dadosConexao['server1']["pass"]

// $dadosConexao['server2']["host"]
// $dadosConexao['server2']["name"]
// $dadosConexao['server2']["user"]
// $dadosConexao['server2']["pass"]
// ############################################################################################################################
class Database{
	static private $instance;
	static private $connection 	= array();
	static public  $active	 	= false;
	//*************************************************************************************************************************
	private function __construct(){

	}
	//-------------------------------------------------------------------------------------------------------------------------
	static public function setActive($id){
		if(isset(self::$connection[$id])){
			self::$active = $id;
		}else{
			return false;
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