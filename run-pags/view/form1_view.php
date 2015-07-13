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
            <? Run::$view->render->echoResponse(); //Run::$DEBUG_PRINT = 1; Debug::p(Run::$router->path); ?>
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
                            <label class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" value="<? $this->model->aux->echoData('email'); ?>" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" value="<? $this->model->aux->echoData('senha'); ?>" name="senha" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Observações</label>
                            <div class="col-lg-10">
                                <textarea name="observacoes" class="form-control" rows="3"><? $this->model->aux->echoData('observacoes'); ?></textarea>
                            </div>
                        </div>



                        <div class="form-group hide">
                            <label class="col-lg-2 control-label">Arquivos</label>
                            <div class="col-lg-10">
                                <input type="hidden" name="pk_arquivo[]" value="<? $this->model->aux->echoData('pk_arquivo'); ?>" />
                                <input type="file" size="10" size="10" name="arquivo[]" class="form-control" />
                                <input type="hidden" name="status_a[]" value="80" />
                                <input type="hidden" name="pk_arquivo[]" />
                                <input type="file" size="10" size="10"   name="arquivo[]" class="form-control" />
                                <input type="hidden" name="status_a[]" value="80" />
                                <input type="hidden" name="pk_arquivo[]" />
                                <input type="hidden" name="status_a[]" value="80" />
                                <input type="file" size="10" size="10"   name="arquivo[]" class="form-control" />
                            </div>
                        </div>

                    <fieldset>
                        <legend>Cores</legend>

                        <? foreach($this->model->aux->getData('pk_cor') as $k => $val){ ?>
                        <? } ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Cores</label>
                            <div class="col-lg-10">
                                <div class="radio row">
                                    <label>
                                        <input type="checkbox" <? $this->model->aux->checkData('nome_cor', 'Azul'); ?> name="nome_cor[0]" value="Azul">Azul
                                        <input type="hidden" name="pk_cor[0]" value="<? $this->model->aux->echoPkData('pk_cor', 'nome_cor', 'Azul', 0); ?>" />
                                    </label>
                                </div>
                                <div class="radio row">
                                    <label>
                                        <input type="checkbox" <? $this->model->aux->checkData('nome_cor', 'vermelho'); ?> name="nome_cor[1]" value="vermelho">Vermelho
                                        <input type="hidden" name="pk_cor[1]" value="<? $this->model->aux->echoPkData('pk_cor', 'nome_cor', 'vermelho', 1); ?>" />
                                    </label>
                                </div>
                                <div class="radio row">
                                    <label>
                                        <input type="checkbox" <? $this->model->aux->checkData('nome_cor', 'Verde'); ?> name="nome_cor[2]" value="Verde">Verde
                                        <input type="hidden" name="pk_cor[2]" value="<? $this->model->aux->echoPkData('pk_cor', 'nome_cor', 'Verde', 2); ?>" />
                                    </label>
                                </div>
                                <div class="radio row">
                                    <label>
                                        <input type="checkbox" <? $this->model->aux->checkData('nome_cor', 'Laranja'); ?> name="nome_cor[3]" value="Laranja">Laranja
                                        <input type="hidden" name="pk_cor[3]" value="<? $this->model->aux->echoPkData('pk_cor', 'nome_cor', 'Laranja', 3); ?>" />
                                    </label>
                                </div>
                                <div class="radio row">
                                    <label>
                                        <input type="checkbox" <? $this->model->aux->checkData('nome_cor', 'Ciano'); ?> name="nome_cor[4]" value="Ciano">Ciano
                                        <input type="hidden" name="pk_cor[4]" value="<? $this->model->aux->echoPkData('pk_cor', 'nome_cor', 'Ciano', 4); ?>" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="enderecos">
                          <legend>Endereços </legend>


                          <? foreach($this->model->aux->getData('pk_endereco') as $k => $val){ ?>
                                <fieldset>
                                    <input type="hidden" name="pk_endereco[]" value="<? $this->model->aux->echoData('pk_endereco', true, $k); ?>" />
                                    <div class="enderecos">
                                       <legend style="margin-top:10px; font-size:120%;">Novo Endereço <button type="button" class="btn excluir right btn-xs btn-danger">X</button> </legend>

                                      <div class="endereco">
                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Endereço</label>
                                          <div class="col-lg-10">
                                            <input type="text" name="rua[]" value="<? $this->model->aux->echoData('rua', true, $k); ?>" class="form-control" placeholder="Endereço">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label for="select" class="col-lg-2 control-label">Estado</label>
                                          <div class="col-lg-10">
                                            <select name="fk_uf[]" class="form-control" id="select">
                                              <?  $this->model->aux->selectData('fk_uf', false, true, $k); ?>
                                            </select>
                                          </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Arquivos</label>
                                            <div class="col-lg-10">
                                            <? 
                                                //Debug::p($this->model->aux->getData('pk_arquivo'));
                                                foreach($this->model->aux->getData('pk_arquivo', $k) as $k_a => $val_a){
                                                    if($this->model->aux->checkFileEmpty('arquivo', $k, $k_a)){
                                            ?>
                                                <div>
                                                    <div class="form-control">
                                                        <? $this->model->aux->echoFile('arquivo', 'path', $this->model->aux->getData('pk_endereco', $k), $this->model->aux->getData('pk_arquivo', $k, $k_a), $k, $k_a); ?>
                                                        <input type="hidden" name="pk_arquivo[][]" value="<? $this->model->aux->echoData('pk_arquivo', true, $k, $k_a); ?>" />
                                                        <input type="hidden" name="status_a[][]" value="95" />
                                                    </div>
                                                </div>
                                            <?  
                                                    }
                                                }
                                            ?>
                                                <input type="hidden" name="pk_arquivo[][]" />
                                                <input type="hidden" name="status_a[][]" value="99" />
                                                <input type="file" size="10"  name="arquivo[][]" class="form-control" />
                                                <input type="hidden" name="pk_arquivo[][]" />
                                                <input type="hidden" name="status_a[][]" value="99" />
                                                <input type="file" size="10"  name="arquivo[][]" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                          <label class="col-lg-2 control-label">Tipos</label>
                                          <div class="col-lg-10">
                                            <div class="radio row">
                                              <label>
                                                <input type="checkbox" name="tipo[][0]" <? $this->model->aux->checkData('tipo', 'Residencial', $k); ?> value="Residencial">
                                        <input type="hidden" name="pk_endereco_tipo[][0]" value="<? $this->model->aux->echoPkData('pk_endereco_tipo', 'tipo', 'Residencial', $k, 0); ?>" />
                                                Residencial
                                              </label>
                                            </div>
                                            <div class="radio row">
                                              <label>
                                                <input type="checkbox" name="tipo[][1]" <? $this->model->aux->checkData('tipo', 'Comercial', $k); ?> value="Comercial">
                                        <input type="hidden" name="pk_endereco_tipo[][1]" value="<? $this->model->aux->echoPkData('pk_endereco_tipo', 'tipo', 'Comercial', $k, 1); ?>" />
                                                Comercial
                                              </label>
                                            </div>
                                            <div class="radio row">
                                              <label>
                                                <input type="checkbox" name="tipo[][2]" <? $this->model->aux->checkData('tipo', 'Veraneio', $k); ?> value="Veraneio">
                                        <input type="hidden" name="pk_endereco_tipo[][2]" value="<? $this->model->aux->echoPkData('pk_endereco_tipo', 'tipo', 'Veraneio', $k, 2); ?>" />
                                                Veraneio
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </fieldset>
                          <? } ?>



                    </fieldset>


                    <hr style=" border-top-color:rgba(0,0,0,.3);" />
                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="button" class="btn adicionar left btn-warning">Adicionar</button>
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



    <div id="model_endereco" class="hide">
        <fieldset>
            <input type="hidden" name="pk_endereco[]" />
            <div class="enderecos">
               <legend style="margin-top:10px; font-size:120%;">Novo Endereço <button type="button" class="btn excluir right btn-xs btn-danger">X</button> </legend>

              <div class="endereco">
                <div class="form-group">
                  <label class="col-lg-2 control-label">Endereço</label>
                  <div class="col-lg-10">
                    <input type="text" name="rua[]" class="form-control" placeholder="Endereço">
                  </div>
                </div>
                <div class="form-group">
                  <label for="select" class="col-lg-2 control-label">Estado</label>
                  <div class="col-lg-10">
                    <select name="fk_uf[]" class="form-control" id="select">
                      <option value="1">SP</option>
                      <option value="3">MG</option>
                      <option value="2">RJ</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label">Arquivos</label>
                    <div class="col-lg-10">
                        <input type="hidden" name="pk_arquivo[][]" value="0" />
                        <input type="file" size="10"  name="arquivo[][]" class="form-control" />
                        <input type="hidden" name="status_a[][]" value="90" />
                        <input type="hidden" name="pk_arquivo[][]" value="0" />
                        <input type="file" size="10"  name="arquivo[][]" class="form-control" />
                        <input type="hidden" name="status_a[][]" value="90" />
                        <input type="hidden" name="pk_arquivo[][]" />
                        <input type="hidden" name="status_a[][]" value="90" />
                        <input type="file" size="10"  name="arquivo[][]" value="0" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                  <label class="col-lg-2 control-label">Tipos</label>
                  <div class="col-lg-10">
                    <div class="radio row">
                      <label>
                        <input type="checkbox" name="tipo[][]" value="Residencial">
                        <input type="hidden"  size="10" name="pk_endereco_tipo[][]" />
                        Residencial
                      </label>
                    </div>
                    <div class="radio row">
                      <label>
                        <input type="checkbox" name="tipo[][]" value="Comercial">
                        <input type="hidden"  size="10" name="pk_endereco_tipo[][]" />
                        Comercial
                      </label>
                    </div>
                    <div class="radio row">
                      <label>
                        <input type="checkbox" name="tipo[][]" value="Veraneio">
                        <input type="hidden"  size="10" name="pk_endereco_tipo[][]" />
                        Veraneio
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </fieldset>
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