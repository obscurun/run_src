<?php

// guarda tudo o que é impresso na memória para não imprimir na página de processamento
//ob_start();


// carrega as dependências
require_once(RUN_PATH.'core/model/mysql_query.php');
require_once(RUN_PATH.'core/modelForm/check.php');
require_once(RUN_PATH.'core/modelForm/token.php');
require_once(RUN_PATH.'core/modelForm/data.php');
require_once(RUN_PATH.'core/modelForm/form_aux.php');
require_once(RUN_PATH.'core/modelForm/session.php');
require_once(RUN_PATH.'core/modelForm/select_data.php');
require_once(RUN_PATH.'core/modelForm/save_data.php');
require_once(RUN_PATH.'core/modelForm/validate.php');
require_once(RUN_PATH.'core/modelForm/errors.php');



//*****************************************************************************************************************************
class modelForm{
	public 			 $session				= NULL;
	public 			 $form					= NULL;
	public 			 $check					= NULL;
	public 			 $token					= NULL;
	public 			 $validate				= NULL;
	public 			 $errors				= NULL;
	public 			 $saveData				= NULL;
	public 			 $selectData			= NULL;
	public 			 $settings 				= array();
	public 			 $schema				= array();
	public 			 $schema_unions			= array();
	public 			 $database				= "";
	public  		 $query		 			= false;
	public 			 $dataIntern			= array(); // dados internos, usados como referência, ID, paginação, ordenação, etc...
	public 			 $dataForm				= array(); // dados recebidos do form
	public 			 $dataFormChecked		= array(); // retorno dos dados convertidos e analisados
	public 			 $dataFormRecorded		= array(); // retorno dos dados salvos e processados
	public 			 $dataFormSequencial	= array(); // usado no form, select, save
	public 			 $dataFormTabulated		= array(); // deprecated - não usado no momento
	public 			 $dataFormRecursive		= array(); // deprecated - não usado no momento
	public 			 $dataFormPKList		= array(); // dados recebidos no select, apenas PKs como referência para autoDelete
	public 			 $dataErrors			= array(); // apenas dados que geraram erros no validate ou no save
	public 			 $selectType			= "select";
	//*************************************************************************************************************************
	function modelForm(){
		Debug::log("Iniciando Core/Form.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(function_exists("get_called_class")) Debug::log("Iniciando form pela classe ". get_called_class() , __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		

		Run::$benchmark->mark("FormModel/Inicio");
		Debug::print_r("request", $_REQUEST);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		// Instancia a sessão usada para gerenciar os dados entre a View e o FormModel
		$this->session 		= new SessionForm($this);
		$this->aux 			= new FormAux($this);
		$this->errors		= new ErrorsForm($this);





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Checa se o model foi definido com os arrays corretamente
		$this->setSchema();
		$this->check    	= new Check();
		$checked 			= $this->check->checkModel($this->schema, $this->settings);

		$this->schema		= $checked["schema"];
		$this->settings		= $checked["settings"];

		foreach($this->schema_unions as $k => $schemaU){
			if(is_array($schemaU)){
				$schemaUChecked = $this->check->checkModel($schemaU, $this->settings);
				$this->schema_unions[$k] = $schemaUChecked["schema"];
			}
		}
		
		Run::$benchmark->writeMark("FormModel/Inicio", "FormModel/Check/Schema/Settings");






		//INSTANCE DATABASE / SUBCLASSES -----------------------------------------------------------------------------------------------------------------------------------------------------------------





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Iniciar banco de dados se houver instancia, inicia classes Token, Data, SaveData e SelectData
		$this->database 	= Model::getInstance();
		if(!$this->database){
			Error::show(5200, "Model-> A conexão com o banco não foi iniciada. ".__FUNCTION__, __FILE__, __LINE__, '');
			$this->settings['auto_load'] = false;
			$this->settings['auto_save'] = false;
		}
		$this->query 		= new MysqlQuery();
		$this->validate    	= new Validate();
		$this->data  		= new FormModel\Data($this->schema, $this->settings);
		$this->saveData  	= new SaveData($this, $this->database, $this->query);
		$this->selectData  	= new selectData($this);
		
		Run::$benchmark->writeMark("FormModel/Check/Schema/Settings", "FormModel/Instance/Mysql/Validate/SaveData/SelectData");







		//EXE REQUEST / POST / FILES / REF -----------------------------------------------------------------------------------------------------------------------------------------------------------------





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Executa método exeBeforeRequest usando na aplicação, trata os requests e unifica em DataForm e executa exeAfterRequest
		$this->exeBeforeRequest();
		$this->dataForm 	= $this->data->getRequests();
		$this->dataIntern 	= $this->data->getDataInternal();
		$this->exeAfterRequest();
		
		Run::$benchmark->writeMark("FormModel/Instance/Mysql/Validate/SaveData/SelectData", "FormModel/exeBeforeRequest/getRequests/exeAfterRequest");

		//Debug::print_r("request", $_REQUEST);
		//Debug::print_r("dataForm", $this->dataForm);
		//Debug::print_r("dataFormSequencial", $this->dataFormSequencial);
		//Debug::print_r("SESSION", $this->session->getDataSession());





		//$this->session->setSettings($this->schema, $this->settings, $this->dataIntern);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		//Valida se existe erro ao configurar o schema e o setting no model da aplicação
		if(count($this->data->dataErrors) > 0){
			$this->dataErrors['dadosInternos'] = $this->data->dataErrors;
			$this->dataErrors['dadosInternos']['label'] = Language::get("form_erro_dados_internos");
		}






		//CHECK TOKEN / VALIDATION -----------------------------------------------------------------------------------------------------------------------------------------------------------------






		//Debug::print_r($this->dataIntern);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Checa o token do form e identifica o tipo de operação insert/update e executa Validação
		$this->token = new Token($this->settings,$this->session->getFormSessionId());
		if(isset($this->dataForm['form_id']) && ($this->dataForm['form_id'] == $this->settings['form_id']) ){
			$this->settings['auto_load'] = false;
			$checkToken = ($this->settings['check_token'] == true) ? $this->token->checkToken($this->dataForm) : true;
			//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
			if(!$checkToken){
				$this->dataErrors['token']['label'] = Language::get("form_token_label");
				$this->dataErrors['token'][1] = Language::get("form_token_description");
			}
			//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
			Debug::log("Model-> SET auto_load: false; ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			$pk_schema  = (int)$this->dataForm[$this->schema['from'][0]['pk']];
			if($pk_schema < 1){
				Debug::log("Model-> dataForm-action =".$this->dataForm['form_action']." / SET auto_save: insert; ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
				$this->settings['auto_save_type'] = "insert";
			}
			else{
				Debug::log("Model-> dataForm-action =".$this->dataForm['form_action']." / SET auto_save: update; ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
				$this->settings['auto_save_type'] = "update";
			} 
			//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
			if($this->settings['val_server'] === true && $this->settings['auto_save'] == true && count($this->dataErrors) <= 0){
				$this->dataFormChecked = $this->data->checkData($this->schema, $this->dataForm);
				$this->dataErrors = $this->validate->validator($this->schema, $this->dataFormChecked, $this->dataForm);
			}
			//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		} else{
			$this->settings['auto_save'] = false;
		}		
		Run::$benchmark->writeMark("FormModel/exeBeforeRequest/getRequests/exeAfterRequest", "FormModel/checkToken/validator");





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Deleta dados automaticamnete do registro, caso não tenha erros do form
		if(isset($this->dataForm['form_id']) && count($this->dataErrors) <= 0 && $this->settings['auto_delete'] !== false){
			$this->saveData->autoDeletePKs($this->dataForm);
			//echo 123; exit;
		}

		Run::$benchmark->writeMark("FormModel/checkToken/validator", "FormModel/autoDelete");







		//AUTO SAVE -----------------------------------------------------------------------------------------------------------------------------------------------------------------







		Debug::p("dataFormChecked", $this->dataFormChecked);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Salva os dados do formulário caso tenha permissão e não contanha erros e redireciona para o View
		if(isset($this->dataForm['form_id']) && ($this->dataForm['form_id'] == $this->settings['form_id']) && count($this->dataErrors) <= 0 && ($this->settings['permission_'.$this->settings['auto_save_type']] == true) && ($this->settings['auto_save'] !== false) ){
			//Debug::log("Model-> INI auto insert/update; ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			//Run::$DEBUG_PRINT = 1;
			$checkToken = ($this->SETTINGS['check_token'] == true) ? $this->token->checkToken() : true;
			if($checkToken){
				//Debug::log("Model-> INI checkData; ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
				Debug::p("dataFormChecked", $this->dataFormChecked);
				if(count($this->dataFormChecked) == 0) $this->dataFormChecked = $this->data->checkData($this->schema, $this->dataForm);
				$this->dataFormRecorded = $this->saveData->save($this->dataFormChecked, $this->dataIntern, $this->schema, $this->settings);
				$erros = $this->saveData->dataErrors;
				if(is_array($erros) && count($erros) > 0){
					Debug::p("SAVE/erros", $erros);
				}else{
					$this->session->delDataSession();
					//exit;
					Render::setResponse($this->settings['msg_'.$this->settings['auto_save_type'].'_sucess'], "success", $this->session->getFormSessionId());
					
					Run::$benchmark->writeMark("FormModel/autoDelete", "FormModel/saveData/save/delDataSession");

					Debug::p("dataFormRecorded", $this->dataFormRecorded);

					if($this->settings['auto_save_type'] == "insert"){
						if($this->settings['redirect_insert'] !== false){
							$this->settings['redirect_insert'] = $this->aux->convertStringToData($this->settings['redirect_insert']);
							Debug::p("REDIRECT1 ", $this->settings['redirect_insert']);
							View::redirect($this->settings['redirect_insert']);
						}
						else{
							$this->settings['redirect'] = $this->aux->convertStringToData($this->settings['redirect']);
							Debug::p("REDIRECT2 ", $this->settings['redirect']);
							View::redirect($this->settings['redirect']);
						}
					}else{
						if($this->settings['redirect_update'] !== false){
							$this->settings['redirect_update'] = $this->aux->convertStringToData($this->settings['redirect_update']);
							Debug::p("REDIRECT3 ", $this->settings['redirect_update']);
							View::redirect($this->settings['redirect_update']);
						}
						else{
							$this->settings['redirect'] = $this->aux->convertStringToData($this->settings['redirect']);
							Debug::p("REDIRECT4 ", $this->settings['redirect']);
							View::redirect($this->settings['redirect']);
						}
					}
					//ob_end_clean();
				}
			}
			//$this->delete();
		}
		if(isset($this->dataForm['form_id']) && count($this->dataErrors) <= 0 && ($this->settings['permission_'.$this->settings['auto_save_type']] == true) ){
			$this->exeCustomSave();
		}






		//AUTO SELECT -----------------------------------------------------------------------------------------------------------------------------------------------------------------





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Seleciona os dados no banco de dados, caso não tenha salvo os dados vindos do form
		if($this->dataIntern[$this->settings['ref']] > 0 && $this->settings['permission_select'] == true && $this->settings['auto_load'] !== false ){
			$returnDatas = $this->selectData->select($this->selectType, $this->dataIntern, $this->dataForm, $this->schema, $this->settings, $this->schema_unions);
			//Debug::p($returnDatas);
			$this->dataFormSequencial 	= $returnDatas['dataSelectSequencial'];
			$this->dataFormPKList 		= $returnDatas['dataSelectPKList'];
			$this->dataFormTabulated 	= $returnDatas['dataSelectTabulated'];
			$this->dataFormRecursive 	= $returnDatas['dataSelectRecursive'];
			$this->session->setPKListSession($this->dataFormPKList);
			if( !isset($this->dataFormSequencial[$this->schema['from'][0]['pk']]) ){
				//Run::$DEBUG_PRINT = 1;
				$errorMsg = str_replace('[path]', Run::$router->path['base'].Run::$router->getPath(-1), Language::get("form_msg_auto_load_error"));
				$errorMsg = $this->aux->convertStringToData($errorMsg);
				Debug::p("errorMsg", Run::$router->getPath(-1));
				Render::setResponse($errorMsg, "danger", $this->session->getFormSessionId());
			}
		}else{
			$this->dataFormSequencial 	= $this->dataForm;
		}
		$this->exeCustomSelect();

		Debug::p("PKList", $this->dataFormPKList);

		Run::$benchmark->writeMark("FormModel/autoDelete", "FormModel/selectData/select");






		//SET SESSION -----------------------------------------------------------------------------------------------------------------------------------------------------------------






		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Caso receba dados no formulário e tenha erros, retorna os erros no form
		if(isset($this->dataForm['form_id']) && count($this->dataErrors) >= 1){
			Debug::p("dataErrors  ", $this->dataErrors);
			$this->session->setDataSession();
			Run::$benchmark->writeMark("FormModel/selectData/select", "FormModel/setDataSession");
			View::redirect("back");
			////ob_end_flush();
			//exit;
		}







		//DEL SESSION -----------------------------------------------------------------------------------------------------------------------------------------------------------------







		Debug::p("dataFormSequencial"	, $this->dataFormSequencial);
		Debug::p("dataForm"				, $this->dataForm);
		//exit;
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Caso tenha recebido os dados do form e não tenha erros, apaga a sessão e volta pra View
		if(isset($this->dataForm['form_id']) && count($this->dataErrors) <= 0){
			$this->session->delDataSession();
			Run::$benchmark->writeMark("FormModel/setDataSession", "FormModel/delDataSession");
			View::redirect("back");
			//ob_end_clean();
		}





		//GET SESSION -----------------------------------------------------------------------------------------------------------------------------------------------------------------





		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Na view, caso tenha dados no Session, é recebido os dados para imprimir no formulário
		if(!isset($this->dataForm['form_id'])){ //&& count($this->dataFormSequencial) == 0
			if(count($this->session->getDataSession()) > 1) $this->dataFormSequencial = $this->session->getDataSession();
			//exit;
		}





		//FINISHED -----------------------------------------------------------------------------------------------------------------------------------------------------------------




		// Debugação pronta
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		Debug::p("dataForm"				, $this->dataForm);
		Debug::p("dataFormSequencial"	, $this->dataFormSequencial);
		Debug::p("SESSION"				, $this->session->getDataSession());
		//Debug::p("dataErrors"			, $this->dataErrors);
		//Debug::p("dataIntern"			, $this->dataIntern);
		//Debug::p("settings"			, $this->settings);
		//Debug::p("language"			, language::$phrase);
		//Debug::showLog();
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		//Debug::getBacktrace();
		//Debug::showBacktrace();


		Run::$benchmark->writeMark("FormModel/Inicio", "FormModel/Final");
		// finaliza o flush para exibir tudo que foi impresso ao longo do processamento
		//ob_end_flush();
	}
	//-------------------------------------------------------------------------------------------------------------------------





	public function setInstances(){ 		// método chamado para instanciar as classes para usar no model da aplicação		
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Instancia a sessão usada para gerenciar os dados entre a View e o FormModel
		$this->session 		= new SessionForm($this);
		$this->aux 			= new FormAux($this);
		$this->check    	= new Check();
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		// Iniciar banco de dados se houver instancia, inicia classes Token, Data, SaveData e SelectData
		$this->database 	= Model::getInstance();
		if(!$this->database){
			Error::show(5200, "Model-> A conexão com o banco não foi iniciada. ".__FUNCTION__, __FILE__, __LINE__, '');
			$this->settings['auto_load'] = false;
			$this->settings['auto_save'] = false;
		}
		$this->query 		= new MysqlQuery();
		$this->validate    	= new Validate();
		$this->data  		= new FormModel\Data($this->schema, $this->settings);
		$this->saveData  	= new SaveData($this, $this->database, $this->query);
		$this->selectData  	= new selectData($this);
	}
	public function setSchema(){} 			// método chamado nos models da aplicação
	//-------------------------------------------------------------------------------------------------------------------------
	public function exeBeforeRequest(){} 	// método chamado antes do parse Post > dataForm
	//-------------------------------------------------------------------------------------------------------------------------
	public function exeAfterRequest(){} 	// método chamado depois do parse Post > dataForm
	//-------------------------------------------------------------------------------------------------------------------------
	public function exeCustomSave(){} 		// método chamado depois do autoSave
	//-------------------------------------------------------------------------------------------------------------------------
	public function exeCustomSelect(){} 	// método chamado depois do autoSelect




	
	//*************************************************************************************************************************
}
//*****************************************************************************************************************************

?>