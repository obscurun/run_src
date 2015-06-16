<?php 
/**
 *	@package Run 
 *  A aplicação começa no index.php > { ini_config.php / ini_template.php /  ini_run.php } > run_pags > view > home_aplication
 *  A classe Run inicia a aplicação carregando automaticamente classes primárias: control, session... entre outras 
 *  Atenção, não imprima nada no index nem neste arquivo, ele apenas inicia a classe Run, usando os parametros setados em Config
**/
//********************************************************************************************************************************
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	$time_start = microtime(true);
	define('APP_PATH', 		(realpath(dirname(__FILE__)."")."/../") );
	define('RUN_PATH', 		(realpath(dirname(__FILE__)."/../")."/run/") );
	define('CONFIG_PATH', 	(APP_PATH.'run-config/' 	));
	define('PAGS_PATH', 	(APP_PATH.'/run-pags/' 		));
	define('FILES_PATH', 	(APP_PATH.'/run-files/'		));
	define('LOGS_PATH', 	(APP_PATH.'/run-logs/' 		));
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	require(APP_PATH.'/run-config/ini_config.php');
	//require(APP_PATH.'/run-config/ini_template.php');
	require(RUN_PATH.'run.php');
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	Debug::log("\r\n\t\tRUNNING APP <b>". Config::NAME ."</b>\r\n\t", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
//********************************************************************************************************************************

	$run = new Run();	// iniciando de verdade
	if(Run::$DEBUG_BENCHMARK){
		$time_end = microtime(true);
		echo "<script> if(console.debug) console.debug('RUN Benchmark: Total Time: ".number_format($time_end - $time_start, 4)."') </script>";
	}
	exit;
//********************************************************************************************************************************
	if(Run::$ERROR_SHOW_DEBUG){
		Debug::log("Backtrace:".Debug::getBacktrace(), __LINE__, __FUNCTION__, __CLASS__, __FILE__);
  		//Debug::print_r(getallheaders());
		Debug::showLog();
	}
//********************************************************************************************************************************
	exit;
?>