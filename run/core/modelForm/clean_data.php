<?php
// ****************************************************************************************************************************
class CleanData{
	private $model 				= NULL;
	//*************************************************************************************************************************
	function cleanData($model){
		Debug::log("Iniciando Core/Form/cleanData.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->model 		= $model;
		$this->checkCleanData();
		$this->checkRecoverData();
	}
	//*************************************************************************************************************************
	private function checkCleanData(){
		if(isset($_GET['cleanForm']) && $_GET['cleanForm'] === $this->model->settings['form_id']){
			$dataFormNew = array();
			$dataFormNew['cleaned'] = true;			
			if($this->model->settings['show_msg_clean']) Run::$view->render->setResponse("<p>Atenção: Você limpou os dados do formulário</p><p>Se preferir, <b><a href='".$this->model->aux->getRecoverForm("")."'>recupere os dados salvos anteriormente</a></b></p><p>Observação: Após atualizar o formulário abaixo, não será possível recuperar os dados anteriores.</p>", "warning msg-cleaned msg-".$this->model->session->getFormSessionId(), $this->model->session->getFormSessionId());

			$dataFormNew[$this->model->schema['from'][0]['pk']] = $_GET[$this->model->settings['ref']];

				//Run::$DEBUG_PRINT = 1;
			if(count($this->model->session->getDataSession()) == 1 && count($this->model->session->getRecoverSession()) == 1){
				//$this->model->dataFormSequencial 	= $this->model->dataForm;
				//Debug::p($this->model->dataFormSequencial);
				$this->model->session->setDataFormSession();
			}
				//Run::$DEBUG_PRINT = 0;

			$this->model->dataFormSequencial 	= $dataFormNew;
			$this->model->DataErrors 			= array();
			$this->model->session->setDataFormSession();
			$this->model->session->setDataErrorsSession();

			//exit;
			View::redirect("back");
		}
		//Run::$DEBUG_PRINT = 0;
	}
	//*************************************************************************************************************************
	private function checkRecoverData(){
		//Run::$DEBUG_PRINT = 1;
		//Debug::p($this->model->dataIntern);
		//Debug::p($this->model->session->getFormSessionId());
		if(isset($_GET['recoverForm']) && $_GET['recoverForm'] === $this->model->settings['form_id'] ){
			
			$dataRecovered = $this->model->session->getRecoverSession();
			if(is_array($dataRecovered) && count($dataRecovered) > 0){
				$this->model->dataFormSequencial = $dataRecovered;
				$this->model->session->setDataFormSession();
				if($this->model->settings['show_msg_clean']) Run::$view->render->setResponse("<p>Os dados limpos anteriormente foram recuperados com sucesso.</p>", "warning msg-cleaned msg-".$this->model->session->getFormSessionId(), $this->model->session->getFormSessionId());
			}else{
				Run::$view->render->setResponse("<p>Não foi possível recuperar os dados anteriores.</p>", "danger msg-cleaned msg-".$this->model->session->getFormSessionId(), $this->model->session->getFormSessionId());
			}
			View::redirect("back");
		}
		//Run::$DEBUG_PRINT = 0;
	}
	//*************************************************************************************************************************
}
// ############################################################################################################################

?>