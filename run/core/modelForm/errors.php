<?php
// ****************************************************************************************************************************
class ErrorsForm{

	public 			 $model 				= array();
	//*************************************************************************************************************************
	function errorsForm($model){
		Debug::log("Iniciando Core/Form/errorsForm.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->model = $model;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getErrors(){
		if(count($this->model->dataErrors) ==  0){
			$this->model->dataErrors = $this->model->session->getErrorsSession();
		}
		return $this->model->dataErrors;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoErrorsList($qFields=5, $qErrors=5){
		$errors = $this->getErrors();
		if(!$errors || count($errors) <= 0) return false;
		$html   = "<dl>";
		$qFN 	= 0;
		$qEN 	= -1;
		foreach($errors as $field => $erros){
			if($qFN >= $qFields) break;
			$html  .= "<dt>".Language::get("form_erro_campo")." ".  $erros['label'] ."</dt>";
			foreach($erros as $k => $details){
				if($qEN >= $qErrors) break;
				if($k === "label") continue;
				$html .= "<dd>".Language::get("form_erro_detalhes")." ". $details ."</dd>";
				$qEN++;
			}
			$qFN++;
			$qEN = 0;
		}
		$html  .= "</dl>";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoErrorsResponse($qFields=5, $qErrors=5, $type="warning"){
		$errorsList = $this->echoErrorsList($qFields, $qErrors);
		if(!$errorsList || count($errorsList) <= 0) return false;//
		$typeMsg = (int)$this->model->dataFormSequencial[$this->model->schema['from'][0]['pk']] > 0 ? "update":"insert";
		Run::$view->render->setResponse("<h4>".Language::get('form_'.$typeMsg.'_unsaved')."</h4>", "danger", $this->model->session->getFormSessionId()."errors", "unsaved");
		Run::$view->render->echoResponse($this->model->session->getFormSessionId()."errors");
		$errorsList = "<h4>".Language::get("form_error_message")."</h4>" . $errorsList;
		Run::$view->render->setResponse($errorsList, $type, $this->model->session->getFormSessionId()."errors");
		Run::$view->render->echoResponse($this->model->session->getFormSessionId()."errors");
	}
}
// ############################################################################################################################
?>