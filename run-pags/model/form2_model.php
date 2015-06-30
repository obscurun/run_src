<?php
include_once(RUN_PATH."core/modelForm.php");
// ############################################################################################################################
class Form2Model extends modelForm{
	//*************************************************************************************************************************
	public function setSchema(){
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->settings					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"nick_name"					=> 'CadastroForm2',
			"form_id"					=> 'form2',
			"ref_page"					=> 'ref',
			"val_server"				=> true,
			"val_client"				=> true,
			"auto_save"					=> true,
			"auto_delete"				=> true,
			"permission_select" 		=> true,
			"permission_insert" 		=> true,
			"permission_update" 		=> true,
			"permission_delete" 		=> true,
			"redirect_insert"			=> "testes/form2/[pk_cadastro]".Run::$control->data->getQueryToString(),
			"encode_utf8" 				=> false,
			"decode_utf8" 				=> false,
			"check_token"				=> false,
			"paging_num"				=> 20
		);
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->schema					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"from"						=> array(
				array(
					"table"       		=> "form1_cadastros",
					"table_nick"  		=> "c",
					"pk"          		=> "pk_cadastro",
					"pk_del"      		=> "del_pk_cadastro",
					"save"        		=> true,
					"multiple"    		=> false
				)
			),
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"order"						=> "order_tables",
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"join"						=> array(
				array(
					"type"        		=> "left",
					"table"       		=> "form1_ufs",
					"table_nick"  		=> "u",
					"pk"          		=> "pk_uf",
					"pk_del"      		=> "del_pk_uf",
					"view"				=> false,
					"save"        		=> false,
					"multiple"    		=> false
				),
				array(
					"type"        		=> "left",
					"table"       		=> "form1_cores",
					"table_ref"   		=> "c",
					"pk"          		=> "pk_cor",
					"pk_del"      		=> "del_cor",
					"pk_ref"      		=> "pk_cadastro",
					"fk_ref"      		=> "fk_cadastro",
					"save"        		=> true,
					"multiple"    		=> true,
					"on"          		=> ""
				),
				array(
					"type"        		=> "left",
					"table"       		=> "form2_arquivos",
					"table_nick"  		=> "a",
					"table_ref"   		=> "c",
					"pk"          		=> "pk_arquivo",
					"pk_del"      		=> "del_arquivo",
					"pk_ref"      		=> "pk_cadastro",
					"fk_ref"      		=> "fk_cadastro",
					"save"        		=> true,
					"multiple"    		=> true,
					"on"          		=> ""
				)
			),
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"fields"					=> array(
				'pk_cadastro'			=> array(
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'nome_cadastro'			=> array(
					'name'				=> 'nome',
					'list'				=> true,
					'view'				=> true,
					'export'			=> false,
					'insert'			=> true,
					'update'			=> true,
					'type' 				=> 'string',
					'maxSize'			=> 10,
					'label'				=> 'Nome',
					'validation'		=> array(
						'required'		=> array(true, true, 'Escreva um nome.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'sobrenome'				=> array(
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Sobrenome',
					'listWidth'			=> '100',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Preencha o sobrenome.'),
						'maxcaracters'	=> array(10, true),
						'minwords'		=> array(2, true),
						'rangenumbers'	=> array(array(1, 3), true)
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'email'					=> array(
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'type' 				=> 'email',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Preencha o email.'),
						'email'			=> array(true, true)
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'senha'					=> array(
					'list'				=> false,
					'view'				=> false,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'type' 				=> 'string',
					'action'			=> 'md5',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Preencha a senha.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'observacoes'			=> array(
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'type' 				=> 'string',
					'validation1'		=> array(
						'maxcaracters'	=> array(400, true, 'O campo Nota permite no máximo 400 caracteres.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert'			=> array(
					'belongsTo'			=> 'c',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update'			=> array(
					'belongsTo'			=> 'c',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'status'				=> array(
					'belongsTo'			=> 'c',
					'update'			=> true,
					'label'				=> 'Status',
					'name' 				=> 'status',
					'type' 				=> 'int',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_cor'				=> array(
					'belongsTo'			=> 'form1_cores',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID - Cor',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_cadastro_c'			=> array(
					'name'				=> 'fk_cadastro',
					'belongsTo'			=> 'form1_cores',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'nome_cor'				=> array(
					'belongsTo'			=> 'form1_cores',
					'name'				=> 'nome',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Nome da Cor',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Preencha as cores.'),
						'minlength'		=> array(2, true),
						'maxlength'		=> array(4, true)
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert_cor'		=> array(
					'belongsTo'			=> 'form1_cores',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_cor'		=> array(
					'belongsTo'			=> 'form1_cores',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_arquivo'			=> array(
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_endereco_a'			=> array(
					'name'				=> 'fk_cadastro',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'arquivo'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'addSlashe'			=> true,
					'skipRecEmpty'		=> true,
					'maxLength'			=> 45,
					'type' 				=> 'file_name',
					'label'				=> 'ID',
					'listWidth'			=> '60',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'tipo_a'				=> array(
					'name'				=> 'tipo', //tipo repete em outra tabela enderecos_tipos
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'addSlashe'			=> true,
					'skipRecEmpty'		=> true,
					'type' 				=> 'file_type',
					'label'				=> 'ID',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'path'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'addSlashe'			=> true,
					'value'				=> 'default/', 
					'type' 				=> 'file_path',
					'label'				=> 'ID',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'tamanho'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'addSlashe'			=> true,
					'type' 				=> 'file_size',
					'label'				=> 'Tamanho do arquivo',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.'),
						'maxfilesize'	=> array(array(500, "KB"), true, 'O arquivo [name] é maior que o permitido')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'extensao'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'a',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> false,
					'addSlashe'			=> true,
					'type' 				=> 'file_extension',
					'label'				=> 'ID',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert_a'			=> array(
					'belongsTo'			=> 'a',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_a'			=> array(
					'belongsTo'			=> 'a',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'status_a'				=> array(
					'belongsTo'			=> 'a',
					'update'			=> true,
					'label'				=> 'Status',
					'name' 				=> 'status',
					'type' 				=> 'int',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			)
		);
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->schema_unions	=  array(
			// "all" => $this->schema,
			// "all1" => " select * from ".".form1_cadastros"
		);
	}
	//*************************************************************************************************************************
	function __construct(){
    	Run::$benchmark->mark("Form2Model/Inicio");
		parent::modelForm();
    	Run::$benchmark->writeMark("Form2Model/Inicio", "Form2Model/Final");
	}
	//*************************************************************************************************************************
	public function setRequest(){
	}
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
?>