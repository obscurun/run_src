<?php
include_once(RUN_PATH."core/modelForm.php");
// ############################################################################################################################
class Form3bModel extends modelForm{
	//*************************************************************************************************************************
	public function setSchema(){
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->settings					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"nick_name"					=> 'Form3b',
			"form_id"					=> 'form3b',
			"ref_page"					=> 'ref',
			"val_server"				=> true,
			"val_client"				=> true,
			"auto_save"					=> true,
			"auto_delete"				=> true,
			"permission_select" 		=> true,
			"permission_insert" 		=> true,
			"permission_update" 		=> true,
			"permission_delete" 		=> true,
			"redirect_insert"			=> "testes/form3b/[id]/".Run::$control->data->getQueryToString(),
			"encode_utf8" 				=> false,
			"decode_utf8" 				=> false,
			"check_token"				=> true,
			"database_id"				=> "runb",
			"paging_num"				=> 20
		);
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->schema					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"from"						=> array(
				array(
					"table"       		=> Run::QUERY_PREFIX."form3_cadastros",
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
			"fields"					=> array(
				'pk_cadastro'			=> array(
					'list'				=> true,
					'select'			=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID',
					'size'				=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'nome_cadastro'			=> array(
					'name'				=> 'nome',
					'list'				=> false,
					'select'			=> true,
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
					'select'			=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Sobrenome',
					'value'				=> 'pad def2',
					'size'				=> '100',
					'validation'		=> array(
						'required'		=> array(true, true, 'Preencha o sobrenome.'),
						'maxcaracters'	=> array(10, true),
						'minwords'		=> array(2, true),
						'rangenumbers'	=> array(array(1, 3), true)
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert'			=> array(
					'belongsTo'			=> 'c',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update'			=> array(
					'belongsTo'			=> 'c',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'arquivo'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'c',
					'list'				=> true,
					'select'			=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'skipFieldEmpty'	=> true,
					'maxLength'			=> 45,
					'type' 				=> 'file_name',
					'label'				=> 'Arquivo/Nome',
					'size'				=> '60',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.'),
						'filesize'		=> array(array(1014, "MB"), true, 'Arquivo maior que o permitido')	
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'size'					=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'c',
					'list'				=> false,
					'select'			=> false,
					'export'			=> false,
					'insert'			=> false,
					'update'			=> false,
					'addSlashe'			=> true,
					'skipFieldEmpty'	=> true,
					'maxLength'			=> 45,
					'type' 				=> 'file_size',
					'label'				=> 'Tamanho do arquivo',
					'size'				=> '60',
					'validation'		=> array(
						'maxfilesize'	=> array(array(1, "MB"), true)	
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'tipo_a'				=> array(
					'name'				=> 'tipo', //tipo repete em outra tabela enderecos_tipos
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'c',
					'list'				=> true,
					'select'			=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'skipFieldEmpty'	=> true,
					'type' 				=> 'file_type',
					'label'				=> 'Arquivo/Type',
					'size'				=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'path'				=> array(
					'fieldRef'			=> 'arquivo',
					'belongsTo'			=> 'c',
					'list'				=> true,
					'select'			=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'skipFieldEmpty'	=> true,
					'value'				=> 'default/', 
					'type' 				=> 'file_path',
					'label'				=> 'Arquivo/Path',
					'size'				=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira um arquivo.')
					)
				)
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			)
		);
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->schema_unions	=  array(
			// "all" => $this->schema,
			// "all1" => " select * from ".Run::QUERY_PREFIX.".form1_cadastros"
		);
	}
	//*************************************************************************************************************************
	function __construct(){
    	Run::$benchmark->mark("Form3Model/Inicio");
		parent::modelForm();
    	Run::$benchmark->writeMark("Form3Model/Inicio", "Form3Model/Final");
	}
	//*************************************************************************************************************************
	public function setRequest(){
	}
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
?>