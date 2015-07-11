<?php
// ****************************************************************************************************************************
class Validate{

	public $dataRequest 	= array();
	public $dataForm 		= array();
	public $errors			= array();
	public $messages		= array();
	public $model			= array();
	public $fields_removed	= array();
	//*************************************************************************************************************************
	function Validate($model){
		Debug::log("Iniciando Core/Form/Validate.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->model = $model;
	}
	//*************************************************************************************************************************
	public function validator($schema, $data, $dataOriginal){
		Debug::log("Validate->validator:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//Debug::p("validator",$schema['fields']);
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	
		foreach($schema['fields'] as $field => $settings){
			$count_erros = 0;
			if(Run::$control->typeof($settings['validation']) == "array"){

			if(isset($settings['validation']['depends'])){
				// call_user_func($settings['validation']['depends']);
				if($settings['validation']['depends'] === true){
					$settings['validation']['required'][0] = true;
					$settings['validation']['required'][1] = true;
				}else{
					$settings['validation']['required'][0] = false;
					$settings['validation']['required'][1] = false;
				}
			}

			if($settings['validation']['useSentValue'] === true){
				$data[$field] = $dataOriginal[$field];
			}

			foreach($settings['validation'] as $type => $value){
				if($type == "depends") continue;				
				$erro_status = false;
				if(!isset($data[$field])) 	$data[$field] 	= false;
				if(!isset($value[2])) 		$value[2] 		= false;
				if(!isset($settings['validation']['required'])){ $settings['validation']['required'][0] = false;$settings['validation']['required'][1] = false;}
				$msgErro = $this->getMsg($type, $value[2], $value[0], $data[$field]);
				$required = (($settings['validation'][$type][0] !== false || $settings['validation'][$type][0] != "") && ($settings['validation']['required'][0] !== false || $settings['validation']['required'][0] != "")) ? true:false;
 				
 				if($schema['fields'][$field]['skipRecEmpty'] === true || $schema['fields'][$field]['skipFieldEmpty'] === true ){
 					if($data[$field] == false || $data[$field] == "") continue;
 				}
 				
 				//echo "<br /> <br /> $field / $type / required = $required --------------------------------------------------------- ";	
				
				if($settings['multiple'] === true && Run::$control->typeof($data[$field]) != "multiple"){ } // $required = false;  
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "required"		&& $required){	if(!$this->_require($data[$field], $settings['validation']['required'][0]))	$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "email"			&& $required){	if(!$this->_email($data[$field])) 						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "url"			&& $required){	if(!$this->_url($data[$field])) 						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "placeholder"	&& $required){	if(!$this->_placeholder($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "phone"			&& $required){	if(!$this->_phone($data[$field])) 						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "date"			&& $required){	if(!$this->_date($data[$field])) 						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "dateISO"		&& $required){	if(!$this->_dateISO($data[$field])) 					$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "dateBR"		&& $required){	if(!$this->_dateBR($data[$field])) 						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "number"		&& $required){	if(!$this->_number($data[$field]))						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				elseif($type == "real" 			&& $required){	if(!$this->_real($data[$field]))						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "equalTo"			&& $required){	$value[0] = preg_replace("/^(#|\.)/", "", $value[0]);
							if(!$this->_equalTo($data[$field], $data[$value[0]])) 										$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "cpf"				&& $required){	if(!$this->_cpf($data[$field]))							$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "cnpj"				&& $required){	if(!$this->_cnpj($data[$field]))						$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "php"				&& $required){	$msgErro1 = $this->_php($value[0][0], $data[$field], $value[0][1]);
							if($msgErro1 != false)	$erro_status = true;	$msgErro  = (strlen($value[2]) >= 1) ? $value[2] : $msgErro1 ;		}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "maxwords"			&& $required){	if(!$this->_maxwords($data[$field], $value[0]))			$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "minwords"			&& $required){	if(!$this->_minwords($data[$field], $value[0]))			$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangewords"		&& $required){	if(!$this->_rangewords($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangedate"			&& $required){		if(!$this->_rangedate($data[$field], $value[0]))	$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangenumbers"		&& $required){	if(!$this->_rangenumbers($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangeletters"		&& $required){	if(!$this->_rangeletters($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangevalues" 		&& $required){	if(!$this->_rangevalues($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangecaracters" 	&& $required){ if(!$this->_rangecaracters($data[$field], $value[0]))	$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "maxcaracters"		&& $required){	if(!$this->_maxcaracters($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "mincaracters"		&& $required){	if(!$this->_mincaracters($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "rangelength"		&& ($required || $value[0][0] >= 1)){	if(!$this->_rangelength($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "maxlength"			&& $required){	if(!$this->_maxlength($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "minlength"			&& ($required || $value[0] >= 1)){	if(!$this->_minlength($data[$field], $value[0]))			$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "letterspunc"		&& $required){	if(!$this->_letterspunc($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "alphanumeric"		&& $required){	if(!$this->_alphanumeric($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "lettersonly"		&& $required){	if(!$this->_lettersonly($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "nowhitespace"		&& $required){	if(!$this->_nowhitespace($data[$field], $value[0]))		$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "integer"			&& $required){	if(!$this->_integer($data[$field], $value[0]))			$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "accept"){			if(!$this->_accept($data[$field], $value[0]))							$erro_status = true;	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "maxfilesize"){		$this->_maxfileSize($settings, $settings['fieldRef'], $data[$field], $value[0], $count_erros, $msgErro);	}
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($type == "minfilesize"){		$this->_minfileSize($settings, $settings['fieldRef'], $data[$field], $value[0], $count_erros, $msgErro);	}
				//Error::writeLog("TESTE: ".$result, __FILE__, __LINE__);
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
				if($erro_status == true){ 
					if($count_erros == 0) $this->errors[$field]['label'] = $settings['label'];
					$count_erros++; $this->errors[$field][$count_erros] = $msgErro;
				}
			}
			}
		}
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		return $this->errors;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function jsArray($value){
					$str 	= "";
					$str 	.= "";
					$n3 	= 0;
					$str 	.= "[";
					foreach($value as $k => $v){ 
						$n3++; if($n3 < count($value)) $virgula3 = ","; else $virgula3 = "";
						if(is_string($v)) $v = "\"". $v ."\"";
						if(is_array($v)) $v = $this->jsArray($v);
						$str .= "$v$virgula3";
					};
					$str 	.= "]";
					return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function validateJS(){
		return $this->validatorJS();
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function validatorJS(){

		$settings = $this->model->settings;

		$settings['CLIENT_PLACEHOLDERS'] = "";
		$settings['CLIENT_MASKS'] = "";

		Debug::p($settings);

		Debug::log("Validate->validatorJS:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!isset($settings['val_client'])){
			$settings['val_client'] = false;
		}
		if(!$settings['val_client'] == true) return false;
		$script = "";

		if(isset(Config::$PATH_RUN)){
		//	$script .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/validate.js\"></script>\n";
		}else{
		//	$script .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/validate.js\"></script>\n";
		}
		$script .= "\n\t<script>"; //if(typeof(fields) == 'string')
		$script .= "\n\tjQuery(document).ready(function($){";
		$script .= "\n\t\t _val_". $settings['form_id'] ." = $(\"#". $settings['form_id'] ."\").submit(function(){";
		$script .= "\n\t\t\t if(typeof window.exec_". $settings['form_id'] ." == 'function') exec_". $settings['form_id'] ."();";
		$script .= "\n\t\t\t return $('#". $settings['form_id'] ."').validate();";
		$script .= "\n\t\t}).validate({";
		$script .= "\n\t\t ". $settings['CLIENT_EXTRAS'];
		$script .= "\n\t\trules:{";
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	
		$n = 0;
		foreach($this->model->schema['fields'] as $field => $settings){
			if($settings["multiple"] === true){
				if($settings["serial"] == true) $field .= "_0";
				else $field .= "[]";
			}
			$n++;
			if($n < count($this->model->schema['fields'])) $virgula = ",";
			else $virgula = "";
			$count_erros = 0;
			if(count($settings['validation']) > 0 && $settings['validation'] != false){
				$script .= "\n\t\t\t\"".$field."\":{";
				$n2 = 0;
				foreach($settings['validation'] as $type => $value){
					if($type == "depends") continue;
					if($type == 'placeholder') $settings['CLIENT_PLACEHOLDERS'] .= "\n\tplaceholder[\"$field\"] = \"{$value[0]}\";";
					if($type == 'mask'){
						$settings['CLIENT_MASKS'] 		  .= "\n\tmasks[\"$field\"] = \"{$value[0][0]}\";";
						$settings['CLIENT_MASKS'] 		  .= "\n\tmasksPh[\"$field\"] = \"{$value[0][1]}\";";
					}

					if(!isset($value[1])) $value[1] = false;
					if($value[1] !== true && $value[1] !== false ) $value[0] = $value[1];
					if($value[1] !== false){
						if($n2 > 0) $virgula2 = ","; else $virgula2 = "";
						$n2++;
						if($value[1] === true && ($value[0] === true || $value[0] === false)) $value[0] = true;
						if($value[0] === "all") $value[0] = true;
						if(is_string($value[0])) $value[0] = "\"". $value[0] ."\"";
						if(is_array($value[0])){
							$value[0] = $this->jsArray($value[0]);
						};
						if($value[0] === true) $value[0] = "true";
						if($value[0] === false) break; //$value[0] = "false";
						$value = (string)$value[0];
						if(isset($settings['validation']['depends']) && $type == "required"){
							 $script .= "$virgula2\n\t\t\t\t$type	: { depends : function(element){ return depends_$field(element); } } ";
						}
						else{
							$script .= "$virgula2\n\t\t\t\t$type	: $value ";
						}
					}
				}
				$script .= "\n\t\t\t}$virgula";
			}
		}
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -			
		$script .= "\n\t\t},";		
		$script .= "\n\t\tmessages:{";		
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	
		$n = 0;
		foreach($this->model->schema['fields'] as $field => $set){
			if($set["multiple"] === true){
				if($set["serial"] == true) $field .= "_0";
				else $field .= "[]";
			}
			$n++;
			if($n < count($this->model->schema['fields'])) $virgula = ",";
			else $virgula = "";
			$count_erros = 0;
			if(count($set['validation']) > 0 && $set['validation'] != false){
				$script .= "\n\t\t\t\"".$field."\":{";
				$n2 = 0;
				
				foreach($set['validation'] as $type => $value){
					if($type == "depends") continue;
					if($type != "php"){
					if(!isset($value[2])) $value[2] = false;
					if($value[1] !== true && $value[1] !== false ) $value[0] = $value[1];
					if($value[1] !== false){
						$value = "\"". Run::$view->render->html($this->getMsg($type, $value[2], $value[0], isset($data[$field]))) ."\"";
						if(strlen($value) >= 3) if($n2 > 0) $virgula2 = ","; else $virgula2 = "";
						if(strlen($value) >= 3) $n2++;
						if(strlen($value) >= 3) $script .= "$virgula2\n\t\t\t\t$type	: $value ";	
					}
					}
				}
				$script .= "\n\t\t\t}$virgula";
			}
		}
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -					
		$script .= "\n\t\t";
		$script .= "\n\t\t}";
		$script .= "\n\t\t});";
		$script .= "\n\t});";
		$script .= $settings['CLIENT_PLACEHOLDERS'];
		$script .= $settings['CLIENT_MASKS'];
		$script .= "\n\t</script>";
		$script .= "\n";
		return $script;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	public function getMsg($type, $msg, $param, $value){
			//echo "<br /> $type, $msg, $param, $value";
			//Debug::print_r($param);
			$msgErro = (strlen($msg) >= 1) ? $msg : Language::get("validate_".$type);
			if($type == "php" && (strlen($msg) >= 1)) $msgErro = "";
			if(!is_array($param)) $param = array($param);
			if(!isset($param[0])) $param[0] = 1;
			if(!isset($param[1])) $param[1] = 1;
			if(!isset($param[2])) $param[2] = 1;
			if(!isset($param[3])) $param[3] = 1;
			if(!isset($param[4])) $param[4] = 1;
			if(!isset($param[5])) $param[5] = 1;
			if(is_array($param)){
				$msgErro = str_replace("{0}", ((string)$param[0]), $msgErro);
				$msgErro = str_replace("{1}", ((string)$param[1]), $msgErro);
				$msgErro = str_replace("{2}", ((string)$param[2]), $msgErro);
				$msgErro = str_replace("{3}", ((string)$param[3]), $msgErro);
				$msgErro = str_replace("{4}", ((string)$param[4]), $msgErro);
				$msgErro = str_replace("{5}", ((string)$param[5]), $msgErro);
				$msgErro = str_replace("{v}", (string)$value, 	 $msgErro);
				$msgErro = str_replace("{value}", (string)$value,$msgErro);
			} else $msgErro = str_replace("{0}", $param, $msgErro);
			return $msgErro;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function validationShowErrors($nField, $nType){
		if(count($this->errors) === 0) return false;
		echo "<div class='errorValidation'><h6>Foram encontrados erros nos dados enviados:</h6>";
		echo "\n\t<ul>";
		$nF = 0;
		foreach($this->errors as $field => $erros){
			if($nF+1 > $nField) break;
			echo "\n\t<li>";
			if($erros['label'] != "") echo "\n\t\t <b> Campo ". View::html($erros['label']) ."</b>"; else echo "\n\t\t  <b> Campo ". View::html($field) ."</b>";
			echo "\n\t\t <span>";
			$nT = 0;
			foreach($erros as $i => $msg){
				//echo "<BR> validationShowErrors $i $nT  $nType: ".($msg)." / ".View::html($msg);
				if($nT > $nType) break;
				if($i != "label"){
					if(strlen($msg) > 3) echo "<span> ". View::html($msg) ."</span>";
					else{  echo "<span> O Campo não está preenchido corretamente. </span>"; }
				}
				$nT++;
			}
			echo "</span>";
			echo "\n\t</li>";
			$nF++;
		}
		echo "</ul><div class='clear'></div>";
		//print_r($this->errors);
		echo "</div>";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	// validações - réplicas de validation.js
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	public function _require($value, $param){
		if(is_array($value) &&  $param === "all"){ 
			//echo "<br /> > ".$param ;
			foreach($value as $k => $v){ if(!$this->_require($v, true)) return false;	}
		}
		else{
			$num  = -1;
			if(is_string($value)) $num = strlen($value);
			if(is_array($value)) $num = count($value);
			if($num <= 0 && $param === true) return false;
			else return true;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _email($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_email($v)) return false;	}
		}
		else{
			$reg = "/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _url($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_url($v)) return false;	}
		}
		else{
			$reg = "/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[00A0-D7FFF900-FDCFFDF0-FFEF])|(([a-z]|\d|[00A0-D7FFF900-FDCFFDF0-FFEF])([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])*([a-z]|\d|[00A0-D7FFF900-FDCFFDF0-FFEF])))\.)+(([a-z]|[00A0-D7FFF900-FDCFFDF0-FFEF])|(([a-z]|[00A0-D7FFF900-FDCFFDF0-FFEF])([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])*([a-z]|[00A0-D7FFF900-FDCFFDF0-FFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[E000-F8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[00A0-D7FFF900-FDCFFDF0-FFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _date($value){
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _dateISO($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_dateISO($v)) return false;	}
		}
		else{
			$reg = "/^[0-9]{4}[\/-][0-9]{1,2}[\/-][0-9]{1,2}$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _dateBR($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_dateBR($v)) return false;	}
		}
		else{
			$reg = "/^[0-9]{1,2}[\/][0-9]{1,2}[\/][0-9]{4}$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _number($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_number($v)) return false;	}
		}
		else{
			$reg = "/^[-]?[0-9]{0,100}[,|.]?[0-9]{1,100}$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _digits($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_digits($v)) return false;	}
		}
		else{
			$reg = "/^[0-9]+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _real($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_real($v)) return false;	}
		}
		else{
			$value = str_replace("R$", "", $value);
			$reg = "/^([0-9]{1,3})(([.][0-9]{1,3}){1,6})?[,][0-9]{2}$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _equalTo($value, $value2){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_equalTo($v, $value2)) return false;	}
		}
		else{
			if($value == $value2) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _php($func, $value, $extras){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_php($func, $v, $extras)) return false;	}
		}
		else{
			//require_once(RUN_PATH.'libraries/ajax.php');
			//$ajax= new Ajax;
			
			//Debug::log("KADE???  para a URL. (pags/control/".self::$levels[0]."_control.php) - $class");
			//$this->validaURL();
			$str = Run::$ajax->execute($this, $func, $value, $extras);
			if(strlen($str) == 0) return false;
			else return "\"".$str."\"";
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _maxwords($value, $max){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_maxwords($v, $params)) return false;	}
		}
		else{
			$reg = "/\b\w+\b/";
			$num = preg_match_all($reg, strip_tags($value), $array_);
			if($num <= $max) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _minwords($value, $min){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_minwords($v, $min)) return false;	}
		}
		else{
			$reg = "/\b\w+\b/";
			$num = preg_match_all($reg, strip_tags($value), $array_);
			if($num >= $min) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangedate($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_rangedate($v, $params)) return false;	}
		}
		else{
			$_dt 		= Run::$control->date->fullConversion($value);
			$_dt_ini	= Run::$control->date->fullConversion($params[0]); 
			$_dt_fim	= Run::$control->date->fullConversion($params[1]); 
			if($_dt['MKTIME'] >= $_dt_ini['MKTIME'] && $_dt['MKTIME'] <= $_dt_fim['MKTIME']) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangewords($value, $params){
		$reg = "/\b\w+\b/";
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_rangewords($v, $params)) return false;	}
		}
		else{
			$num = preg_match_all($reg, strip_tags($value), $array_);
			if($num >= $params[0] && $num <= $params[1]) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _maxlength($value, $max){
		$num = 0;
		if(is_string($value)) $num = strlen($value);
		if(is_array($value)) foreach($value as $k => $v){ if(strlen($v) >= 1) $num++; }
		if($num <= $max) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _minlength($value, $min){
		$num = 0;
		if(is_string($value)) $num = strlen($value);
		if(is_array($value)) foreach($value as $k => $v){ if(strlen($v) >= 1) $num++; }
		if($num >= $min) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangelength($value, $params){
		$num = 0;
		if(is_string($value)) $num = strlen($value);
		if(is_array($value)) foreach($value as $k => $v){ if(strlen($v) >= 1) $num++; }
		if($num >= $params[0] && $num <= $params[1]) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangenumbers($value, $params){
		$reg = "/[0-9]/";
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_rangenumbers($v, $params)) return false;	}
		}
		else{
			$num = preg_match_all($reg, strip_tags($value), $array_);
			if($num >= $params[0] && $num <= $params[1]) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangevalues($value, $params){
		//$value = str_replace('.','',$value);
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_rangevalues($v, $params)) return false;	}
		}
		else{
			$value = floatval(str_replace(',', '.', str_replace('.', '', $value)));
			if($value >= $params[0] && $value <= $params[1]) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangeletters($value, $params){
		$reg = "/[a-z]/";
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_rangeletters($v, $params)) return false;	}
		}
		else{
			$num = preg_match_all($reg, strip_tags($value), $array_);
			if($num >= $params[0] && $num <= $params[1]) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _rangecaracters($value, $params){
		if(is_array($value)){
			//echo "<br /> é array ";
			foreach($value as $k => $v){ if(!$this->_rangecaracters($v, $params)) return false;	}
		}
		else{
			$num = strlen($value);
			//echo "<br /> NUM $num  / ".$params[1];
			if($num >= $params[0] && $num <= $params[1]) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _maxcaracters($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_maxcaracteres($v, $params)) return false;	}
		}
		else{
			$num = strlen($value);
			if($num <= $params) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _mincaracters($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_mincaracteres($v, $params)) return false;	}
		}
		else{
			$num = strlen($value);
			if($num >= $params) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _letterspunc($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_letterspunc($v, $params)) return false;	}
		}
		else{
			$reg = "/^[a-z-.,()'\"\s]+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _alphanumeric($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_alphanumeric($v, $params)) return false;	}
		}
		else{
			$reg = "/^\w+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _lettersonly($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_lettersonly($v, $params)) return false;	}
		}
		else{
			$reg = "/^[a-z]+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _nowhitespace($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_nowhitespace($v, $params)) return false;	}
		}
		else{
			$reg = "/^\S+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _integer($value, $params){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_integer($v, $params)) return false;	}
		}
		else{
			$reg = "/^-?[0-9]+$/";
			if(preg_match($reg, $value)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function _cpf($value) {
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_cpf($v)) return false;	}
		}
		else{
			$value = $this->string->clearPoints($value);
			
			if (strlen($value) != 11 || $value == "00000000000" || $value == "11111111111" ||	$value == "22222222222" ||
			$value == "33333333333" || $value == "44444444444" || $value == "55555555555" || $value == "66666666666" ||
			$value == "77777777777" || $value == "88888888888" || $value == "99999999999"){
				return false;
			}
			
			$soma = 0;
			for ($i=0; $i<9; $i++) $soma += substr($value,$i,1) * (10 - $i);
			$resto = 11 - ($soma % 11);
			if ($resto == 10 || $resto == 11) $resto = 0;
			if ($resto != substr($value,9,1)) return false;
			
			$soma = 0;
			for ($i=0; $i<10; $i++) $soma += substr($value,$i,1) * (11 - $i);
			$resto = 11 - ($soma % 11);
			if ($resto == 10 || $resto == 11) $resto = 0;
			if ($resto != substr($value,10,1)) return false;
			
			return true;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function _cnpj($value) {
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_cnpj($v)) return false;	}
		}
		else{
			$value = $this->string->clearPoints($value);
			
			if(strlen($value) < 14) return false;
	
			$a = str_split($value);
			$b = int;
			$c = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
			
			for ($i=0; $i<12; $i++){ $b += $a[$i] * $c[$i+1]; }
			if (($x = $b%11) < 2) { $a[12] = 0; } else { $a[12] = 11-$x; }
			
			$b = 0;
			for ($i=0; $i<13; $i++){ $b += ($a[$i] * $c[$i]); }
			if (($x = $b%11) < 2) { $a[13] = 0; } else { $a[13] = 11-$x; }
			
			if ((substr($value,12,1) != $a[12]) || (substr($value,13,1) != $a[13])){
				return false;
			}
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _creditcard($value){
		/*
			if ( this.optional(element) )
				return "dependency-mismatch";
			// accept only digits and dashes
			if (/[^0-9-]+/.test(value))
				return false;
			var nCheck = 0,
				nDigit = 0,
				bEven = false;

			value = value.replace(/\D/g, "");

			for (var n = value.length - 1; n >= 0; n--) {
				var cDigit = value.charAt(n);
				var nDigit = parseInt(cDigit, 10);
				if (bEven) {
					if ((nDigit *= 2) > 9)
						nDigit -= 9;
				}
				nCheck += nDigit;
				bEven = !bEven;
			}

			return (nCheck % 10) == 0;
		*/
		return false;
	}
		
	//-------------------------------------------------------------------------------------------------------------------------
	public function _phone($value){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_phone($v)) return false;	}
		}
		else{
			$value = ereg_replace("([() '-.,:\/])", "", $value);
			$reg0 = "000000";
			$reg1 = "111111";
			$reg2 = "222222";
			$reg3 = "333333";
			$reg4 = "444444";
			$reg5 = "555555";
			$reg6 = "666666";
			$reg7 = "777777";
			$reg8 = "888888";
			$reg9 = "999999";
			if(preg_match($reg0, $value)) return false;
			else if(preg_match($reg1, $value)) return false;
			else if(preg_match($reg2, $value)) return false;
			else if(preg_match($reg3, $value)) return false;
			else if(preg_match($reg4, $value)) return false;
			else if(preg_match($reg5, $value)) return false;
			else if(preg_match($reg6, $value)) return false;
			else if(preg_match($reg7, $value)) return false;
			else if(preg_match($reg8, $value)) return false;
			else if(preg_match($reg9, $value)) return false;
			else return true;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _placeholder($value, $placeHolder){
		if(is_array($value)){ 
			foreach($value as $k => $v){ if(!$this->_placeholder($v, $placeHolder)) return false;	}
		}
		else{
			if($placeHolder != $value) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _accept($value, $params){
		if(is_array($value["size"])){ 
			foreach($value as $k => $v){ if(!$this->_accept($v, $params)) return false;	}
		}
		else{
			$file = File::getInstance();
			if($value["tmp_name"]=="")return true;//retorna se nao foi enviado arquivo
			
			if($file->verifyFileExtension($value,$params)) return true;
			else return false;
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _maxfileSize($settings, $field, $value, $params, $count_erros, $msgErro, $ind1=-1, $ind2=-1){
		//Run::$DEBUG_PRINT = 1;
		if(is_array($value)){ 
			//	Debug::p($value);
			//	Debug::p($_FILES);
			//	Run::$DEBUG_PRINT = 0;
			//exit;
			foreach($value as $k => $v){
				if(is_array($v)){
					foreach($v as $k2 => $v2){
						$this->_maxfileSize($settings, $field, $v2, $params, $count_erros++, $msgErro, $k, $k2);
					}
				}else{
					if(!$this->_maxfileSize($settings, $field, $v, $params, $count_erros++, $msgErro, $k)){
						//return false;
					}
				}
			}
		}
		else{
			if(isset($value["tmp_name"]) && $value["tmp_name"] == "") return true;//retorna se nao foi enviado arquivo
			else if(!isset($value["tmp_name"]) && $value=="") return true;
			
			if(is_array($params) && count($params) == 2){
				Error::writeLog("nome>: ".Run::$control->file->getBytesByUnit($value, "B")." >> ".Run::$control->file->getBytesByUnit((int)$params[0], $params[1])	, __FILE__, __LINE__);
				if(is_array($value) && Run::$control->file->getBytesByUnit((int)$value["size"], "B") <= Run::$control->file->getBytesByUnit($params[0], $params[1])) return true;
				else if(!is_array($value) && Run::$control->file->getBytesByUnit($value, "B") > 2 && Run::$control->file->getBytesByUnit($value, "B") <= Run::$control->file->getBytesByUnit((int)$params[0], $params[1])) return true;
				else{				
					$this->errors[$field]['label'] = $settings['label'];
					$nome = ($ind1 == -1) ? $_FILES[$field]["name"]	: $_FILES[$field][$ind1]["name"];
					$nome = ($ind2 == -1) ? $nome					: $_FILES[$field][$ind1][$ind2]["name"];
					$msgErroFile = str_replace('[name]', "<b>".$nome."</b>", $msgErro);		
					$count_erros++; $this->errors[$field][$count_erros] = $msgErroFile;
				}
			}
			else{
				if(is_array($value) && (int)$value["size"] <= (int)$params) return true;
				else if(!is_array($value) && (int)$value > 1 && (int)$value <= (int)$params) return true;
				else{
					$this->errors[$field]['label'] = $settings['label'];
					$nome = ($ind1 == -1) ? $_FILES[$field]["name"]	: $_FILES[$field][$ind1]["name"];
					$nome = ($ind2 == -1) ? $nome					: $_FILES[$field][$ind1][$ind2]["name"];
					$msgErroFile = str_replace('[name]',$nome, $msgErro);		
					$count_erros++; $this->errors[$field][$count_erros] = $msgErroFile;
				}				
			}
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function _minfileSize($settings, $field, $value, $params, $count_erros, $msgErro, $ind1=-1){
		Error::writeLog("_maxfileSize: ".$value, __FILE__, __LINE__);
		if(is_array($value["size"])){ 
			foreach($value as $k => $v){ 
				if(!$this->_minfileSize($settings, $v, $params, $count_erros++, $msgErro, $k)){
					return false;
				}
			}
		}
		else{
			if(isset($value["tmp_name"]) && $value["tmp_name"] == "") return true;//retorna se nao foi enviado arquivo
			else if(!isset($value["tmp_name"]) && $value=="") return true;
			
			if(is_array($params) && count($params) == 2){
				if(is_array($value) && Run::$control->file->getBytesByUnit((int)$value["size"], "B") >= Run::$control->file->getBytesByUnit($params[0], $params[1])) return true;
				else if(!is_array($value) && (int)$value > 1 && (int)$value <= (int)$params) return true;
				else{
					$nome = ($ind1 == -1) ? $_FILES[$field]["name"]:$_FILES[$field][$ind1]["name"];
					$msgErroFile = str_replace('[name]', "<b>".$nome."</b>", $msgErro);		
					$count_erros++; $this->errors[$field][$count_erros] = $msgErroFile;
				}
			}
			else{
				if(is_array($value) && (int)$value["size"] >= (int)$params) return true;
				else if(!is_array($value) && (int)$value > 1 && (int)$value <= (int)$params) return true;
				else{
					$nome = ($ind1 == -1) ? $_FILES[$field]["name"]:$_FILES[$field][$ind1]["name"];
					$msgErroFile = str_replace('[name]', "<b>".$nome."</b>", $msgErro);		
					$count_erros++; $this->errors[$field][$count_erros] = $msgErroFile;
				}			
			}
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function editValidation($fields, $valueServer, $valueClient){
		if(!is_array($fields)){
			$fields = explode(',', $fields);
			foreach($fields as $k => $v){
				$fields[$k] = Run::$control->string->trim($v);
			}
		}
		//Debug::print_r($fields);
		//Debug::print_r($this->model->schema['fields']);
		foreach($fields as $k => $field){
			//echo "<br /> CHECK /".$field ."/".array_key_exists($field, $this->model->schema['fields']);
			if(array_key_exists($field, $this->model->schema['fields'])){
					//echo "<br /> $valueServer ".$field." / ".$type." / ".$value;
				foreach($this->model->schema['fields'][$field]['validation'] as $type => $value){
					if(!isset($this->model->schema['fields'][$field]['validation_bkp'])) $this->model->schema['fields'][$field]['validation_bkp'] =  array();
					if(!isset($this->model->schema['fields'][$field]['validation_bkp'][$type])) $this->model->schema['fields'][$field]['validation_bkp'][$type] =  array();
					if($valueServer === true AND $this->model->schema['fields'][$field]['validation_bkp'][$type]){ echo ">> ". $this->print_rr($this->model->schema['fields'][$field]['validation_bkp'][$type]); $this->model->schema['fields'][$field]['validation'][$type][0] = $this->model->schema['fields'][$field]['validation_bkp'][$type]['server'];}
					if($valueClient === true AND $this->model->schema['fields'][$field]['validation_bkp'][$type]) $this->model->schema['fields'][$field]['validation'][$type] = $this->model->schema['fields'][$field]['validation_bkp'][$type]['client'];
					if($valueServer === false){  $this->model->schema['fields'][$field]['validation_bkp'][$type]['server'] = (!isset($this->model->schema['fields'][$field][$type][0])) ? false : $this->model->schema['fields'][$field][$type][0]; $this->model->schema['fields'][$field]['validation'][$type][0] = false; }
					if($valueClient === false){  $this->model->schema['fields'][$field]['validation_bkp'][$type]['client'] = (!isset($this->model->schema['fields'][$field][$type][1])) ? false : $this->model->schema['fields'][$field][$type][1]; $this->model->schema['fields'][$field]['validation'][$type][1] = false; }
				}
			}
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function addRules($fields){
		$this->editValidation($fields, true, true);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function removeRules($fields){
		$this->editValidation($fields, false, false);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function addRulesServer($fields){
		//echo "<br>ADD <br>";
		$this->editValidation($fields, true, '');
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function removeRulesServer($fields){
		//echo "<br>REMOVENDO <br>";
		$this->editValidation($fields, false, '');
	}
	//-------------------------------------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------------------------------------
	function addRulesClient($fields){
		$this->editValidation($fields, '', true);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function removeRulesClient($fields){
		$this->editValidation($fields, '', false);
	}
}
// ############################################################################################################################

?>