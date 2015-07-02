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
	public $orderedTables		= array();

	//*************************************************************************************************************************
	public function ListAux($model){
		$this->model = $model;
		Run::$DEBUG_PRINT = true;
		$this->orderedTables = $this->model->orderData->getOrderedListTables();
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
		Run::$DEBUG_PRINT = true;
		echo $this->getTable();
		//Debug::p($this->model->dataList);
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
			$style 	= ($fieldP['listWidth']   !== false) ? " style=\""."width:".$fieldP['listWidth']."px;\" " : "";
			$class 	= ($fieldP['listInClass'] !== false) ? " class=\""."o".$order." ".$field." ".$fieldP['listInClass']."\" " :  "class=\""."o".$order." ".$field."\" ";
			$html .= "<th ". $class . $style .">";
			$html .= $fieldP['listLabel'];
			$html .= "</th>";
		}
		if($this->model->settings['list_edit_show'] === true){
			$html .= "<th ". $class . $style .">";
			$html .= $this->model->settings['list_edit_label'];
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

		foreach($this->model->dataList as $pk => $register){
			$html .= "<tr ". $class .">";
			foreach($this->listOrderFields as $order => $field){
				$fieldP = $this->model->schema['fields'][$field];
				if($fieldP['listAsColumn'] !== true) continue;
				$class 	= ($fieldP['listInClass'] !== false) ? " class=\"".""."\" " :  "";

				if(is_array($register[$field])){
					foreach($register[$field] as $k => $value){
						if($value != "") $register[$field][$k] = (!isset($fieldP['labelList'][$value])) ? $value : $fieldP['labelList'][$value];
					}
					$value = implode($fieldP['listImplode'], $register[$field]);
					//Debug::p($value);
				}
				else if($register[$field] != "") $value = (!isset($fieldP['labelList'][$register[$field]])) ? $register[$field] : $fieldP['labelList'][$register[$field]];
				else $value = $register[$field];

				if(isset($fieldP['idPad'])) $value = str_pad($value, $fieldP['idPad'], "0", STR_PAD_LEFT);

				$html .= "<td class=\"col_". $field ."\">";
				$html .= $value;
				$html .= "</td>";
			}
			if($this->model->settings['list_edit_show'] === true){
				$html .= "<td class=\"edit_column\">";
				$html .= "<div class=\"infos_extras\" style='display:none; background:#ccc; width: 600px; position:absolute; right:10%; bottom:0px; border:1px solid red;'>";
				$html .= $this->model->exeDetailsList($this->orderedTables, $register);
				$html .= "</div>";
				$html .= "</td>";
			}
			$html .= "</tr>";
		}

		$html .= "</tbody>";

		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getDetailData($register, $field){
		$fieldP = $this->model->schema['fields'][$field];
		if($fieldP['listAsColumn'] === true) return NULL;
		if($fieldP['iskey'] === true) return NULL;

		if(is_array($register[$field])){
			foreach($register[$field] as $k => $value){
				if($value != "") $register[$field][$k] = (!isset($fieldP['labelList'][$value])) ? $value : $fieldP['labelList'][$value];
			}
			$value = implode($fieldP['listImplode'], $register[$field]);
		}
		else if($register[$field] != "") $value = (!isset($fieldP['labelList'][$register[$field]])) ? $register[$field] : $fieldP['labelList'][$register[$field]];
		else $value = $register[$field];

		if(isset($fieldP['idPad'])) $value = str_pad($value, $fieldP['idPad'], "0", STR_PAD_LEFT);
		return array("value"=>$value);
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