<?php
// ********************************************************************************************************************************
class Template{
	public	static		$mailBodyHtml				= "";

	public static		$STRUCTURE					= array();
	public static		$STRUCTURE_ACTIVE			= array();
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	// ****************************************************************************************************************************
	function __construct(){
		//-----------------------------------------------------------------------------------------------
		$this->getAreas();
		$this->checkAreas();
		$this->setHtmlMail();
		//-----------------------------------------------------------------------------------------------
		Debug::log("Finalizando configuração do template.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	function getAreas(){
		self::$STRUCTURE['ACTIVE'] = array();
	    self::$STRUCTURE['ACTIVE']['AREA']  = "";
	    self::$STRUCTURE['ACTIVE']['SUB']   = "";
	    self::$STRUCTURE['ACTIVE']['TIT']   = "";

		self::$STRUCTURE['home']['label']	= "Home";
		self::$STRUCTURE['home']['pag']		= Config::$PATH."home";

		self::$STRUCTURE['perfil']['label']													= "Meus Dados";
		self::$STRUCTURE['perfil']['pag']													= Config::$PATH.'meusdados/ficha';
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['label']							= 'Acessar Meus Dados';
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['View']['label']			= "Meus Dados";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['View']['details']			= "Detalhes do Cadastro";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['View']['pag']				= Config::$PATH.'meusdados/ficha';
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['Form']['label']			= "Atualizar meus Dados";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['Form']['details']			= "Editar Detalhes do Cadastro";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['Form']['pag']				= Config::$PATH.'meusdados/atualizar';
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['senha']['label']			= "Atualizar Senha";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['senha']['details']			= "Atualizar Senha do Meu Login";
		self::$STRUCTURE['perfil']['sub']['meu_perfil']['subs']['senha']['pag']				= Config::$PATH.'meusdados/senha';

		self::$STRUCTURE['cadastro']['label']												= "Imóveis";
		self::$STRUCTURE['cadastro']['pag']													= Config::$PATH.'imovel/busca';
		self::$STRUCTURE['cadastro']['sub']['imovel']['label']								= 'Busca de Imóveis';
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['List']['label']				= "Busca de Imóveis";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['View']['label']				= "Ficha do Imóvel";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['Form']['label']				= "Cadastro de Imóvel";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['List']['details']			= "Encontre um Imóvel Pré-Cadastrado";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['View']['details']			= "Detalhes do Imóvel Pré-Cadastrado";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['Form']['details']			= "Novo Imóvel para o CAR";
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['List']['pag']				= Config::$PATH.'imovel/busca';
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['View']['pag']				= Config::$PATH.'imovel/ficha';
		self::$STRUCTURE['cadastro']['sub']['imovel']['subs']['Form']['pag']				= Config::$PATH.'imovel/cadastro';
		
		self::$STRUCTURE['cadastro']['sub']['imovel_cadastro']['label']						= "Cadastro de Imóvel";
		self::$STRUCTURE['cadastro']['sub']['imovel_cadastro']['perfis']					= array(12, 19, 20, 21);
		self::$STRUCTURE['cadastro']['sub']['imovel_cadastro']['subs']['Form']['label']		= "Cadastro de Imóvel";
		self::$STRUCTURE['cadastro']['sub']['imovel_cadastro']['subs']['Form']['details']	= "Novo Imóvel para o CAR";
		self::$STRUCTURE['cadastro']['sub']['imovel_cadastro']['subs']['Form']['pag']		= Config::$PATH.'imovel/cadastro';
		
		self::$STRUCTURE['cadastro']['sub']['imovel_financeiro']['label']					= "Financeiro";
		self::$STRUCTURE['cadastro']['sub']['imovel_financeiro']['perfis']					= array(0);
		self::$STRUCTURE['cadastro']['sub']['imovel_financeiro']['subs']['Form']['label']	= "Financeiro";
		self::$STRUCTURE['cadastro']['sub']['imovel_financeiro']['subs']['Form']['details']	= "Fatura do Imóvel";
		self::$STRUCTURE['cadastro']['sub']['imovel_financeiro']['subs']['Form']['pag']		= Config::$PATH.'imovel/financeiro';

		self::$STRUCTURE['interessados']['label']													= "Interessados";
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['label']						= "Lista de Interessados";
		//self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['perfis']						= array(14, 19, 20, 21);
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['List']['label']		= "Interessados";
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['List']['details']	= "Interessados para o CAR / Hotsite";
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['List']['pag']		= Config::$PATH.'interessados/busca';
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['View']['label']		= "Cadastro de Interesse";
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['View']['details']	= "Interesse para o CAR ";
		self::$STRUCTURE['interessados']['sub']['interesse_cadastro']['subs']['View']['pag']		= Config::$PATH.'interessados/ficha';

		self::$STRUCTURE['gestao_usuarios']['label']										= 'Usuários';
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['label']						= 'Gestão de Usuários';
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['List']['label']		= "Busca de Usuários";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['View']['label']		= "Ficha de Usuário";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['Form']['label']		= "Cadastro de Usuários";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['List']['details']	= "Encontre um Usuário Cadastrado";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['View']['details']	= "Detalhes do Usuário Cadastrado";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['Form']['details']	= "Cadastre um Novo Usuário";
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['List']['pag']		= Config::$PATH.'usuarios/busca';
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['View']['pag']		= Config::$PATH.'usuarios/ficha';
		self::$STRUCTURE['gestao_usuarios']['sub']['usuarios']['subs']['Form']['pag']		= Config::$PATH.'usuarios/cadastro';

		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['label']						= 'Perfis de Acesso';
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['List']['label']		= "Busca de Perfis";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['View']['label']		= "Ficha do Perfil";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['Form']['label']		= "Cadastro de Perfil";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['List']['details']		= "Encontre um Perfil Cadastrado";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['View']['details']		= "Detalhes do Perfil Cadastrado";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['Form']['details']		= "Cadastre um Novo Perfl";
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['List']['pag']			= Config::$PATH.'perfis/busca';
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['View']['pag']			= Config::$PATH.'perfis/ficha';
		self::$STRUCTURE['gestao_usuarios']['sub']['perfis']['subs']['Form']['pag']			= Config::$PATH.'perfis/cadastro';

		self::$STRUCTURE['gestao_usuarios']['sub']['log']['label']							= 'Log';
		self::$STRUCTURE['gestao_usuarios']['sub']['log']['subs']['List']['label']			= 'Log';
		self::$STRUCTURE['gestao_usuarios']['sub']['log']['subs']['List']['details']		= 'Ações dos Usuários no Sistema';
		self::$STRUCTURE['gestao_usuarios']['sub']['log']['subs']['List']['pag']			= Config::$PATH.'log/busca';
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	function checkAreas(){
		foreach(self::$STRUCTURE as $area => $conf){
			if(!isset($conf['level']))	self::$STRUCTURE[$area]['level'] = 1;
			if(!isset($conf['pag']))	self::$STRUCTURE[$area]['pag'] = "#$area";
			if(!isset($conf['sub']))	self::$STRUCTURE[$area]['sub'] = array();

			if(isset($conf['pag'])){
				$ref_pag = explode(Config::$PATH, $conf['pag']);
				$ref = explode('/', $ref_pag[1]);
				if($ref[0] == "") $ref[0] = $area; 
			}else{
				$ref[0] = $area;
			}
			if($ref_pag[1] == "") $ref_pag[1] = $area; 
			self::$STRUCTURE[$area]['ref']	= $ref[0];
			self::$STRUCTURE[$area]['ref_path']	= $ref[0];//$ref_pag[1];

			foreach(self::$STRUCTURE[$area]['sub'] as $sub => $sub_conf){
				if(isset($sub_conf['subs'])){
					if(isset($sub_conf['subs']['Form'])){
						if(!isset($sub_conf['subs']['Form']['level'])){ self::$STRUCTURE[$area]['sub'][$sub]['subs']['Form']['level'] = 3;  }
					} 
					if(isset($sub_conf['subs']['View'])){
						if(!isset($sub_conf['subs']['Form']['level'])){ self::$STRUCTURE[$area]['sub'][$sub]['subs']['View']['level'] = 2;  }
					} 
					if(isset($sub_conf['subs']['List'])){
						if(!isset($sub_conf['subs']['Form']['level'])){ self::$STRUCTURE[$area]['sub'][$sub]['subs']['List']['level'] = 1;  }
					}
				}
				if(!isset($sub_conf['pag'])){
					if(isset($sub_conf['subs'])){
						if(isset($sub_conf['subs']['Form']['pag'])){
							$ref_pag = explode(Config::$PATH, $sub_conf['subs']['Form']['pag']);
							self::$STRUCTURE[$area]['sub'][$sub]['ref_path']	= $ref_pag[1];
							self::$STRUCTURE[$area]['sub'][$sub]['pag'] 	= $sub_conf['subs']['Form']['pag'];
							self::$STRUCTURE[$area]['sub'][$sub]['level'] 	= self::$STRUCTURE[$area]['sub'][$sub]['subs']['Form']['level'];
						}
						if(isset($sub_conf['subs']['View']['pag'])){
							$ref_pag = explode(Config::$PATH, $sub_conf['subs']['View']['pag']);
							self::$STRUCTURE[$area]['sub'][$sub]['ref_path']	= $ref_pag[1];
							self::$STRUCTURE[$area]['sub'][$sub]['pag'] 	= $sub_conf['subs']['View']['pag'];
							self::$STRUCTURE[$area]['sub'][$sub]['level'] 	= self::$STRUCTURE[$area]['sub'][$sub]['subs']['View']['level'];
						}
						if(isset($sub_conf['subs']['List']['pag'])){
							$ref_pag = explode(Config::$PATH, $sub_conf['subs']['List']['pag']);
							self::$STRUCTURE[$area]['sub'][$sub]['ref_path']	= $ref_pag[1];
							self::$STRUCTURE[$area]['sub'][$sub]['pag'] 		= $sub_conf['subs']['List']['pag'];
							self::$STRUCTURE[$area]['sub'][$sub]['level'] 		= self::$STRUCTURE[$area]['sub'][$sub]['subs']['List']['level'];
						}
					}else{
						$ref_pag = explode(Config::$PATH, self::$STRUCTURE[$area]['sub'][$sub]['pag']);
						self::$STRUCTURE[$area]['sub'][$sub]['ref_path']	= $ref_pag[1];
						self::$STRUCTURE[$area]['sub'][$sub]['pag'] = "#$sub";
					}
				}
				if(isset($sub_conf['subs'])){
					foreach($sub_conf['subs'] as $tipo => $detalhe){
						$ref_pag = explode(Config::$PATH, $detalhe['pag']);
						self::$STRUCTURE[$area]['sub'][$sub]['subs'][$tipo]['ref_path']	= $ref_pag[1];
					}
				}
				if(!isset(self::$STRUCTURE[$area]['sub'][$sub]['level'])){
					self::$STRUCTURE[$area]['sub'][$sub]['level'] = 1;
				}
				if(!isset($sub_conf['ref']) && isset($sub_conf['subs']['List']['pag'])){
					$ref_pag = explode(Config::$PATH, $sub_conf['subs']['List']['pag']);
					$ref = explode('/', $ref_pag[1]);
					self::$STRUCTURE[$area]['sub'][$sub]['ref'] = $ref[0];
					self::$STRUCTURE[$area]['sub'][$sub]['ref_path'] = $ref_pag[1];
				}
				if(isset($sub_conf['subs']['Form']['pag']) && !isset(self::$STRUCTURE[$area]['sub'][$sub]['ref']) ){
					$ref_pag = explode(Config::$PATH, $sub_conf['subs']['Form']['pag']);
					$ref = explode('/', $ref_pag[1]);
					self::$STRUCTURE[$area]['sub'][$sub]['ref'] = $ref[0];
					self::$STRUCTURE[$area]['sub'][$sub]['ref_path'] = $ref_pag[1];
				}
				if(isset($sub_conf['subs']['View']['pag']) && !isset(self::$STRUCTURE[$area]['sub'][$sub]['ref']) ){
					$ref_pag = explode(Config::$PATH, $sub_conf['subs']['View']['pag']);
					$ref = explode('/', $ref_pag[1]);
					self::$STRUCTURE[$area]['sub'][$sub]['ref'] = $ref[0];
					self::$STRUCTURE[$area]['sub'][$sub]['ref_path'] = $ref_pag[1];
				}
		}
		}
	//	Debug::print_r(self::$STRUCTURE);
	}
// ********************************************************************************************************************************
	public function getHtmlMail(){
		$html  = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
		$html .= "<html>";
		$html .= "<head>";
		$html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />";
		$html .= "<title>RUN - SENDMAIL</title>";
		$html .= "<style>";
		$html .= "p{ margin:0; padding:0; margin-bottom:5px;}";
		$html .= "</style>";
		$html .= "</head>";
		$html .= "<body bgcolor=\"#FFFFFF\" text=\"#777777\" link=\"#999999\" vlink=\"#999999\" alink=\"#999999\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
		$html .= '<table width="100%" height="10" bgcolor="#fbfbfb" border="0" align="center" cellpadding="5" cellspacing="0">';
		$html .= "<tr><td align='left' valign='middle'>";
		$html .= "<font face=\"Arial\" size=\"2\" style=\"font-size:12px;\">";
		$html .= '<img src="http://'.$_SERVER['HTTP_HOST']. Config::$PATH .'img/header/logo_adesao.png" alt="CMS" />';
		$html .= "</font>";
		$html .= "</td><td align='right' valign='bottom'>";
		$html .= "<font face=\"Arial\" size=\"1\" style=\"font-size:11px;\"></font>";
		$html .= "</td></tr>";
		$html .= "</table>";
		$html .= '<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">';
		$html .= "<tr><td><div style=' margin:5px;'>";
		$html .= "<font face=\"Arial\" size=\"2\" style=\"font-size:12px;\">";
		$html .= "[MENSAGEM]";
		$html .= "</font>";
		$html .= "</div></td></tr>";
		$html .= "</table>";
		$html .= "</body>";
		$html .= "</html>";
		self::$mailBodyHtml = $html;
	}
}
// ********************************************************************************************************************************
?>