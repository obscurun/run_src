<?php
// ****************************************************************************************************************************
class SelectData{
	private $checkBuildFirst 	= false;
	private $query_errors 		= 0;
	private $database 			= NULL;
	private $query 				= NULL;
	public  $orderData 			= NULL;
	private $model 				= NULL;
	private $fetchData			= NULL;
	private $queryResult		= NULL;
	//*************************************************************************************************************************
	function SelectData($model){
		Debug::log("Iniciando Core/Form/Select.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->query_errors = 0;
		$this->model 		= $model;
		$this->orderData 	= $this->model->orderData;
		$this->database 	= $this->model->database;
		$this->query 		= $this->model->query;
		//Run::$DEBUG_PRINT = true;
		//Debug::p('query', Run::$control->typeof($this->model->database) );
		//Run::$DEBUG_PRINT = false;
	}
	//*************************************************************************************************************************
	public function select($type, $dataIntern, $dataForm, $schema, $settings, $schema_unions){
		Run::$benchmark->mark("SelectData/select/Inicio");
		$sql = $this->buildSQL($type, $dataIntern, $schema, $schema_unions);
		Run::$benchmark->writeMark("SelectData/select/Inicio", "SelectData/select/buildSQL");
		$dataSelectSequencial 	= array();
		$dataSelectTabulated 	= array();
		$dataSelectRecursive 	= array();
		$dataSelectPKList 		= array();

		
		//Run::$DEBUG_PRINT = true;
		Debug::p("sql", $sql);
		//exit;


		$this->queryResult = $this->query->setLog(__LINE__, __FUNCTION__, __CLASS__, __FILE__)->setConnection($this->model->settings['database_id'])->setReturnId()->execute($sql)->getResult();
		//$this->fetchData = $this->queryResult; //$this->query->returnFetchAssoc($this->queryResult);
		Run::$benchmark->writeMark("SelectData/select/buildSQL", "SelectData/select/database->query(sql)");
		if(!$this->queryResult){
			Error::show(0, "MODEL:: Houve um erro ao executar o select query automaticamente: ".$sql);
		}


		//Debug::p( $this->query->returnNumRows($this->queryResult) );
		if($this->queryResult === -2) Error::show(0, "MODEL:: Houve um erro ao executar o select query automaticamente: ".$sql);
		else if($this->query->returnNumRows($this->queryResult) > 0){
			$dataSelectSequencial 	= $this->buildSQLDataSequencial($type, $this->queryResult, $schema, $settings, $dataIntern);
			Run::$benchmark->writeMark("SelectData/select/buildSQL", "SelectData/select/buildSQLDataSequencial");
			if($settings['select_tabulated'] === true){
				$dataSelectTabulated 	= $this->buildSQLData($type, $this->queryResult, $schema, $settings, $dataIntern);
				Run::$benchmark->writeMark("SelectData/select/buildSQL", "SelectData/select/buildSQLDataTabulated");
			}
			if($settings['select_recursive'] === true){
				$orderTables 			= $this->orderData->getOrderedTables($data, $schema);
				$dataSelectRecursive 	= $this->buildSQLDataRecursive($type, $orderTables, $this->queryResult, $schema, $settings, $dataIntern);
				Run::$benchmark->writeMark("SelectData/select/buildSQL", "SelectData/select/buildSQLDataRecursive");
			}
			$dataSelectPKList			= $this->buildSQLPKList($type, $this->queryResult, $schema, $settings, $dataIntern);

			Run::$benchmark->writeMark("SelectData/select/Inicio", "SelectData/select/Final");
		}
		//Debug::p( $this->query->returnNumRows($this->queryResult) );
		Debug::p( $dataSelectSequencial );
		//Debug::p($dataSelectTabulated);
		//Debug::p($dataSelectRecursive);
		return array(
			"dataSelectSequencial" 	=> $dataSelectSequencial,
			"dataSelectTabulated" 	=> $dataSelectTabulated,
			"dataSelectRecursive" 	=> $dataSelectRecursive,
			"dataSelectPKList" 		=> $dataSelectPKList
		);
	}
	//*************************************************************************************************************************
	public function getList(){
		//Run::$DEBUG_PRINT = true;
		$dataList = array();
		$this->prepareList();
		$sql = $this->buildSQL("list", $this->model->dataIntern, $this->model->schema, $this->model->schema_unions);


		$this->queryResult = $this->query->setLog(__LINE__, __FUNCTION__, __CLASS__, __FILE__)->setConnection($this->model->settings['database_id'])->setReturnId()->execute($sql)->getResult();
		//$this->fetchData = $this->queryResult; //$this->query->returnFetchAssoc($this->queryResult);
		Run::$benchmark->writeMark("SelectData/select/buildSQL", "SelectData/select/database->query(sql)");
		if(!$this->queryResult){
			Error::show(0, "MODEL:: Houve um erro ao executar o select query automaticamente: ".$sql);
		}


		if($this->queryResult === -2) Error::show(0, "MODEL:: Houve um erro ao executar o select query automaticamente: ".$sql);
		else if($this->query->returnNumRows($this->queryResult) > 0){
			$dataSelectSequencial 	= $this->buildSQLDataList('list', $this->queryResult, $this->model->schema, $this->model->settings, $this->model->dataIntern);
			Run::$benchmark->writeMark("SelectData/select/Inicio", "SelectData/select/Final");
		}


		$sql_total = $this->buildSQLTotal("list", $this->model->dataIntern, $this->model->schema);
		$total = $this->query->setLog(__LINE__, __FUNCTION__, __CLASS__, __FILE__)->setConnection($this->model->settings['database_id'])->setReturnId()->execute($sql_total)->getResult();
		//$this->queryResult = $this->query->execute($sql, false, false, __LINE__, __FUNCTION__, __CLASS__, __FILE__, $this->model->settings['database_id']);
		//Debug::p($sql);
		//Debug::p($sql_total);
		//Debug::p($dataSelectSequencial);
		//Debug::p($dataSelectTabulated);
		//Debug::p($dataSelectRecursive);
		return array(
			"list" 	=> $dataSelectSequencial,
			"total" 	=> $total
		);
		//Debug::p("sql", $sql);
		//exit;
	}
	//*************************************************************************************************************************
	public function prepareList(){
		$data_int = array();
		$sets = $this->model->settings;
		if(!isset($data_int[$sets['paging_ref'].'busca'])) $data_int[$sets['paging_ref'].'busca'] 	 = "";
		$data_int[$sets['paging_ref'].'use_default'] = (isset($data_int[$sets['paging_ref'].'ordem'])) ? false : $this->model->schema['orderby'];

		if(!isset($data_int[$sets['paging_ref']."index"])){
			if((int)Run::$router->getLevel($sets['paging_param_ref'], true) > 0 && $sets['use_url_ref'] === true) $data_int[$sets['paging_ref'].'index'] = (int)Run::$router->getLevel($sets['paging_param_ref'], true);
			else if(isset($_GET[$sets['ref']]) && (int)$_GET[$sets['ref']] > 0) $data_int[$sets['paging_ref'].'index'] = (int)$_GET[$sets['ref']];
		}
		if((int)$data_int[$sets['paging_ref'].'index'] < 1) $data_int[$sets['paging_ref'].'index']	= 1;
		if(!isset($data_int[$sets['paging_ref'].'ordem'])) 	$data_int[$sets['paging_ref'].'ordem']	= $this->model->schema['from']['pk'];
		if(!isset($data_int[$sets['paging_ref'].'modo'])) 	$data_int[$sets['paging_ref'].'modo']	= "desc";
		if(!isset($data_int[$sets['paging_ref'].'num'])) 	$data_int[$sets['paging_ref'].'num']	= $sets['paging_num'];
		
		$sets['paging_num'] = $data_int[$sets['paging_ref'].'num'];
		if($data_int[$sets['paging_ref'].'modo'] == 'asc')		$data_int[$sets['paging_ref'].'contramodo']	= 'desc'; 
		else $data_int[$sets['paging_ref'].'contramodo'] = 'asc';
		
		if(isset($_GET[$sets['ref']])){
			if((int)$_GET[$sets['ref']] > 0) $data_int[$sets['ref']] = (int)$_GET[$sets['ref']];		
		}else if(!isset($data_int[$sets['ref']])){
			if((int)Run::$router->getLevel($sets['paging_param_ref'], true) > 0) $data_int[$sets['ref']] = (int)Run::$router->getLevel($sets['paging_param_ref'], true);		
		}

		if($p_index>0) $item_inicial = (($data_int[$sets['paging_ref'].'index']-1) * $data_int[$sets['paging_ref'].'num']); 
		else $item_inicial = 0;

		$data_int[$sets['paging_ref'].'limit']	= array($item_inicial, $data_int[$sets['paging_ref'].'num']);
		if(isset($_GET[$sets['paging_ref'].'export']) == true){	$data_int[$sets['paging_ref'].'limit']	= false;	}

		$this->model->dataIntern = $data_int;
		//Debug::p("data_int", $data_int);

		return $data_int;
	}
	//*************************************************************************************************************************
	public function buildSQLPKList($type, $queryResult, $schema, $settings, $dataIntern, $fkTableID=0){	
		Run::$benchmark->mark("SelectData/buildSQLPKList/Inicio");
		//Debug::p($orderTables);	
		//$queryResult->data_seek(0);
		$dataTable = array();
		foreach($schema['from'] as $index => $paramTable){
			$dataTable = $this->fetchSQLPKList($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		Run::$benchmark->writeMark("SelectData/buildSQLPKList/Inicio", "SelectData/buildSQLPKList/schema[from]");
		foreach($schema['join'] as $index => $paramTable){
			$dataTable = $this->fetchSQLPKList($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		Run::$benchmark->writeMark("SelectData/buildSQLPKList/schema[from]", "SelectData/buildSQLPKList/schema[joins]");
		return $dataTable;
	}
	//*************************************************************************************************************************
	public function buildSQLDataList($type, $queryResult, $schema, $settings, $dataIntern, $fkTableID=0){	
		Run::$benchmark->mark("SelectData/buildSQLDataSequencial/Inicio");
		//Debug::p($orderTables);	
		//$queryResult->data_seek(0);
		$dataTable = array();
		foreach($schema['from'] as $index => $paramTable){
			$dataTable = $this->fetchSQLDataList($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		Run::$benchmark->writeMark("SelectData/buildSQLDataSequencial/Inicio", "SelectData/buildSQLDataSequencial/schema[from]");
		foreach($schema['join'] as $index => $paramTable){
			$dataTable = $this->fetchSQLDataList($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		Run::$benchmark->writeMark("SelectData/buildSQLDataSequencial/schema[from]", "SelectData/buildSQLDataSequencial/schema[joins]");
		return $dataTable;
	}
	//*************************************************************************************************************************
	public function buildSQLDataSequencial($type, $queryResult, $schema, $settings, $dataIntern, $fkTableID=0){	
		Run::$benchmark->mark("SelectData/buildSQLDataSequencial/Inicio");
		//$queryResult->data_seek(0);
		$dataTable = array();
		foreach($schema['from'] as $index => $paramTable){
			$dataTable = $this->fetchSQLDataSequencial($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		Run::$benchmark->writeMark("SelectData/buildSQLDataSequencial/Inicio", "SelectData/buildSQLDataSequencial/schema[from]");
		foreach($schema['join'] as $index => $paramTable){
			$dataTable = $this->fetchSQLDataSequencial($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		//Debug::p($dataTable);	
		Run::$benchmark->writeMark("SelectData/buildSQLDataSequencial/schema[from]", "SelectData/buildSQLDataSequencial/schema[joins]");
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function fetchSQLPKList($type, $tableParams, $dataTable, $queryResult, $schema, $settings, $dataIntern){
		Run::$benchmark->mark("fetchSQLPKList/".$tableParams['table_nick']."/Inicio");
		//$queryResult->data_seek(0);
		$this->queryResult = $this->query->resultSeek($this->queryResult, 0);
		$table_ref = $this->findTableByParams($tableParams, $schema);
		while( $row = $this->query->returnFetchAssoc($this->queryResult) ){
			if((int)$row[$tableParams['pk']] > 0){
				$dataTable[$tableParams['table_nick']][$row[$tableParams['pk']]] = $row[$tableParams['pk']];
			}
		}
		Run::$benchmark->writeMark("fetchSQLPKList/".$tableParams['table_nick']."/Inicio", "fetchSQLPKList/".$tableParams['table_nick']."/fetch_assoc");
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function fetchSQLDataList($type, $tableParams, $dataTable, $queryResult, $schema, $settings, $dataIntern){
		Run::$benchmark->mark("fetchSQLDataSequencial/".$tableParams['table_nick']."/Inicio");
		//$queryResult->data_seek(0);
		$this->queryResult = $this->query->resultSeek($this->queryResult, 0);
		$table_ref = $this->findTableByParams($tableParams, $schema);
		//Debug::p($tableParams, $table_ref);
		$fieldsTable = array();
		foreach($schema['fields'] as $field => $params){
			if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
				$fieldsTable[$field] = $params;
			}
		}
		while( $row = $this->query->returnFetchAssoc($this->queryResult) ){
			if((int)$row[$tableParams['pk']] > 0){
				$pk = (int)$row[$this->model->schema['from'][0]['pk']];				
				foreach($fieldsTable as $field => $params){
					if(
						$params['type'] == "datetime" || $params['type'] == "date_insert" || $params['type'] == "date_update"
					){
						$row[$field] = Run::$control->date->convertMysqltoBr($row[$field]);
					}
					if($tableParams['pk'] == $schema['from'][0]['pk']) $dataTable[$pk][$field] = $row[$field];
					else if($tableParams['table_ref'] == $schema['from'][0]['table'] || $tableParams['table_ref'] == $schema['from'][0]['table_nick']){
						$dataTable[$pk][$field][$row[$tableParams['pk']]] = $row[$field];
					}
					else{
						//Debug::p($table_ref['pk'], $this->findFieldByNameSchema($table_ref['pk'], $table_ref['table_nick'], $schema));
						//$dataTable[$field][$row[ $this->findFieldByNameSchema($table_ref['pk'], $table_ref['table_nick'], $schema) ]][$row[$tableParams['pk']]] = $row[$field];
						$dataTable[$pk][$field][$row[ $table_ref['pk'] ]][$row[$tableParams['pk']]] = $row[$field];
					}
				}
				
			}
		}
		unset($fieldsTable);
		Run::$benchmark->writeMark("fetchSQLDataSequencial/".$tableParams['table_nick']."/Inicio", "fetchSQLDataSequencial/".$tableParams['table_nick']."/fetch_assoc");
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function fetchSQLDataSequencial($type, $tableParams, $dataTable, $queryResult, $schema, $settings, $dataIntern){
		Run::$benchmark->mark("fetchSQLDataSequencial/".$tableParams['table_nick']."/Inicio");
		//$queryResult->data_seek(0);
		$this->queryResult = $this->query->resultSeek($this->queryResult, 0);
		$table_ref = $this->findTableByParams($tableParams, $schema);
		//Debug::p($tableParams, $table_ref);
		$fieldsTable = array();
		foreach($schema['fields'] as $field => $params){
			if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
				$fieldsTable[$field] = $params;
			}
		}

		//Run::$benchmark->writeMark("fetchSQLDataSequencial/".$tableParams['table_nick']."/Inicio", "fetchSQLDataSequencial/".$tableParams['table_nick']."/fieldsFilter");
		while( $row = $this->query->returnFetchAssoc($this->queryResult) ){
			if((int)$row[$tableParams['pk']] > 0){
				foreach($fieldsTable as $field => $params){
					if(
						$params['type'] == "datetime" || $params['type'] == "date_insert" || $params['type'] == "date_update"
					){
						$row[$field] = Run::$control->date->convertMysqltoBr($row[$field]);
					}/*	*/
					if($tableParams['pk'] == $schema['from'][0]['pk']) $dataTable[$field] = $row[$field];
					else if($tableParams['table_ref'] == $schema['from'][0]['table'] || $tableParams['table_ref'] == $schema['from'][0]['table_nick']){
						$dataTable[$field][$row[$tableParams['pk']]] = $row[$field];
					}
					else{
						//Debug::p($table_ref['pk'], $this->findFieldByNameSchema($table_ref['pk'], $table_ref['table_nick'], $schema));
						//$dataTable[$field][$row[ $this->findFieldByNameSchema($table_ref['pk'], $table_ref['table_nick'], $schema) ]][$row[$tableParams['pk']]] = $row[$field];
						$dataTable[$field][$row[ $table_ref['pk'] ]][$row[$tableParams['pk']]] = $row[$field];
					}
				}
				
			}
		}
		unset($fieldsTable);
		Run::$benchmark->writeMark("fetchSQLDataSequencial/".$tableParams['table_nick']."/Inicio", "fetchSQLDataSequencial/".$tableParams['table_nick']."/fetch_assoc");
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function fetchSQLDataSequencialBKP($type, $tableParams, $dataTable, $queryResult, $schema, $settings, $dataIntern){
		$queryResult->data_seek(0);
		while($row = $queryResult->fetch_assoc()){
			if((int)$row[$tableParams['pk']] > 0){
				foreach($schema['fields'] as $field => $params){
					if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
						if(
							$params['type'] == "datetime" || $params['type'] == "date_time" || 
							$params['type'] == "date_insert" || $params['type'] == "date_update"
						){
							$row[$field] = Run::$control->date->convertMysqltoBr($row[$field], 'DATE_BR');
						}
						if($tableParams['pk'] == $schema['from'][0]['pk']) $dataTable[$field] = $row[$field];
						else if($tableParams['table_ref'] == $schema['from'][0]['table'] || $tableParams['table_ref'] == $schema['from'][0]['table_nick'] ){
							$dataTable[$field][$row[$tableParams['pk']]] = $row[$field];
						}
						else{
							$table_ref = $this->findTableByParams($tableParams, $schema);
							//Debug::p($tableParams['fk_ref']." / ".$this->findFieldByName($table_ref['pk'], $table_ref['table_nick'], $schema), $row[ $this->findFieldByName($table_ref['pk'], $table_ref['table_nick'], $schema) ]);
							$dataTable[$field][$row[ $this->findFieldByName($table_ref['pk'], $table_ref['table_nick'], $schema) ]][$row[$tableParams['pk']]] = $row[$field];
						}
					}
				}
			}
		}
		return $dataTable;
	}
	//*************************************************************************************************************************
	public function findTableByParams($tableParam, $schema){
		$found = array();
		foreach($schema['join'] as $table => $params){
			if($params['table'] === $tableParam['table_ref'] || $params['table_nick'] === $tableParam['table_ref']){
				$found = $params;
				break;
			}
		}
		//Debug::p("findTableByName",$found);
		return $found;
	}	
	//*************************************************************************************************************************
	public function findFieldByName($name, $tableParam, $fields){
		$found = "";
		foreach($fields as $field => $params){
			if($params['name'] === $name && $params['belongsTo'] == $tableParam['table_ref']){
				$found = $field;
				break;
			}
		}
		//Debug::p("findFieldByName $name $tableNick",$found);
		return $found;
	}
	//*************************************************************************************************************************
	public function findFieldByNameSchema($name, $tableParam, $schema){
		$found = "";
		foreach($schema['fields'] as $field => $params){
			if($params['name'] === $name && $params['belongsTo'] == $tableParam['table_ref']){
				$found = $field;
				break;
			}
		}
		//Debug::p("findFieldByName $name $tableNick",$found);
		return $found;
	}
	//*************************************************************************************************************************
	public function buildSQLData($type, $queryResult, $schema, $settings, $dataIntern, $fkTableID=0){	
		//Debug::p($orderTables);	
		$queryResult->data_seek(0);
		$dataTable = array();
		foreach($schema['from'] as $index => $paramTable){
			$dataTable = $this->fetchSQLData($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		foreach($schema['join'] as $index => $paramTable){
			$dataTable = $this->fetchSQLData($type, $paramTable, $dataTable, $queryResult, $schema, $settings, $dataIntern);
		}
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function fetchSQLData($type, $tableParams, $dataTable, $queryResult, $schema, $settings, $dataIntern){
		$queryResult->data_seek(0);
		$fieldsTable = array();
		foreach($schema['fields'] as $field => $params){
			if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
				$fieldsTable[$field] = $params;
			}
		}

		while($row = $this->fetchData){			if((int)$row[$tableParams['pk']] > 0){
				foreach($fieldsTable as $field => $params){
					if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
						if(
							$params['type'] == "datetime" || $params['type'] == "date_time" || 
							$params['type'] == "date_insert" || $params['type'] == "date_update"
						){
							$row[$field] = Run::$control->date->convertMysqltoBr($row[$field], 'DATE_BR');
						}
						$dataTable[$tableParams['table_nick']][$row[$tableParams['pk']]][$field] = $row[$field];	
					}
				}
			}
		}
		return $dataTable;
	}
	//*************************************************************************************************************************
	public function buildSQLDataRecursive($type, $orderTables, $queryResult, $schema, $settings, $dataIntern, $fkTableID=0){	
		//Debug::p($orderTables);	
		$queryResult->data_seek(0);
		$dataTable = array();
		$fieldsTable = array();
		foreach($schema['fields'] as $field => $params){
			if($params[$type] == true && ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table']) ){
				$fieldsTable[$field] = $params;
			}
		}
		if(count($orderTables) < 1) return;
		foreach($orderTables as $table => $paramTable){
			while($row = $this->fetchData){
				foreach($fieldsTable as $field => $params){
					if($params[$type] == true && ($params['belongsTo'] == $table || $params['belongsTo'] == $paramTable['table']) ){
						if($row[$paramTable['fk_ref']] == $fkTableID || $fkTableID === 0){
							if(
								$params['type'] == "datetime" || $params['type'] == "date_time" || 
								$params['type'] == "date_insert" || $params['type'] == "date_update"
							){
								$row[$field] = Run::$control->date->convertMysqltoBr($row[$field], 'DATE_BR');
							}
							$dataTable[$table][$row[$paramTable['pk']]][$field] = $row[$field];								
						}
					}
				}
			}
			$dataTable[$table]['joined'] = $this->buildSQLDataRecursive($type, $paramTable['joineds'], $queryResult, $schema, $settings, $dataIntern, $row[$paramTable['pk']]);
		}
		return $dataTable;
	}
	//*************************************************************************************************************************
	public function buildSQL($type, $dataIntern, $schema, $schema_unions){
		//Debug::p($schema);
		//if($schema_unions == false) return "teste"; 
		if(is_string($schema)) return $schema;
		$sql  = "";
		$sql .= "SELECT";
		$sql .= $this->buildSQLFields(		$type, $dataIntern, $this->model->schema);
		$sql .= "\nFROM";
		$sql .= $this->buildSQLFrom(		$type, $dataIntern, $this->model->schema);
		$sql .= $this->buildSQLJoins(		$type, $dataIntern, $this->model->schema);
		$sql .= $this->buildSQLJoinLimit(	$type, $dataIntern, $this->model->schema);

		$sql .= $this->buildSQLWhere(		$type, $dataIntern, $this->model->schema);
		$sql .= $this->buildSQLGroupBy(		$type, $dataIntern, $this->model->schema);
		$sql .= $this->buildSQLHaving(		$type, $dataIntern, $this->model->schema);
		$sql .= $this->buildSQLUnion(		$type, $dataIntern, $this->model->schema, $schema_unions);

		if($schema_unions === true) return $sql;

		if(! ($type == "list" && $this->getHasMultiple() && $this->model->settings['list_mode'] == "multiple" )){
			$sql .= $this->buildSQLOrder(	$type, $dataIntern, $this->model->schema);
			$sql .= $this->buildSQLLimit(	$type, $dataIntern, $this->model->schema);
		}

		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLFields($type, $dataIntern, $schema){
		$sql = "";
		$typeTable = ($type == "list") ? "list_fields" : $type;
		$tableParams = array();
		foreach($schema['fields'] as $field => $param){
			//Debug::p($param['name'] ." / ".$param['sqlSelect']);
			if(!isset($tableParams[$param['belongsTo']])) $tableParams[$param['belongsTo']] = $this->getTableParams($param['belongsTo'], $schema);
			//Debug::p($field."/".Run::$control->typeof($tableParams[$type]), $tableParams[$param['belongsTo']]);
			if( $param[$type] === true && $tableParams[$param['belongsTo']][$typeTable] !== false && isset($tableParams[$param['belongsTo']]) ){
				if(!$param['sqlSelect']) $sql .= ",\n\t". $this->findTableNick($param['belongsTo'], $schema) .".".$param['name'];
				else{
					$param['sqlSelect'] = Run::$control->string->replace("COLUMN", $this->findTableNick($param['belongsTo'], $schema).".".$param['name'], $param['sqlSelect']);
					$param['sqlSelect'] = Run::$control->string->replace("()", "(".$this->findTableNick($param['belongsTo'], $schema).".".$param['name'].")", $param['sqlSelect']);
					$sql .= ",\n\t". $param['sqlSelect'];
				}
				if($param['name'] != $field || $param['sqlSelect'] !== false) $sql .= " AS ".$field;
			}
		}
		$sql = substr($sql, 1, strlen($sql));
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLFrom($type, $dataIntern, $schema, $preStr=""){
		//Debug::p($type);
		$sql = "";
		foreach($schema['from'] as $k => $table){
			if( ($type == "view" && $table['view'] === true) || ($type == "inner" && $table['list_inner'] === true)  || ($type == "list" && $table['list_fields'] === true) ){
				//Debug::p($table['table']);
				$sql .= ",\n\t$preStr". $this->database->schema.$table['table'];
				if($table['table'] != $table['table_nick'] ) $sql .= " AS ".$table['table_nick'];
			}
		}
		$sql = substr($sql, 1, strlen($sql));
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLJoins($type, $dataIntern, $schema, $preStr=""){
		$sql = "";
		//Debug::p($schema['join']);
		foreach($schema['join'] as $k => $table){
			//Debug::p($param['name'] ." / ".$param['sqlSelect']);
			if( ($type == "view" && $table['view'] === true) || ($type == "inner" && $table['list_inner'] === true)  || ($type == "list" && $table['list_fields'] === true) ){
				$sql .= "\n\n$preStr ". Run::$control->string->upper($table['type']) ." JOIN";
				$table['table'] = $this->database->schema.$table['table'];
				$sql .= " ". $table['table'];
				if($table['table'] != $table['table_nick'] ) $sql .= " ".$table['table_nick'];
				$table_name = $table['table_nick'] != "" ? $table['table_nick'] : $table['table'] ;
				if(isset($table['table_ref']) || $table['table_ref'] != "") $sql .= "\n\t$preStr ON(( ".$table['table_ref'].".".$table['pk_ref']." = ".$table_name.".".$table['fk_ref']." ".$table['on']." ) AND (".$table_name.".". $table['status_name'] ." != '-1'))";
				else{ $sql .= "\n\t$preStr ON(( ".$table_name.".".$table['pk']." > 0 ".$table['on']." ) AND (".$table_name.".". $table['status_name'] ." != '-1'))"; }
			}
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLWhere($type, $dataIntern, $schema){
		$sql = "";
		if($type == "view"){
			$sql .= "\nWHERE ";
			$table_from = $schema['from'][0];
			$table_name = $table_from['table_nick'] != "" ? $table_from['table_nick'] : $table_from['table'] ;

			$schema['where'] = $this->addWhere($schema['where'], $table_name.".". $table_from['pk'] ." = ".$dataIntern['ref']);
			if($this->model->settings['select_use_status']) $schema['where'] = $this->addWhere($schema['where'], $table_name.".". $table_from['status_name'] ." != '-1'");
			$sql .= $schema['where'];
		}else{
			if(trim($schema['where']) != ""){
				if( !($type == "list" && $this->getHasMultiple() && $this->model->settings['list_mode'] == "multiple") ){
					$sql .= "\nWHERE ";
					$sql .= $schema['where'];
				}
			}
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLHaving($type, $dataIntern, $schema){
		$sql = "";
		if(trim($schema['having']) != ""){
			$sql .= "\nHAVING ";
			$sql .= $schema['having'];
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLOrder($type, $dataIntern, $schema, $preStr=""){
		$sql = "";
		if(strrpos($schema['order'], "order_tables") >=0){
			$order_tables = "";
			$mode = (strrpos($schema['order'], "order_tables_desc") != "") ? "DESC":"ASC";
			foreach($schema['from'] as $k => $table){
				if( ($type == "view" && $table['view'] === true) || ($type == "list" && $table['list_fields'] === true) ){
					$order_tables .= ", ".$table['pk']." ".$mode." ";
				}
			}
			foreach($schema['join'] as $k => $table){
				if( ($type == "view" && $table['view'] === true) || ($type == "list" && $table['list_fields'] === true) ){
					$order_tables .= ", ".$table['pk']." ".$mode." ";
				}
			}
			$order_tables = substr($order_tables, 1, strlen($order_tables));
			$schema['order'] = Run::$control->string->replace("order_tables_desc",  $order_tables, $schema['order']);
			$schema['order'] = Run::$control->string->replace("order_tables", 		$order_tables, $schema['order']);
		}
		if($schema['order'] !== "") $sql .= "\n\n$preStr ORDER BY ".$schema['order'];
		foreach($schema['from'] as $k => $table){
			if($table['order'] !== ""){
				if(trim($sql) == "")	$sql .= "\n\n$preStr ORDER BY ";
				else $sql .= ", ";
				$sql .= " ".$schema['order'];
			}
		}
		foreach($schema['join'] as $k => $table){
			if($table['order'] !== ""){
				if(trim($sql) == "")	$sql .= "\n\n$preStr ORDER BY ";
				else $sql .= ", ";
				$sql .= " ".$schema['order'];
			}
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLGroupBy($type, $dataIntern, $schema){
		$sql = "";
		if(trim($schema['group_view']) != "") $sql .= "\nGROUP BY ".$schema['group_view'];
		else if($type == "list" && trim($schema['group_list']) != ""){
			if( !($type == "list" && $this->getHasMultiple() && $this->model->settings['list_mode'] == "multiple") ){
				$sql .= "\nGROUP BY ".$schema['group_list'];
			}
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLJoinLimit($type, $dataIntern, $schema){
		$sql = "";
		$table_from = $schema['from'][0];
		$table_name = $table_from['table_nick'] != "" ? $table_from['table_nick'] : $table_from['table'] ;

		if($type == "list" && $this->getHasMultiple() && $this->model->settings['list_mode'] == "multiple"){
			$ws = ($this->model->settings['select_use_status']) ? $this->addWhere($schema['where'],  $table_name.".".$table_from['status_name'] ." != '-1'") : "";
			$sql = "\n\nINNER JOIN( SELECT DISTINCT(". $table_from['pk'] .") FROM ";				
			$sql .= $this->buildSQLFrom(		"inner", $dataIntern, $this->model->schema, " ");
			$sql .= $this->buildSQLJoins(		"inner", $dataIntern, $this->model->schema, "\t");
			$sql .=	"\n\t WHERE ". preg_replace('/\s/',' ', $ws);
			$sql .= $this->buildSQLOrder(	$type, $dataIntern, $this->model->schema, "\t");
			$sql .=	$this->buildSQLLimit($type, $dataIntern, $schema, "\t");
			$sql .= ")";
			$sql .= " AS ".$table_name."_l ";
			$sql .= "\n\t ON( ".$table_name."_l.".$table_from['pk']." = ".$table_name.".".$table_from['pk']." ) ";
		}else{
			$this->addOnWhere($table_name.".". $table_from['status_name'] ." != '-1'");
			Debug::p($this->model->schema['where']);
		}

		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLTotal($type, $dataIntern, $schema){
		$sql = "";
		$table_from = $schema['from'][0];
		$table_name = $table_from['table_nick'] != "" ? $table_from['table_nick'] : $table_from['table'] ;

		if($type == "list" && $this->getHasMultiple() && $this->model->settings['list_mode'] == "multiple"){
			$ws = ($this->model->settings['select_use_status']) ? $this->addWhere($schema['where'],  $table_name.".".$table_from['status_name'] ." != '-1'") : "";
			$sql = "\n\nSELECT count(DISTINCT(". $table_from['pk'] .")) as total FROM ";				
			$sql .= $this->buildSQLFrom(		"inner", $dataIntern, $this->model->schema, " ");
			$sql .= $this->buildSQLJoins(		"inner", $dataIntern, $this->model->schema, "\t");
			$sql .=	"\n\t WHERE ". preg_replace('/\s/',' ', $ws) ;
		}else{
			$this->addOnWhere($table_name.".". $table_from['status_name'] ." != '-1'");
			Debug::p($this->model->schema['where']);
		}

		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLLimit($type, $dataIntern, $schema, $preStr=""){
		$sql = "";
		if(count($schema['limit']) == 2) $sql .= "\n$preStr LIMIT ".$schema['limit'][0].", ".$schema['limit'][1];
		else if($type == "list") $sql .= "\n$preStr LIMIT ".$this->model->dataIntern[$this->model->settings['paging_ref'].'limit'][0].", ".$this->model->dataIntern[$this->model->settings['paging_ref'].'limit'][1];
		return $sql;
	}
	//*************************************************************************************************************************
	public function buildSQLUnion($type, $dataIntern, $schema, $schema_unions){
		$sql = "";
		if(!is_array($schema_unions)) return;
		//Debug::p($schema_unions['all']);
		foreach($schema_unions as $k => $schemaU){
			$typeUnion = (strrpos($k, "all") >= 0) ? "ALL": "";
			$sql .= "\n\nUNION $typeUnion (\n";
			$sql .= $this->buildSQL($type, $dataIntern, $schemaU, true);
			$sql .= "\n) -- UNION END";
		}
		return $sql;
	}
	//*************************************************************************************************************************
	public function getTableParams($table_name, $schema){
		foreach($schema['from'] as $k => $table){
			if($table['table'] == $table_name || $table['table_nick'] == $table_name){
				return $table;
			}
		}
		foreach($schema['join'] as $k => $table){
			if($table['table'] == $table_name || $table['table_nick'] == $table_name){
				//Debug::p("{$table['table']} == $table_name", $table);
				return $table;
			}
		}
	}
	//*************************************************************************************************************************
	public function getHasMultiple(){
		foreach($this->model->schema['join'] as $k => $table){
			if($table['multiple'] === true){
				return true;
			}
		}
	}
	//*************************************************************************************************************************
	public function findTableNick($table_name, $schema){
		$nick = $table_name;
		foreach($schema['from'] as $k => $table){
			if($table['table'] == $table_name && $table['table_nick'] != ""){
				$nick = $table['table_nick'];
				break;
			}
		}
		if($nick == $table_name){
				//Debug::p("$table_name");
			foreach($schema['join'] as $k => $table){
				//Debug::p("{$table['table']} == $table_name");
				if($table['table'] == $table_name && $table['table_nick'] != ""){
					$nick = $table['table_nick'];
					break;
				}
			}
		}
		return $nick;
	}
	//*************************************************************************************************************************
	public function addOnWhere($sql){
		if(trim($this->model->schema['where']) == "") $this->model->schema['where'] = "\r\n\t( ".$sql." )";
		else $this->model->schema['where'] .= "\r\n\t AND ( ".$sql." )";
		return $this->model->schema['where'];
	}
	//*************************************************************************************************************************
	public function addOnHaving($sql){
		if(trim($this->model->schema['having']) == "") $this->model->schema['having'] = "\r\n\t( ".$sql." )";
		else $this->model->schema['having'] .= "\r\n\t AND ( ".$sql." )";
		return $this->model->schema['having'];
	}
	//*************************************************************************************************************************
	public function addWhere($where, $sql){
		if(trim($where) == "") $where = "\r\n\t( ".$sql." )";
		else $where .= "\r\n\t AND ( ".$sql." )";
		return $where;
	}
	//*************************************************************************************************************************
	public function addHaving($where, $sql){
		return $this->addWhere($where, $sql);
	}
	//*************************************************************************************************************************


}
// ############################################################################################################################

?>