<?php
// ****************************************************************************************************************************
class ListAux{
	public $dataFormSequencial	= array();
	public $session				= NULL;
	public $model				= NULL;
	public $tableStyle			= "";
	public $tableProperties		= 'cellpadding="0" cellspacing="0" border="1"';
	public $tableClass			= "";
	public $listOrderFields		= array();

	//*************************************************************************************************************************
	public function ListAux($model){
		$this->model = $model;
	}
	//*************************************************************************************************************************
	public function setStyle($style = ""){
		$this->tableStyle = $style;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setClass($class = ""){
		$this->tableClass = $class;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setProperties($prop = ""){
		$this->tableProperties = $prop;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoTable(){
		echo $this->getTable();
		//Run::$DEBUG_PRINT = true;
		//Debug::p($this->listOrderFields);
		//Debug::p($this->model->schema['fields']);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTable(){
		$this->getListOrder();
		$html = "";
		$html .= "<table ". $this->getTableID() . $this->getTableProperties() . $this->getTableStyle() ." >";
		$html .= $this->getTableHead();
		$html .= $this->getTableBody();
		$html .= "</table>";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getListOrder(){
		foreach($this->model->schema['fields'] as $field => $param){
			if($param['list'] !== false) $this->listOrderFields[$param['listOrder']."_".$param['belongsTo']] = $field;
		}
		ksort($this->listOrderFields);	
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableHead(){
		$html = "";		
		$html .= "<thead>";
		$html .= "<tr>";

		foreach($this->listOrderFields as $order => $field){
			$fieldP = $this->model->schema['fields'][$field];
			if($fieldP['listAsColumn'] !== true) continue; 
			$class 	= ($fieldP['listInClass'] !== false) ? " class=\""."o".$order." ".$field." ".$fieldP['listInClass']."\" " :  "class=\""."o".$order." ".$field."\" ";
			$html .= "<th ". $class .">";
			$html .= $fieldP['label'];
			$html .= "</th>";
		}

		$html .= "</tr>";
		$html .= "</head>";

		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableBody(){
		$html = "";		
		$html .= "<tbody>";

		foreach($this->model->dataList as $pk => $field){

		}

		$html .= "</tbody>";

		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableID(){
		return  " id=\"list_". $this->model->settings['form_id']."\"";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableProperties(){
		$html = "";
		if($this->tableProperties != "") $html = " ". $this->tableProperties." ";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableClass(){
		$html = "";
		if($this->tableClass != "") $html = " class=\"". $this->tableClass."\" ";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getTableStyle(){
		$html = "";
		if($this->tableStyle != "") $html = " class=\"". $this->tableStyle."\" ";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
}
// ############################################################################################################################
?>