<!DOCTYPE html>
<html>
	<head>
		<? Run::$router->loadView("_head.php"); ?>
        <style>
        .radio input{
            margin-right: 10px;
            text-indent: -10px;
            line-height: -10px;
        }
        button[type=reset]{
            margin-left: 20px;
            margin-right: 20px;
        }
        </style>
	</head>
	<body>
	<div class="container">
        <br clear="all" />
        <h1>Listagem dos Registros</h1>
		<div class=" blc-content">
            <? $this->model->list->echoTable(); ?>
		</div>		

	</div>
	<? Run::$router->loadView("_scripts.php"); ?>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.gridster-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.mousewheel-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.jscrollpane-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
	<script>
    $(".col_pk_cadastro").hover(
        function(){ $(this).parent("tr").find(".infos_extras").show() },
        function(){ $(this).parent("tr").find(".infos_extras").hide() }
    );
    </script>
    </body>
</html>