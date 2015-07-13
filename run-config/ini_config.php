<?php
/**
 *	@package Run 
 *  Config é usado como parametros para a classe Run, também pode ser usado para a aplicação
 *  Run extends Config
**/
//*******************************************************************************************************************************
class Config{
	const			 TITLE						= 'RUN';										// string	- título padrão usado no navegador
	const			 NAME						= 'run';										// string 	- nome simples - padrão do site usado em variáveis
	const			 VERSION					= '01984';										// string 	- caminho padrão no servidor
	const			 PATH_PAG					= "run-pags/";									// string 	- caminho padrão das páginas
	const		 	 FILES_BASE					= "run-files/";									// string 	- caminho padrão para upload de arquivos
	const			 VIEW_HTML_ENTITIES			= true;											// boolean 	- converter html normal para entities ao imprimir
	const		 	 ENCODING					= "utf8";										// string 	- encoding padrão do projeto: uft8, iso8859-1
	const			 TIMEZONE					= "America/Sao_Paulo";							// string 	- timezone para não dar erro em datas: date();
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	public static 	 $DEBUG_REC_LOG				= true;											// boolean 	- grava [data]*.log do acesso das páginas
	public static 	 $DEBUG_PRINT				= false;										// boolean 	- exibe Debug::p
	public static 	 $DEBUG_BENCHMARK			= false;										// boolean 	- marca microtime de processamento.
	public static 	 $ERROR_REC_LOG				= true;											// boolean 	- grava [data]*.log dos erros gerados
	public static 	 $ERROR_REC_VAR				= false;										// boolean 	- grava variaveis do ambiente no log
	public static 	 $ERROR_EXECUTION			= true;											// boolean 	- executa gerenciamento de erros
	public static 	 $ERROR_SEND_EMAIL			= false;										// boolean 	- envia e-mail para o administrador ao ocorrer erro
	public static 	 $ERROR_EMAIL				= "rafael.teixeira@sccon.com.br";				// string 	- e-mail que receberá os erros ocorridos no momento
	public static 	 $ERROR_SHOW_DEBUG			= true;											// boolean 	- exibe erros completos ao debugar/testar o projeto
	public static 	 $ERROR_SHOW_BACKTRACE		= true;											// boolean 	- exibe backtrace das funções executadas até o erro
	public static 	 $ERROR_SHOW_USER			= true;											// boolean 	- mostra erro pro usuário
	public static 	 $ERROR_SHOW_MSG_DEFAULT	= false;										// boolean 	- ao ocorrer erro, exibe mensagem padrão - sem o erro específico
	public static 	 $ERROR_SHOW_NOTICE			= false; 										// boolean 	- executa erros tipo notice
	public static 	 $ERROR_SHOW_WARNING		= false;										// boolean 	- executa erros tipo warning
	public static 	 $ERROR_LEVEL				= 9000;											// int 		- abaixo de [int], qual nivel deve apresentar erros na pág
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const		 	 ACTION_LOG					= false;										// boolean 	- registra as principais ações realizadas no banco de dados
	const		 	 ACTION_LOG_URL				= true;											// boolean 	- registra cada url acessada no banco de dados
	const		 	 ACTION_LOG_IS_ADMIN		= true;											// boolean 	- true para registrar logs de admin, false, registra de users
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const			 USE_ROUTER					= true;											// boolean 	- usa classe router para gerenciar páginas
	const			 ROUTER_MODE				= "METHODS";									// string 	- "METHODS" para metodos no Control ou "DATABASE" para usar BD
	const			 ROUTER_DEFAULT_PAGE		= 'home_view';									// string 	- indica qual página dentro da view é a default no load da app
	const			 ROUTER_START				= 'home';										// string 	- indica a url da página default na aplicação
	const			 ROUTER_FIXED_START			= true;											// boolean 	- indica se a url deve ter ROUTER_START fixa
	const			 ROUTER_FIXED_LANGUAGE		= true;											// boolean 	- indica se a url deve ter a sigla da ligua fixa
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const 	 		 LANGUAGE_DEFAULT			= "br";											// string 	- sigla / linguagem padrão usado no framework, para multiplas linguas
	const		 	 LANGUAGE_AUTO_LOAD_PHRASES	= true;											// boolean 	- true para carregar a propertie com lista de frases do idioma
	public static	 $LANGUAGES_AVAILABLE		= array("pt", "br", "eng");						// array 	- siglas de linguagens disponíveis no app
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const			 QUERY_PREFIX				= 'run_';										// string 	- prefix_ das querys dentro de models
	const			 QUERY_USE_PREFIX_TABLE		= true;											// boolean 	- usar a const DB nas querys caso true
	const			 QUERY_USE_PREFIX_SCHEMA	= false;										// boolean 	- usar o db/name nas querys caso true
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const			 POSTGRE					= true; 										// boolean 	- ATENÇÃO, FALSE PARA NAO USAR POSTGRE
	const			 MYSQL 						= true; 										// boolean 	- ATENÇÃO, FALSE PARA NAO USAR MYSQLI
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const 			 SESSION_TIMEOUT			= 1800; 										// int 		- [segundos] 300 = 5 minutos
	const 			 LOGIN_TIMEOUT				= 1800; 										// int 		- [segundos] 300 = 5 minutos
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const			 MAIL_TRY_SEND_SERVER		= true;											// boolean 	- tenta disparar e-mail pela função mail do servidor, caso SMTP falhe
	const			 MAIL_AUTO_SEND_LIMIT		= 5;											// boolean 	- limite do select para enviar em sequencia em periodicAutoSendMail
	const			 CRON_JOB_TIME				= 1;											// boolean 	- config no servidor em minutos, para auto execução periódica
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	const			 USE_LOGIN					= true;											// boolean 	- usa a classe login para gerenciar acesso
	const			 LOGIN_TABLE				= "admins"; 									// string 	- tabela de usuarios usada
	const			 LOGIN_PK					= "pk_admin"; 									// string 	- coluna primary key da tabela
	const			 LOGIN_FIELD_LOGIN			= "email"; 										// string 	- coluna usada no login
	const			 LOGIN_FIELD_PASS			= "senha"; 										// string 	- coluna usada na senha
	const			 LOGIN_CRYPTOGRAPHY			= "md5"; 										// string 	- criptografia usada na aplicação
	const			 LOGIN_FORM					= "FORM_LOGIN"; 								// string 	- form id para controle de erros
	const			 LOGIN_USE_CREDENTIALS		= false; 										// boolean 	- usa tabela credenciais no login
	public static	 $LOGIN_FIELDS_EXTRAS		= false; 										// boolean 	- 
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  
	public static	 $PROPERTIES_SESSION_LOAD	= array();										// array 	- carrega properties automaticamente / array("arquivo") *.prop
//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
//*******************************************************************************************************************************
	function onStartConfig(){
		switch($_SERVER['SERVER_NAME']){
			case "www.rafaelteixeira.com":
									 //type			//id 				//host 				//database 			//schema		//user 			//password
				Model::setConnection('mysql',		'default',			'localhost',		'rafael_run',		'', 			'rafael_run',	'dev123'		);
				break;
			default:
									 //type			//id 				//host 				//database 			//schema		//user 			//password
				Model::setConnection('mysql',		'default',			'localhost',		'run',				'', 			'root', 		'dev123'		);
				//Model::setConnection('mysql',		'runb',				'localhost',		'runb',				'', 			'root',			'dev123'		);
				Model::setConnection('postgre',		'postgre',			'localhost',		'run',				'run', 			'postgres',		'dev'			);
				Model::setConnection('postgre',		'postgre_form1',	'localhost',		'run_form1',		'public', 		'postgres',		'dev'			);
				break;
		}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		Debug::log("Finalizando configuração.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
}
//*******************************************************************************************************************************

?>