<?php
// ****************************************************************************************************************************
class token{
	private $tokenId 	= 0;
	private $settings 	= array();
	//*************************************************************************************************************************
	function __construct($settings, $id=0){
		Debug::log("Iniciando Core/Form/token.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->tokenId = $id;
		Debug::p("tokenId", $this->tokenId );
		$this->settings = $settings;
	}
	//------------------------------------------------------------------------------------------------------------------------
	public function getToken(){
		//Run::$DEBUG_PRINT = 1;
		if(!is_array(Run::$session->get('tokens'))) Run::$session->set('tokens', array());
		$tk = Run::$session->get(array('tokens', $this->tokenId));
		if($tk == "") Run::$session->set(array('tokens', $this->tokenId), uniqid($this->tokenId, true));
		//echo "<br> criando: getToken: ".Run::$session->get(array('tokens', $this->settings['tables']));
		Action::logRun("token ".$this->tokenId, 0, 100, " getToken:".Run::$session->get(array('tokens', $this->tokenId)), 1);
		//Debug::print_r( Run::$session->get(array('tokens', $this->tokenId)) );
		//Run::$DEBUG_PRINT = 0;
		return Run::$session->get(array('tokens', $this->tokenId));
	}
	//------------------------------------------------------------------------------------------------------------------------
	public function checkToken($data){
		//Debug::p($data);
		//exit;
		//Run::$DEBUG_PRINT = 1;
		if(isset($data['form_id'])){
			$token_session = Run::$session->get(array('tokens', $this->tokenId));
			Debug::p("<br> CHECKANDO TOKEN / ".$this->settings['tables'].": ". $token_session ." / ". $this->DATA_INT['token']);
			//exit;
			if(!isset($data['token'])){
				//$this->ERRORS['Validação interna']['label'] = "Interno";
				//$this->ERRORS['Validação interna'][1] 		= "".Language::get('token');
				Action::logRun("token ".$this->tokenId, 0, 100, " Token não definido.", 1);
				return false;
			}
			if($token_session == "" || $data['token'] == "" || $token_session != $data['token']){
				//$this->ERRORS['Validação interna']['label'] = "Interno";
				//$this->ERRORS['Validação interna'][1]		= "".Language::get('token');
				Action::logRun("token ".$this->tokenId, 0, 100, " Token incorreto :".$data['token'], 1);
				return false;
			}
			else{
				Run::$session->set(array('tokens', $this->tokenId), "");
				return true;
			}
		}
		//Run::$DEBUG_PRINT = 0;
	}
	//------------------------------------------------------------------------------------------------------------------------
}
// ############################################################################################################################

?>