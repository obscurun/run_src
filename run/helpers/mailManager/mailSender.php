<?php
Run::$DEBUG_PRINT = true;
require_once(RUN_PATH."libraries/phpmailer/PHPMailerAutoload.php");
Run::loadHelper("mailManager/mailManager");
// ********************************************************************************************************************************
class MailSender {
	private $mailManager = NULL;
	//-----------------------------------------------------------------------------------------------------------------------------
	function MailSender($mailManager=null){
		Debug::log("Iniciando MailManager/MailSender.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!$mailManager) $mailManager = new mailManager();
		$this->mailManager = $mailManager;
		return $this;	
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function send(){
		$resposta = false;
		$mail = new PHPMailer(); 
		flush();
		ob_start();

		//Debug::p("CONTEUDO", Run::$control->string->encodeFixUtf8($this->mailManager->content_html));
		//exit;
		//$mail->IsSendmail(); // telling the class to use SendMail transport
		$mail->IsSMTP(); // usando função padrão de email php
		//$mail->Debugoutput = 'html';
		$mail->Subject		= Run::$control->string->encodeFixUtf8($this->mailManager->send_subject);
		$mail->AltBody		= strip_tags($this->mailManager->send_message); // optional, comment out and test
		$mail->setFrom($this->mailManager->send_from['mail'], Run::$control->string->encodeFixUtf8($this->mailManager->send_from['name']));
		$mail->AddAddress($this->mailManager->send_to['mail'], Run::$control->string->encodeFixUtf8($this->mailManager->send_to['name']));
		if(isset($this->mailManager->send_reply['mail']) && $this->mailManager->send_reply['mail'] != "") $mail->AddReplyTo($this->mailManager->send_reply['mail'], Run::$control->string->encodeFixUtf8($this->mailManager->send_reply['name']));

		$this->mailManager->content_html = str_replace("[id]", $this->mailManager->ref_pk, $this->mailManager->content_html);

		if(count($this->mailManager->send_copy) > 0){
			foreach($this->mailManager->send_copy as $k => $copy){
				$mail->AddCC($copy['mail'], $copy['name']);
			}
		}
		if(count($this->mailManager->send_hidden) > 0){
			foreach($this->mailManager->send_hidden as $k => $copy){
				$mail->AddBCC($copy['mail'], $copy['name']);
			}
		}
		$mail->CharSet = 'UTF-8';
		$mail->MsgHTML(Run::$control->string->encodeFixUtf8($this->mailManager->content_html));
		$mail->IsHTML(true);

		$mail->Host 		= $this->mailManager->properties[$this->mailManager->send_prefix.'host']; 					
		$mail->SMTPAuth 	= $this->mailManager->properties[$this->mailManager->send_prefix.'smtp']; 
		$mail->Sender 		= $this->mailManager->properties[$this->mailManager->send_prefix.'mail']; 
		$mail->Username 	= $this->mailManager->properties[$this->mailManager->send_prefix.'login']; 
		$mail->Password 	= $this->mailManager->properties[$this->mailManager->send_prefix.'pass'];

        // enable SMTP authentication
		$door  = $this->mailManager->properties[$this->mailManager->send_prefix.'door'];
		$crypt = $this->mailManager->properties[$this->mailManager->send_prefix.'crypt'];
		if(isset($door) && $door != "")		$mail->Port = $door;
		else 		$mail->Port 	  = 25;
		if(isset($crypt) && $crypt != "") 	$mail->SMTPSecure = $crypt;

		$mail->SMTPDebug  	= 1;                 // sets the prefix to the servier	
	
		$resposta = $mail->Send();
		//echo ">>>> ".$mail->SMTPAuth;
		$error 	= ob_get_contents();
		ob_end_clean();
		flush();


		if( !$resposta ) {
			Error::writeLog("Erro MailInfo: ".$mail->ErrorInfo."\n".$error, __FILE__, __LINE__, '');
			Debug::p("Erro: ".$mail->ErrorInfo, $error);
	    	Error::show(0, "MailSender: Ocorreu um erro ao enviar e-mail: \n ".$mail->ErrorInfo. __FUNCTION__, __FILE__, __LINE__, '');
	    	
	    	if(Config::MAIL_TRY_SEND_SERVER === true){
				$mail->IsMail();
				$resposta = $mail->Send();
				ob_flush();
		    	flush();
				if(!$resposta){ return -2;	}
				else{  			return 2;	}
	    	}
	    	return -1;
	    }
	    return 1;
	}
}
// ********************************************************************************************************************************
?>