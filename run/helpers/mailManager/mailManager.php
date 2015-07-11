<?php
Run::$DEBUG_PRINT = true;
Run::loadHelper("mailManager/mailSender");
// ********************************************************************************************************************************
class MailManager{
	public  $properties 		= NULL;
	public  $content_html		= "";
	public  $sender_total		= 0;
	public  $send_prefix		= "";
	public  $send_id			= 0;
	public  $send_from			= array();	
	public  $send_to			= array();
	public  $send_reply			= array();
	public  $send_copy			= array();
	public  $send_hidden		= array();
	public  $send_subject		= "";
	public  $send_message		= "";
	public  $ref_pk				= 0;
	private $ref_fk_user		= 0;
	private $ref_fk_table		= "";
	private $ref_fk_table_ref	= 0;
	private	$database			= NULL;
	private	$connectionID		= NULL;
	private	$sender				= NULL;
	//-----------------------------------------------------------------------------------------------------------------------------
	function MailManager($mailIndex=0, $connectionID=""){
		Debug::log("Iniciando Helper/MailManager.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->getMailProperties($mailIndex);
		$this->send_prefix = "sender_".$mailIndex."_";
		$this->connectionID = $connectionID;
		$this->sender = new MailSender($this);
		return $this;	
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function addMailList(){
		if(!$this->database) $this->database = Model::connect($this->connectionID);
		$query  = Model::$query;
		$result = $query->insert("mail_manager")
						->fields(array(	'fk_user',
										'fk_table',
										'fk_table_ref',
										'from_name',
										'from_mail',
										'to_name',
										'to_mail',
										'subject',
										'content',
										'date_insert',
										'status_int'
								))
						->values(array(	(int)($this->ref_fk_user),
										"\"".Run::$control->protect->getProtectData($this->ref_fk_table)."\"",
										(int)($this->ref_fk_table_ref),
										"\"".Run::$control->protect->getProtectData($this->send_from['name'])."\"",
										"\"".Run::$control->protect->getProtectData($this->send_from['mail'])."\"",
										"\"".Run::$control->protect->getProtectData($this->send_to['name'])."\"",
										"\"".Run::$control->protect->getProtectData($this->send_to['mail'])."\"",
										"\"".Run::$control->protect->getProtectData($this->send_subject)."\"",
										"\"".Run::$control->protect->addSlashe($this->send_message)."\"",
										"\"".Run::$control->date->getDateUs()."\"",
										"\"1\""
								))
						->execute()->getResult();
		//Debug::p($result); exit;
		$warMsg = $this->database->getWarning();
		if((is_integer($result) || $warMsg != "") && $this->database->getError() != "00000"){ 
			Error::show(5200, "Model-> Erro no SQL:\n ".$warMsg."\n  ". $this->database->getError() ."  \n$sql_query ".__FUNCTION__, __FILE__, __LINE__, '');
			return $this;
		}
		else{
			$this->ref_pk = $query->getID();
			return $this;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setRefUser($pk=""){
		$this->ref_fk_user = $pk;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setRefFk($pk=""){
		$this->ref_fk_table_ref = $pk;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setRefTable($table=""){
		$this->ref_fk_table = $table;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setHtml($html=""){
		$this->content_html = $html;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setFrom($mail="", $name=""){
		$this->send_from['mail'] = $mail;
		$this->send_from['name'] = $name;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setTo($mail="", $name=""){
		$this->send_to['mail']= $mail;
		$this->send_to['name']= $name;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setCopy($mail="", $name=""){
		$copy = array("mail"=>$mail, "name"=>$name);
		array_push($this->send_copy, $copy);
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setHidden($mail="", $name=""){
		$copy = array("mail"=>$mail, "name"=>$name);
		array_push($this->send_hidden, $copy);
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setSubject($text=""){
		$this->send_subject = $text;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setMessage($text=""){
		$this->send_message = $text;
		$this->content_html = $this->getDefaultHtml($this->send_message);
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setDatabase($db){
		$this->database = $db;
		return $this;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function send(){
		if($this->send_message == ""){
			Error::show(0, "MailSender: É preciso definir uma mensagem para envio ",  __FILE__, __LINE__, '');
		}
		$sent = $this->sender->send();
		$query  = Model::$query;
		$result = $query->update("mail_manager")->set(" sent_status = '". $sent ."', try_count = try_count+1, date_update = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$this->ref_pk."'")->execute()->getResult();
				
		return $sent;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function periodicAutoSendMail(){
		Run::$DEBUG_PRINT = true;
		$t = (int)Run::CRON_JOB_TIME;
		$time_exe = ($t*60)+30+Run::MAIL_AUTO_SEND_LIMIT*3;
		set_time_limit($time_exe);
		ob_flush();
		flush();
		Debug::p("time_exe (t=".$t.")", $time_exe);
		ob_flush();
		flush();
    	Run::$benchmark->mark("periodicAutoSendMail/Inicio");
		for( $n=0 ; $n<=$t ; $n++ ){
			$result = $this->triggerPeriodicAutoSendMail();
			if($result !== true ) break;
    		Run::$benchmark->writeMark("periodicAutoSendMail/Inicio", "periodicAutoSendMail/loop$t");
			sleep(60-Run::MAIL_AUTO_SEND_LIMIT*3);
		}
    	Run::$benchmark->writeMark("periodicAutoSendMail/Inicio", "periodicAutoSendMail/final");
		Debug::p("periodicAutoSendMail finalizado");
		exit;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function triggerPeriodicAutoSendMail(){
		ob_clean();
		ob_flush();
		flush();
		if(!$this->database) $this->database = Model::connect($this->connectionID);
		$query  = Model::$query;
		$result = $query
					->select(array(		'pk_mail',
										'fk_user',
										'fk_table',
										'fk_table_ref',
										'from_name',
										'from_mail',
										'to_name',
										'to_mail',
										'subject',
										'content',
										'date_insert',
										'status_int'
					))
					->from("mail_manager")
					->where(" status_int = 1 AND sent_status <= 0")
					->order("pk_mail ASC")
					->limit(0, Run::MAIL_AUTO_SEND_LIMIT)
					->execute()
					->returnAssoc();
		$warMsg = $this->database->getWarning();
		if(($warMsg != "") && $this->database->getError() != "00000"){ 
			Error::show(5200, "Model-> Erro ao selecionar mailManager:\n ".$warMsg."\n  ". $this->database->getError() ."  \n$sql_query ".__FUNCTION__, __FILE__, __LINE__, '');
		}
		else{
			if(count($result) == 0){
				return false;
			}
			foreach($result as $pk => $field){
				if($field['content'] == ""){
					$result = $query->update("mail_manager")->set(" sent_status = '-3', try_count = try_count+1, status_int = -2, date_update = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$field['pk_mail']."'")->execute()->getResult();
					continue;
				}
				if($field['from_mail'] == ""){
					$result = $query->update("mail_manager")->set(" sent_status = '-4', try_count = try_count+1, status_int = -2, date_update = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$field['pk_mail']."'")->execute()->getResult();
					continue;
				}
				if($field['to_mail'] == ""){
					$result = $query->update("mail_manager")->set(" sent_status = '-5', try_count = try_count+1, status_int = -2, date_update = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$field['pk_mail']."'")->execute()->getResult();
					continue;
				}
				$this->ref_pk = $field['pk_mail'];
				$field['content'] = str_replace('[id]', $field['pk_mail'], $field['content']);

				$this->setFrom($field['from_mail'], $field['from_name']);
				$this->setTo($field['to_mail'], $field['to_name']);
				$this->setMessage($field['content']);
				$this->setSubject($field['subject']);
				$resultSend = $this->send();
				$result = $query->update("mail_manager")->set(" sent_status = '". $resultSend ."', try_count = try_count+1, date_update = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$field['pk_mail']."'")->execute()->getResult();
				if((is_integer($result) || $warMsg != "") && $this->database->getError() != "00000"){ 
					Error::show(5200, "Model-> Erro ao atualizar mailManager:\n ".$warMsg."\n  ". $this->database->getError() ."  \n$sql_query ".__FUNCTION__, __FILE__, __LINE__, '');
				}
				Debug::p("enviado $resultSend", $field);
				flush();
				sleep(1);
			}
		}
		return true;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function checkReadMail($pk=0){
		$image = imagecreatefromstring(file_get_contents(RUN_PATH.'helpers/mailManager/blank.gif'));
		header('Content-Type: image/gif');
		imagepng($image);
		if(!$this->database) $this->database = Model::connect($this->connectionID);
		$pk = (int)$pk;
		$query  = Model::$query;
		$result = $query->update("mail_manager")->set(" mail_read = mail_read+1, date_read = '". Run::$control->date->getDateUs() ."'")->where(" pk_mail = '".$pk."'")->execute()->getResult();
		if((is_integer($result) || $warMsg != "") && $this->database->getError() != "00000"){ 
		//	Error::show(5200, "Model-> Erro ao atualizar mailManager/checkReadMail:\n ".$warMsg."\n  ". $this->database->getError() ."  \n$sql_query ".__FUNCTION__, __FILE__, __LINE__, '');
		}
		exit;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	private function getMailProperties($mailIndex=0){
		$this->send_prefix 			= "sender_".$mailIndex."_";
		$this->properties 			= Run::$properties->getProperties("mail");
		if($this->sender_total < 1){
			$total = 0;
			while(isset( $this->properties["sender_".$total."_"."mail"] )){
				$this->sender_total = $total;
				$total++;
			}
		}
		$this->send_from['mail']	= $this->properties[$this->send_prefix."mail"];
		$this->send_from['name']	= $this->properties[$this->send_prefix."name"];
		$this->send_to['mail'] 		= $this->properties[$this->send_prefix."mail"];
		$this->send_to['name'] 		= $this->properties[$this->send_prefix."name"];
		$this->send_reply['mail'] 	= $this->properties[$this->send_prefix."reply_mail"];
		$this->send_reply['name'] 	= $this->properties[$this->send_prefix."reply_name"];
		//Debug::p($this->properties);
		//Debug::p($this->send_from);

	}
	//-------------------------------------------------------------------------------------------------------------------------	
	public function getDefaultHtml($message="<!-- mensagem não incluida -->"){
		$html = "";
		if($this->content_html != ""){
			$html = str_replace('[message]', $this->send_message, $this->content_html);
			$html = str_replace('[mensagem]', $this->send_message, $html);
		}
		//else if(isset(Template::$mailBodyHtml) && Template::$mailBodyHtml != ""){
		//	$html = str_replace('[message]', $message, Template::$mailBodyHtml);
		//	$html = str_replace('[mensagem]', $message, $html);
		//	$html = str_replace('[MENSAGEM]', $message, $html);
		//}
		else{
			$html  = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
			$html .= "<html>";
			$html .= "<head>";
			$html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />";
			$html .= "<title>Run Mail</title>";
			$html .= "<style>";
			$html .= "p{ margin:0; padding:0; margin-bottom:5px;}";
			$html .= "</style>";
			$html .= "</head>";
			$html .= "<body bgcolor=\"#FFFFFF\" text=\"#777777\" link=\"#999999\" vlink=\"#999999\" alink=\"#999999\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
			$html .= '<table width="100%" height="10" bgcolor="#fbfbfb" border="0" align="center" cellpadding="5" cellspacing="0">';
			$html .= "<tr><td>";
			$html .= "<font face=\"Arial\" size=\"2\" style=\"font-size:12px;\">";
			$html .= " <h4>". Run::$control->string->encodeIso(Config::NAME) ."</h4>";
			$html .= "</font>";
			$html .= "</td></tr>";
			$html .= "</table>";
			$html .= '<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">';
			$html .= "<tr><td><div style=' margin:5px;'>";
			$html .= "<font face=\"Arial\" size=\"2\" style=\"font-size:11px;\">";
			$html .= $this->send_message;
			$html .= "</font>";
			$html .= "</div></td></tr>";
			$html .= "</table>";
			$html .= "</body>";
			$html .= "</html>";
		}
		$html = str_replace('</body>', "<img src=\"".Run::$router->path['url']."blank.gif?checkReadMail=[id]\" width=\"0\" border=\"0\" height=\"0\">"."</body>", $html);
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------	
	public function getDatatable($data=array()){
		/*
			formato aceito:
			array(
				array('propriedade' => 'valor'),
				array('propriedade' => 'valor'),
				array('propriedade' => 'valor')
			)
		*/
		if(!is_array($data) && $data != ''){
			$dados = array();
			foreach($data->schema['fields'] as $name => $prop){
				if($prop['type'] == "date_update") continue;
				$val = $data->dataFormRecorded[$prop['belongsTo']][$name];
				if(is_array($val)) $val = implode(", ", $val);
				array_push($dados, array($prop['label'] => $val));
			}
			$data = $dados;
		}

		$html .= "<font face=\"Arial\" size=\"2\" style=\"font-size:11px;\">";
		$html .= '<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">';

		foreach($data as $k => $values){
			$html .= "<tr>";
			foreach($values as $prop => $val){
				$html .= "<td align='right'  style='border-bottom:1px dotted #bbb;'><b>";
				$html .= $prop;
				$html .= "</b>:</td>";
				$html .= "<td align='left'  style='border-bottom:1px dotted #ccc;'>";
				$html .= $val;
				$html .= "</td>";
			}
			$html .= "</tr>";
		}

		$html .= "</table>";
		$html .= "</font>";
		return $html;
	}
}
// ********************************************************************************************************************************
?>