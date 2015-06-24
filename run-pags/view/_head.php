<meta charset="UTF-8">
<title><? echo Run::TITLE . " - " . Run::$router->getTemplateData("title"); ?></title>
<meta http-equiv="Content-Type" 		content="text/html; charset=utf8" />
<meta name="Keywords"					content="PACI" />
<meta name="Description"				content="PACI" />
<meta name="robots" 					content="index, follow" />
<meta name="robots" 					content="noarchive" />
<meta http-equiv="Content-Language"	 	content="pt-br" />
<meta http-equiv="Cache-Control"		content="no-cache, no-store" />
<meta http-equiv="Pragma"				content="no-cache, no-store" />
<meta http-equiv="Expires" 				content="Wed, 4 Apr 1984 00:00:01 GMT" />
<meta name="viewport"                	content="width=device-width, initial-scale=1" />
<meta http-equiv="X-UA-Compatible"   	content="IE=edge" /> 
<link rel="stylesheet" href="<? echo Run::$router->path['src']; ?>css/<? echo Run::$control->string->upper(Run::NAME); ?>.css<? echo Run::$view->writeVersion(); ?>" media="screen" />
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->