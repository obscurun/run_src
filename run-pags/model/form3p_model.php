<?php
include_once(RUN_PATH."core/modelForm.php");
// ############################################################################################################################
class Form3pModel extends modelForm{
	//*************************************************************************************************************************
	public function setSchema(){
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->settings					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"nick_name"					=> 'Form3p',
			"form_id"					=> 'form3p',
			"ref_page"					=> 'ref',
			"val_server"				=> true,
			"val_client"				=> true,
			"auto_save"					=> true,
			"auto_delete"				=> true,
			"permission_select" 		=> true,
			"permission_insert" 		=> true,
			"permission_update" 		=> true,
			"permission_delete" 		=> true,
			"redirect_insert"			=> "testes/form3p/[id]",
			"encode_utf8" 				=> false,
			"decode_utf8" 				=> false,
			"check_token"				=> false,
			"database_id"				=> "postgre",
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