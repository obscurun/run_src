<?php
// ****************************************************************************************************************************
class check{
	//*************************************************************************************************************************
	function __construct(){
		Debug::log("Iniciando Core/Form/checkSettings.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
//-----------------------------------------------------------------------------------------------------------------------------
	public function checkModel($schema, $settings){
		$settings = $this->checkSettings($settings);
		return $this->checkSchema($schema, $settings);
	} 
//-----------------------------------------------------------------------------------------------------------------------------
	private function checkSettings($settings){
		Debug::log("FormModel->checksettings:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$query_prefix = (Run::QUERY_USE_PREFIX_TABLE != false) ? Run::QUERY_PREFIX : "";
		if(!array_key_exists('ref',						$settings) || $settings['ref'] == ""){ $settings['ref'] = "ref";	}
		if(!array_key_exists('nick_name',				$settings)){ $settings['nick_name']				= "Registro";		}
		if(!array_key_exists('debug',					$settings)){ $settings['debug'] 				= false;			}
		if(!array_key_exists('list_mode',				$settings)){ $settings['list_mode']				= "multiple";		}
		if(!array_key_exists('unique_index',			$settings)){ $settings['unique_index'] 			= false;			}
		if(!array_key_exists('val_server',				$settings)){ $settings['val_server'] 			= true;				}
		if(!array_key_exists('val_client',				$settings)){ $settings['val_client'] 			= true;				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('auto_save',				$settings)){ $settings['auto_save'] 			= true;				}
		if(!array_key_exists('auto_select',				$settings)){ $settings['auto_select'] 			= true;				}
		if(!array_key_exists('auto_delete',				$settings)){ $settings['auto_delete'] 			= true;				}
		if(!array_key_exists('permission_select',		$settings)){ $settings['permission_select'] 	= true;				}
		if(!array_key_exists('permission_update',		$settings)){ $settings['permission_update'] 	= true;				}
		if(!array_key_exists('permission_insert',		$settings)){ $settings['permission_insert']		= true;				}
		if(!array_key_exists('permission_delete',		$settings)){ $settings['permission_delete']		= false;			}		
		if(!array_key_exists('permission_export',		$settings)){ $settings['permission_export']		= true;				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('encode_utf8',				$settings)){ $settings['encode_utf8'] 			= false;			}
		if(!array_key_exists('encode_iso',				$settings)){ $settings['encode_iso'] 			= false;			}
		if(!array_key_exists('encoding',				$settings)){ $settings['encoding'] 				= true;				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('form_id',					$settings)){ $settings['form_id']				= "form_default";	}
		if(!array_key_exists('form_max_size',			$settings)){ $settings['form_max_size']			= "30";				} // VALOR EM MB
		if(!array_key_exists('prefix_page',				$settings)){ $settings['prefix_page']			= "";				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('database_id',				$settings)){ $settings['database_id']			= false;			}
		if(!array_key_exists('sql_table_prefix',		$settings)){ $settings['sql_table_prefix']		= $query_prefix;	}		
		if(!array_key_exists('sql_from',				$settings)){ $settings['sql_from']				= false;			}
		if(!array_key_exists('sql_where',				$settings)){ $settings['sql_where']				= false;			}
		if(!array_key_exists('sql_limit',				$settings)){ $settings['sql_limit']				= array();			}
		if(!array_key_exists('sql_orderby',				$settings)){ $settings['sql_orderby']			= false;			}
		if(!array_key_exists('sql_groupby',				$settings)){ $settings['sql_groupby']			= false;			}
		if(!array_key_exists('sql_having',				$settings)){ $settings['sql_having']			= false;			}
		if(!array_key_exists('sql_groupby_total',		$settings)){ $settings['sql_groupby_total']		= false;			}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('select_groupby',			$settings)){ $settings['select_groupby']		= false;			}
		if(!array_key_exists('select_tabulated',		$settings)){ $settings['select_tabulated']		= false;			}
		if(!array_key_exists('select_recursive',		$settings)){ $settings['select_recursive']		= false;			}
		if(!array_key_exists('select_use_status',		$settings)){ $settings['select_use_status']		= true;				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('paging_num_registers',	$settings)){ $settings['paging_num_registers']	= 15;				}
		if(!array_key_exists('paging_param_ref',		$settings)){ $settings['paging_param_ref']		= 0;				}
		if(!array_key_exists('paging_items',			$settings)){ $settings['paging_items']			= 5;				}
		if(!array_key_exists('paging_ref',				$settings)){ $settings['paging_ref']			= '';				}
		if(!array_key_exists('paging_index',			$settings)){ $settings['paging_index']			= false;			}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('use_url_ref',				$settings)){ $settings['use_url_ref']			= true;				}
		if(!array_key_exists('redirect',				$settings)){ $settings['redirect']				= "back";			}
		if(!array_key_exists('redirect_insert',			$settings)){ $settings['redirect_insert']		= $settings['redirect'];			}
		if(!array_key_exists('redirect_update',			$settings)){ $settings['redirect_update']		= $settings['redirect_insert'];			}
		if(!array_key_exists('redirect_delete',			$settings)){ $settings['redirect_delete']		= $settings['redirect'];			}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('list_edit_show',			$settings)){ $settings['list_edit_show']		= true;				}
		if(!array_key_exists('list_edit_label',			$settings)){ $settings['list_edit_label']		= "Ações";			}
		if(!array_key_exists('order_fields_index',		$settings)){ $settings['order_fields_index']	= 20;				}
		if(!array_key_exists('check_schema',			$settings)){ $settings['check_schema']			= false;			}
		if(!array_key_exists('default_pad',				$settings)){ $settings['default_pad']			= 6;				}
		if(!array_key_exists('client_extras',			$settings)){ $settings['client_extras']			= '';				}
		if(!array_key_exists('export_upper',			$settings)){ $settings['export_upper']			= false;			}
		if(!array_key_exists('check_token',				$settings)){ $settings['check_token']			= true;				}
		if(!array_key_exists('content_length',			$settings)){ $settings['content_length']		= 31257280;			} //30mb = 31457280
		if(!array_key_exists('check_content_length',	$settings)){ $settings['check_content_length']	= true;				} //$_server['content_length']
		if(!array_key_exists('show_msg_clean',			$settings)){ $settings['show_msg_clean']		= true;				}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('bt_insert_label',			$settings)){ $settings['bt_insert_label']		= Language::get("form_insert_bt");					}
		if(!array_key_exists('bt_update_label',			$settings)){ $settings['bt_update_label']		= Language::get("form_update_bt");					}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  - 
		if(!array_key_exists('msg_insert_sucess',		$settings)){ $settings['msg_insert_sucess']		= "". $settings['nick_name'] ." ".Language::get("form_msg_insert_sucess");		}
		if(!array_key_exists('msg_update_sucess',		$settings)){ $settings['msg_update_sucess']		= "". $settings['nick_name'] ." ".Language::get("form_msg_update_sucess");		}
		if(!array_key_exists('msg_delete_sucess',		$settings)){ $settings['msg_delete_sucess']		= "". $settings['nick_name'] ." ".Language::get("form_msg_delete_sucess");		}
		if(!array_key_exists('msg_insert_error',		$settings)){ $settings['msg_insert_error']		= Language::get("form_msg_insert_error")." ". $settings['nick_name'] .".";		}
		if(!array_key_exists('msg_update_error',		$settings)){ $settings['msg_update_error']		= Language::get("form_msg_update_error")." ". $settings['nick_name'] .".";		}
		if(!array_key_exists('msg_delete_error',		$settings)){ $settings['msg_delete_error']		= Language::get("form_msg_delete_error")." ". $settings['nick_name'] .".";		}

		$settings['redirect'] = str_replace( "//", "/",	$settings['redirect']);
		$settings['redirect_insert'] = str_replace( "//", "/",	$settings['redirect_insert']);
		$settings['redirect_update'] = str_replace( "//", "/",	$settings['redirect_update']);
		$settings['redirect_delete'] = str_replace( "//", "/",	$settings['redirect_delete']);


		return $settings;
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	private function checkSchema($schema, $settings){
		Debug::log("Model->checkschema:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//Debug::print_r($schema['from'][0]); 
		if(!array_key_exists('pk',						$schema['from'][0])){
			Error::show(0, "FORM_MODEL:: Não foi declarado o PK para o schema.");
		}
		if(!array_key_exists('table',					$schema['from'][0])){
			Error::show(0, "FORM_MODEL:: Não foi declarado o table para o schema.");
		}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
		if(!array_key_exists('join',				$schema)){ $schema['join'] 		= array();							}
		if(!array_key_exists('where',				$schema)){ $schema['where'] 	= "";								}
		if(!array_key_exists('having',				$schema)){ $schema['having'] 	= "";								}
		if(!array_key_exists('order',				$schema)){ $schema['order'] 	= "";								}
		if(!array_key_exists('group',				$schema)){ $schema['group'] 	= "";								}
		if(!array_key_exists('group_list',			$schema)){ $schema['group_list']= $schema['group'];					}
		if(!array_key_exists('group_view',			$schema)){ $schema['group_view']= $schema['group'];					}
		if(!array_key_exists('limit',				$schema)){ $schema['limit'] 	= array();							}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
	//	LEMBRAR DE ATUALIZAR A CLASSE OrderTables->recursiveOrderJoinCheck
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
		foreach($schema['from'] as $k => $table){

			$if0_false = ($k == 0) ? false : true;
			if(!array_key_exists('save',				$table)){ $schema['from'][$k]['save'] 				= "";								}
			if(!array_key_exists('view',				$table)){ $schema['from'][$k]['view'] 				= true;								}
			if(!array_key_exists('idPad',				$table)){ $schema['from'][$k]['idPad'] 				= 4;								}
			if(!array_key_exists('list',				$table)){ $schema['from'][$k]['list'] 				= true;								}
			if(!array_key_exists('listAsColumn',		$table)){ $schema['from'][$k]['listAsColumn']		= true;								}
			if(!array_key_exists('list_fields',			$table)){ $schema['from'][$k]['list_fields']		= $schema['from'][$k]['list'];		}
			if(!array_key_exists('list_inner',			$table)){ $schema['from'][$k]['list_inner']			= $schema['from'][$k]['list'];		}
			if(!array_key_exists('export',				$table)){ $schema['from'][$k]['export'] 			= true;								}
			if(!array_key_exists('order',				$table)){ $schema['from'][$k]['order'] 				= "";								}
			if(!array_key_exists('status_name',			$table)){ $schema['from'][$k]['status_name']		= 'status_int';						}
			if(!array_key_exists('delete_pk_empties',	$table)){ $schema['from'][$k]['delete_pk_empties'] 	= $if0_false;						}
			if(!array_key_exists('table_prefix',		$table)){ $schema['from'][$k]['table_prefix'] 		= $settings['sql_table_prefix'];	}
			
			$checkPos = strpos($schema['from'][$k]['table'], $schema['from'][$k]['table_prefix']);
	        $schema['from'][$k]['table'] = ( $checkPos  === 0 || $checkPos > 0 ) ? ($schema['from'][$k]['table']) : ($schema['from'][$k]['table_prefix'].$schema['from'][$k]['table']) ;
			
			if(!array_key_exists('table_nick',			$table)){ $schema['from'][$k]['table_nick'] 		= $table['table'];					}

			if($settings['check_schema'] === true){
				$check = false;
				foreach($schema['fields'] as $key=>$val){
					if(!isset($val['name'])) $val['name'] = false;
					if($key == $table['pk'] || $val['name'] == $table['pk']){
						$check = true;
						break;
					}
				}
				if($check == false) 	Error::show(0, "MODEL:: A referência PK: <b>". $table['pk_ref'] ."</b> no FROM <b>".$table['table']."</b> deve ser declarada corretamente na lista do schema.");
			}
		}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
		foreach($schema['join'] as $k => $table){
			if(!array_key_exists('save',				$table)){ $schema['join'][$k]['save'] 				= true;								}
			if(!array_key_exists('view',				$table)){ $schema['join'][$k]['view'] 				= true;								}
			if(!array_key_exists('idPad',				$table)){ $schema['join'][$k]['idPad'] 				= 4;								}
			if(!array_key_exists('list',				$table)){ $schema['join'][$k]['list'] 				= true;								}
			if(!array_key_exists('listAsColumn',		$table)){ $schema['join'][$k]['listAsColumn']		= false;							}
			if(!array_key_exists('list_fields',			$table)){ $schema['join'][$k]['list_fields']		= $schema['join'][$k]['list'];		}
			if(!array_key_exists('list_inner',			$table)){ $schema['join'][$k]['list_inner']			= $schema['join'][$k]['list'];		}
			if(!array_key_exists('export',				$table)){ $schema['join'][$k]['export'] 			= true;								}
			if(!array_key_exists('delete_pk_empties',	$table)){ $schema['join'][$k]['delete_pk_empties'] 	= true;								}
			if(!array_key_exists('on',					$table)){ $schema['join'][$k]['on'] 				= "";								}
			if(!array_key_exists('order',				$table)){ $schema['join'][$k]['order'] 				= "";								}
			if(!array_key_exists('status_name',			$table)){ $schema['join'][$k]['status_name']		= 'status_int';						}
			if(!array_key_exists('table_prefix',		$table)){ $schema['join'][$k]['table_prefix'] 		= $settings['sql_table_prefix'];	}

	        $checkPos = strpos($schema['join'][$k]['table'], $schema['join'][$k]['table_prefix']);
	        $schema['join'][$k]['table'] = ( $checkPos  === 0 || $checkPos > 0 ) ? ($schema['join'][$k]['table']) : ($schema['join'][$k]['table_prefix'].$schema['join'][$k]['table']) ;

			if(!array_key_exists('table_nick',			$table)){ $schema['join'][$k]['table_nick'] 		= $schema['join'][$k]['table'];					}

			if($settings['check_schema'] === true){
				$check = false;
				foreach($schema['fields'] as $key=>$val){
					if(!isset($val['name'])) $val['name'] = false;
					if($key == $table['pk_ref'] || $val['name'] == $table['pk_ref']){
						$check = true;
						break;
					}
				}
				if($table['table_ref'] != "" && $check == false) 	Error::show(0, "MODEL:: A referência PK_REF: <b>". $table['pk_ref'] ."</b> no JOIN <b>".$table['table']."</b> deve ser declarada corretamente na lista do schema.");
				$check = false;
				foreach($schema['fields'] as $key=>$val){
					if(!isset($val['name'])) $val['name'] = false;
					if($key == $table['pk'] && $val['name'] != false &&  $val['name'] != $table['pk']){
						$check = false;
						break;
					}
					if($key == $table['pk'] || $val['name'] == $table['pk']){
						$check = true;
						break;
					}
				}
				if($check == false) Error::show(0, "MODEL:: A referência PK: <b>". $table['pk'] ."</b> no JOIN <b>".$table['table']."</b> deve ser declarada corretamente na lista do schema.");
			}
		}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - --
		//Run::$DEBUG_PRINT = true;
		$order = (int)$settings['order_fields_index'];
		foreach($schema['fields'] as $key=>$val){
			$multiple 	  = false;
			$listAsColumn = true;
			$idPad		  = 4; 
			if(!array_key_exists('belongsTo',		$val)){ $schema['fields'][$key]['belongsTo'] 	= false;	}
			if($schema['fields'][$key]['belongsTo'] !== false){/**/
				$from_index = -1;
				$join_index = -1;
				foreach($schema['from'] as $k => $from){ 
					if($from['table_nick'] == $schema['fields'][$key]['belongsTo'] || $from['table'] == $schema['fields'][$key]['belongsTo']){
						$from_index = $k;
						$listAsColumn = $from['listAsColumn'];
						$idPad		  = $from['idPad'];
						//Debug::print_r("ACHOU $key / {$schema['fields'][$key]['belongsTo']} :".$from_index);
						break;
					}else if($join['table'] == $from['table_prefix'].$schema['fields'][$key]['belongsTo']){
						$schema['fields'][$key]['belongsTo'] = $from['table_prefix'].$schema['fields'][$key]['belongsTo'];
						$join_index = $k;
						break;
					}
					$multiple 	= false;
				}
				if($from_index == -1){
					foreach($schema['join'] as $k => $join){
						//Debug::p($schema['fields'][$key]['belongsTo'], $join['table']);
						if($join['table_nick'] == $schema['fields'][$key]['belongsTo'] || $join['table'] == $schema['fields'][$key]['belongsTo']){
							$join_index = $k;
							$listAsColumn = $join['listAsColumn'];
							$idPad		  = $join['idPad'];
							break;
						}else if($join['table'] == $join['table_prefix'].$schema['fields'][$key]['belongsTo']){
							$schema['fields'][$key]['belongsTo'] = $join['table_prefix'].$schema['fields'][$key]['belongsTo'];
							$join_index = $k;
							break;
						}
					}
					$multiple 	= (isset($schema['join'][$join_index]['multiple'])) 	? $schema['join'][$join_index]['multiple']		:	false;
				}
				if($from_index == -1 && $join_index == -1){
					Error::show(0, "MODEL:: O campo <b>". $key ."</b> possui uma referência belongsTo <b>".$schema['fields'][$key]['belongsTo'] ."</b> não encontrada em from ou em join no schema.");
				} 
			}
			//Debug::print_r("ACHOU $key / {$schema['fields'][$key]['belongsTo']} :");

			if(!array_key_exists('name',			$val)){ $schema['fields'][$key]['name'] 			= $key;			}
			if(!array_key_exists('label',			$val)){ $schema['fields'][$key]['label'] 			= $key;			}
			if(!array_key_exists('belongsTo',		$val)){ $schema['fields'][$key]['belongsTo'] 		= $schema['from'][0]['table_nick'] != "" ? $schema['from'][0]['table_nick'] : $schema['from'][0]['table'];	}
			if(!array_key_exists('fieldRef',		$val)){ $schema['fields'][$key]['fieldRef'] 		= $key;			}
			if(!array_key_exists('view',			$val)){ $schema['fields'][$key]['view'] 			= true;			}
			if(!array_key_exists('list',			$val)){ $schema['fields'][$key]['list'] 			= false;		}
			if(!array_key_exists('listLabel',		$val)){ $schema['fields'][$key]['listLabel'] 		= $schema['fields'][$key]['label'];		}
			if(!array_key_exists('listOrder',		$val)){ $schema['fields'][$key]['listOrder'] 		= $order++;		}
			if(!array_key_exists('listInClass',		$val)){ $schema['fields'][$key]['listInClass'] 		= false;		}
			if(!array_key_exists('listImplode',		$val)){ $schema['fields'][$key]['listImplode'] 		= ", ";			}
			if(!array_key_exists('listWidth',		$val)){ $schema['fields'][$key]['listWidth'] 		= false;		}
			if($this->checkPkInTable($schema, 	$schema['fields'][$key]['name']) === true){
				if(!array_key_exists('listAsColumn',	$val))	$schema['fields'][$key]['listAsColumn'] = false;
																$schema['fields'][$key]['idPad'] 		= $idPad;
																$schema['fields'][$key]['isKey'] 		= true;
																														}
			else{ 	
				if(!array_key_exists('listAsColumn',	$val))	$schema['fields'][$key]['listAsColumn'] = $listAsColumn ;							}
			if(!array_key_exists('exportWidth',		$val)){ $schema['fields'][$key]['exportWidth'] 		= $schema['fields'][$key]['listWidth'];		}
			if(!array_key_exists('export',			$val)){ $schema['fields'][$key]['export'] 			= true;			}
			if(!array_key_exists('skipRecEmpty',	$val)){ $schema['fields'][$key]['skipRecEmpty'] 	= false;		} // retira todos os campos do registro para o insert/update se for vazio
			if(!array_key_exists('skipFieldEmpty',	$val)){ $schema['fields'][$key]['skipFieldEmpty'] 	= false;		} // retira apenas o campo vazio para o insert/update
			if(!array_key_exists('type',			$val)){ $schema['fields'][$key]['type'] 			= "string";		}
			if(!array_key_exists('insert',			$val)){ $schema['fields'][$key]['insert'] 			= true;			}
			if(!array_key_exists('update',			$val)){ $schema['fields'][$key]['update'] 			= false;		}
			if(!array_key_exists('multiple',		$val)){ $schema['fields'][$key]['multiple'] 		= $multiple;	}
			if(!array_key_exists('allowJS',			$val)){ $schema['fields'][$key]['allowJS'] 			= false;		}
			if(!array_key_exists('allowHTML',		$val)){ $schema['fields'][$key]['allowHTML'] 		= false;		}
			if(!array_key_exists('realScape',		$val)){ $schema['fields'][$key]['realScape']		= true;			}
			if(!array_key_exists('maxLength',		$val)){ $schema['fields'][$key]['maxLength']		= false;		}
			if(!array_key_exists('removeSpecials',	$val)){ $schema['fields'][$key]['removeSpecials']	= false;		}
			if(!array_key_exists('convertSpecials',	$val)){ $schema['fields'][$key]['convertSpecials']	= true;			}
			if(!array_key_exists('protectData',		$val)){ $schema['fields'][$key]['protectData'] 		= true;			}
			if(!array_key_exists('addSlashe',		$val)){ $schema['fields'][$key]['addSlashe'] 		= true;			}
			if(!array_key_exists('convertValue',	$val)){ $schema['fields'][$key]['convertValue'] 	= false;		}
			if(!array_key_exists('sqlSelect',		$val)){ $schema['fields'][$key]['sqlSelect']		= false;		} // 'select_as'   => 'CONVERT(INT, COLUMN)', // COLUMN = NOME DA COLUNA
			if(!array_key_exists('value',			$val)){ $schema['fields'][$key]['value'] 			= false;		}
			if(!array_key_exists('mask',			$val)){ $schema['fields'][$key]['mask'] 			= "";			}
			if(!array_key_exists('validation',		$val)){ $schema['fields'][$key]['validation']		= array();		}
			if(!array_key_exists('labelEmpty',		$val)){ $schema['fields'][$key]['labelEmpty']		= "";			}
			if(!array_key_exists('labelList',		$val)){ $schema['fields'][$key]['labelList']		= array();		}

			if(	
				(
					$schema['fields'][$key]['type'] == "datetime" 		
					|| $schema['fields'][$key]['type'] == "date_time"
					|| $schema['fields'][$key]['type'] == "date_insert"
					|| $schema['fields'][$key]['type'] == "date_update"
				)
				AND	
				(	
					!isset($this->data[$key])	
				)
			){
				$this->data[$key] = date("Y-m-d H:i:s");
				//echo "<br /> >>>>$key: ".$this->data[$key];
			}
			ksort($schema['fields'][$key]);
		}

		if($settings['redirect_insert'] != false && $this->data['action'] == "insert"){
			$settings['redirect'] = $settings['redirect_insert'];
		}
		if($settings['redirect_update'] != false && $this->data['action'] == "update"){
			$settings['redirect'] = $settings['redirect_update'];
		}
		return array("schema"=>$schema, "settings"=>$settings);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function checkPkInTable($schema, $pk=""){
		//Run::$DEBUG_PRINT = true;
		//Debug::p("comparando1 ".$pk, $table['pk']);
		foreach($schema['join'] as $k => $table){
			if($table['pk'] === $pk) return true;
			else if($table['pk_ref'] === $pk) return true;
			else if($table['fk_ref'] === $pk) return true;
		}
		foreach($schema['from'] as $k => $table){
		//	Debug::p("comparando ".$pk, $table['pk']);
			if($table['pk'] === $pk) return true;
		}
		return false;
	}
}
// ############################################################################################################################

?>