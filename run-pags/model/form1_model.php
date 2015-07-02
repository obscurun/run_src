<?php
include_once(RUN_PATH."core/modelForm.php");
// ############################################################################################################################
class Form1Model extends modelForm{
	//*************************************************************************************************************************
	public function setSchema(){
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		$this->settings					=  array(
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"nick_name"					=> 'Cadastro',
			"form_id"					=> 'form1',
			"ref_page"					=> 'ref',
			"val_server"				=> true,
			"val_client"				=> true,
			"auto_save"					=> true,
			"auto_delete"				=> true,
			"permission_select" 		=> true,
			"permission_insert" 		=> true,
			"permission_update" 		=> true,
			"permission_delete" 		=> true,
			"redirect_insert"			=> "testes/form1/[pk_cadastro]/[nome]",
			"encode_utf8" 				=> false,
			"decode_utf8" 				=> false,
			"check_token"				=> true,
			//"list_mode"					=> "unique",
			"paging_num"				=> 10
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
			"order"						=> "c.date_update DESC, order_tables",
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"join"						=> array(
				array(
					"type"        		=> "left",
					"table"       		=> "form1_ufs",
					"table_nick"  		=> "u",
					"pk"          		=> "pk_uf",
					"pk_del"      		=> "del_pk_uf",
					"list"				=> false,
					"view"				=> false,
					"save"        		=> false,
					"multiple"    		=> false
				),
				array(
					"type"        		=> "left",
					"table"       		=> "form1_enderecos",
					"table_nick"  		=> "e",
					"table_ref"   		=> "c",
					"pk"          		=> "pk_endereco",
					"pk_del"      		=> "del_endereco",
					"pk_ref"      		=> "pk_cadastro",
					"fk_ref"      		=> "fk_cadastro",
					"save"        		=> true,
					"multiple"    		=> true,
					"on"          		=> "",
					"list"				=> true,
					"listAsColumn"		=> true
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
					"table"       		=> "form1_enderecos_tipos",
					"table_nick"  		=> "et",
					"table_ref"   		=> "e",
					"pk"          		=> "pk_endereco_tipo",
					"pk_del"      		=> "del_endereco_tipo",
					"pk_ref"      		=> "pk_endereco",
					"fk_ref"      		=> "fk_endereco",
					"save"        		=> true,
					"multiple"    		=> true,
					"on"          		=> ""
				),
				array(
					"type"        		=> "left",
					"table"       		=> "form1_arquivos",
					"table_nick"  		=> "a",
					"table_ref"   		=> "e",
					"pk"          		=> "pk_arquivo",
					"pk_del"      		=> "del_arquivo",
					"pk_ref"      		=> "pk_endereco",
					"fk_ref"      		=> "fk_endereco",
					"list"        		=> true,
					"list_fields"  		=> false,
					"save"        		=> true,
					"multiple"    		=> true,
					"on"          		=> ""
				)
			),
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"group_list"				=> "pk_cadastro",
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
			"fields"					=> array(
				'pk_cadastro'			=> array(
					'list'				=> true,
					'listAsColumn'		=> true,
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
					'listWidth'			=> '150',
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
					'listWidth'			=> '150',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Preencha o sobrenome.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'email'					=> array(
					'view'				=> true,
					'list'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'type' 				=> 'email',
					'listWidth'			=> '150',
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
					'update'			=> true,
					'type' 				=> 'string',
					'skipFieldEmpty'	=> true,
					'convertValue'		=> 'sha1',
					'validation'		=> array(
						'required'		=> array(true, true, 'Preencha a senha.'),
						'useSentValue'  => true,
						'maxcaracters'	=> array(10, true),
						'rangenumbers'	=> array(array(1, 3), true)
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
				'status'				=> array(
					'belongsTo'			=> 'c',
					'update'			=> true,
					'label'				=> 'Status',
					'name' 				=> 'status',
					'type' 				=> 'int',
					'list' 				=> true,
					'listOrder'			=> '23s'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_arquivo'			=> array(
					'belongsTo'			=> 'a',
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID Arquivo',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_endereco_a'			=> array(
					'name'				=> 'fk_endereco',
					'belongsTo'			=> 'a',
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID Ref / Endereço',
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
					'label'				=> 'Tipo',
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
						'maxfilesize'	=> array(array(1, "MB"), true)
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
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_a'			=> array(
					'belongsTo'			=> 'a',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'status_a'				=> array(
					'belongsTo'			=> 'a',
					'update'			=> true,
					'label'				=> 'Status',
					'name' 				=> 'status',
					'type' 				=> 'int',
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_endereco'			=> array(
					'belongsTo'			=> 'e',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_cadastro_e'			=> array(
					'name'				=> 'fk_cadastro',
					'belongsTo'			=> 'e',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'rua'					=> array(
					'belongsTo'			=> 'e',
					'listOrder'			=> 23,
					'list'				=> true,
					'listAsColumn'		=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Rua',
					'listWidth'			=> '60',
					'validation'		=> array(
						'required'		=> array(true, true, 'Insira uma rua.')
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_uf'					=> array(
					'belongsTo'			=> 'e',
					'name'				=> 'fk_uf',
					'listOrder'			=> '23a',
					'list'				=> true,
					'listAsColumn'		=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'type' 				=> 'int',
					'label'				=> 'UF',
					'listWidth'			=> '60',
					'labelEmpty'		=> "Não preenchido",
					'labelList'			=> array(
									"" 	=> "Escolha uma opção",
									"1" => "SP",
									"3" => "MG",
									"2" => "RJ"
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert_e'			=> array(
					'belongsTo'			=> 'e',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_e'			=> array(
					'belongsTo'			=> 'e',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'status_e'				=> array(
					'belongsTo'			=> 'e',
					'listOrder'			=> '23b',
					'update'			=> true,
					'label'				=> 'Status',
					'name' 				=> 'status',
					'type' 				=> 'int',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_endereco_tipo'		=> array(
					'belongsTo'			=> 'form1_enderecos_tipos',
					'list'				=> true,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'label'				=> 'ID - End',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'fk_cadastro_et'		=> array(
					'name'				=> 'fk_endereco',
					'belongsTo'			=> 'et',
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'tipo'					=> array(
					'belongsTo'			=> 'et',
					'name'				=> 'tipo',
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Tipo de residência',
					'listWidth'			=> '60',
					'validation1'		=> array(
						'required'		=> array(true, true, 'Preencha as cores.'),
						'minlength'		=> array(2, true),
						'maxlength'		=> array(4, true)
					)
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_insert_et'		=> array(
					'belongsTo'			=> 'et',
					'type' 				=> 'date_insert',
					'name' 				=> 'date_insert',
					'update'			=> false,
					'label'				=> 'Data de Inserção',
					'list' 				=> false
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_et'		=> array(
					'belongsTo'			=> 'et',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
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
					'list'				=> false,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'type' 				=> 'int',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'nome_cor'				=> array(
					'belongsTo'			=> 'form1_cores',
					'name'				=> 'nome',
					'list'				=> true,
					'listOrder'			=> "23g",
					'listAsColumn'		=> true,
					'listWidth'			=> 150,
					'view'				=> true,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'type' 				=> 'string',
					'label'				=> 'Nome da Cor',
					'listLabel'			=> 'Cores selecionadas',
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
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'date_update_cor'		=> array(
					'belongsTo'			=> 'form1_cores',
					'type' 				=> 'date_update',
					'name' 				=> 'date_update',
					'update'			=> true,
					'label'				=> 'Data de Atualização',
					'list' 				=> true
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'pk_uf'					=> array(
					'belongsTo'			=> 'u',
					'list'				=> true,
					'view'				=> false,
					'export'			=> true,
					'insert'			=> false,
					'update'			=> false,
					'sqlSelect'			=> 'DISTINCT()',
					'type' 				=> 'int',
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'nome_uf'				=> array(
					'belongsTo'			=> 'u',
					'name' 				=> 'nome',
					'type' 				=> 'string',
					'label'				=> 'Nome',
					'list'				=> true,
					'view'				=> false,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'listWidth'			=> '60'
				),
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				'uf'					=> array(
					'belongsTo'			=> 'u',
					'type' 				=> 'string',
					'label'				=> 'UF',
					'list'				=> true,
					'view'				=> false,
					'export'			=> true,
					'insert'			=> true,
					'update'			=> true,
					'addSlashe'			=> true,
					'listWidth'			=> '60'
				)
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
    	Run::$benchmark->mark("Form1Model/Inicio");
		parent::modelForm();
    	Run::$benchmark->writeMark("Form1Model/Inicio", "Form1Model/Final");
	}
	//*************************************************************************************************************************
	public function setRequest(){
	}
	//*************************************************************************************************************************
	public function exeBeforeList(){
		$this->selectData->addOnWhere(" c.nome like '%%' ");
	}
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
?>