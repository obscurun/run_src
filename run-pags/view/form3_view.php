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
		<div class=" blc-content">
			<article class="col-lg-8">
            <? Run::$view->render->echoResponse(); ?>
            <? $this->model->errors->echoErrorsResponse(5, 5); ?>
			<div class="well bs-component">
                <form action="<? $this->model->aux->echoAction(); ?>" method="POST" enctype="multipart/form-data" onsubmit="return orderMultipleIndex(this);" class="form-horizontal">
                    <? $this->model->aux->echoBasicInputs(); ?>
                    <fieldset>
                        <legend>Dados Cadastrais</legend>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Nome</label>
                            <div class="col-lg-10">
                                <input type="text" value="<? $this->model->aux->echoData('nome_cadastro'); ?>" name="nome_cadastro" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Sobrenome</label>
                            <div class="col-lg-10">
                                <input type="text" value="<? $this->model->aux->echoData('sobrenome'); ?>" name="sobrenome" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Arquivo</label>
                            <div class="col-lg-10">
                                <? $this->model->aux->echoFile('arquivo', 'path', $this->model->aux->getData('pk_cadastro'), $this->model->aux->getData('pk_cadastro')); ?>
                                <input type="hidden" name="pk_arquivo" value="<? $this->model->aux->echoData('pk_arquivo', true); ?>" />
                                <input type="file"   name="arquivo" class="form-control" />
                            </div>
                        </div>


                    <hr style=" border-top-color:rgba(0,0,0,.3);" />
                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="submit" class="btn right btn-primary"><? $this->model->aux->echoBtLabel(); ?></button>
                            <div class="right">&nbsp;&nbsp;&nbsp;</div>
                            <? if($this->model->aux->getData('cleaned') == ""){ ?>
                            <a href="<? $this->model->aux->echoCleanForm(); ?>" type="button" class="btn right btn-default">Limpar Dados</a>
                            <? } else { ?>
                            <a href="<? $this->model->aux->echoRecoverForm(); ?>" type="button" class="btn right btn-default">Recuperar Dados Salvos</a>
                            <? } ?>
                        </div>
                    </div>
                </form>
            <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div>
            </div>
			</article>
		</div>
		

	</div>


	<? Run::$router->loadView("_scripts.php"); ?>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.gridster-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.mousewheel-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
	<script type="text/javascript" src="<? echo Run::$router->path['base']; ?>js/jquery.jscrollpane-apart.js<? echo Run::$view->writeVersion(); ?>"></script>
    <script>
        $(document).ready(function(){
            $("button.excluir").on({
                "click":function(){
                    console.log("excluindo");
                    $(this).parents("fieldset").eq(0).remove();
                }
            });
            $(".adicionar").bind("click", function(){
                conteudo_endereco = $("#model_endereco").html();
                $("#enderecos").append(conteudo_endereco);

                $("button.excluir").on({
                    "click":function(){
                        console.log("excluindo");
                        $(this).parents("fieldset").eq(0).remove();
                    }
                });
            });
            $(".deleteFile").bind("click", function(){
                console.log("removendo arquivo");
                $(this).parents(".form-control").eq(0).remove();
            });
        });
        function orderMultipleIndex(form){
            return;
            $(form).find("#enderecos").find('fieldset').each(function(n){
                $(this).find("input[name^='pk_endereco[]']").attr("name", "pk_endereco["+n+"]");
                $(this).find("input[name^='rua[]']").attr("name", "rua["+n+"]");
                $(this).find("select[name^='fk_uf[]']").attr("name", "fk_uf["+n+"]");
                $(this).find("input[name^='tipo[]']").attr("name", "tipo["+n+"][]");
                $(this).find("input[name^='pk_endereco_tipo[]']").attr("name", "pk_endereco_tipo["+n+"][]");
                $(this).find("input[name^='pk_arquivo[]']").attr("name", "pk_arquivo["+n+"][]");
                $(this).find("input[name^='arquivo[]']").attr("name", "arquivo["+n+"][]");
                $(this).find("input[name^='arquivo_ref[]']").attr("name", "arquivo_ref["+n+"][]");
                $(this).find("input[name^='status_a[]']").attr("name", "status_a["+n+"][]");
                // alert(n);
            });
            //return false;
        }
    </script>
	</body>
</html>