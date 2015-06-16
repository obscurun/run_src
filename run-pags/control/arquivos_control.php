<?
require_once(APP_PATH."run_pags/model/arquivos_model.php");
require_once(RUN_PATH."run/core/page.php");
require_once(RUN_PATH."run/libraries/image.php");
require_once(RUN_PATH."libraries/url_router/router_page.php");
require_once(RUN_PATH.'helpers/render.php');
require_once(RUN_PATH.'helpers/form.php');
// ********************************************************************************************************************************
class ArquivosController extends Render{
	  var $_imageWidth 			= 1200;
	  var $_imageHeight 		= 900;
	  var $_imageWidthMiddle 	= 400;
	  var $_imageHeightMiddle 	= 250;
	  var $_imageWidthMini 		= 200;
	  var $_imageHeightMini 	= 150;
	  var $_imageWidthMiniHome 	= 95;
	  var $_imageHeightMiniHome	= 65;
	  var $_imageWidthMiddleHome 	= 226;
	  var $_imageHeightMiddleHome	= 202;
	  var $_imageWidthKit 			= 110;
	  var $_imageHeightKit 			= 50;
	  var $_imageWidthEditor		= 400;
	  var $_imageHeightEditor 		= 300;
	  public $_imageWidthManual 	= 50;
	  public $_imageHeightManual 	= 50;
	  public $model;
	  //-----------------------------------------------------------------------------------------------------------------------------
	  function __construct(){
			if(UrlController::getLastLevel() == "upload_image"){
				$_POST['action']		= "insert";
				$_POST['token'] 		= "";
				$_POST['local'] 		= "";
				$_POST['status'] 		= 1;
				$_POST['pk_arquivo'] 	= "";
				$_FILES['arquivo'] 		= $_FILES['upload'];
				$_POST['descricao']		= "ilustração";
				$_POST['nota']			= "Imagem inserida dentro de conteúdos";
				$_POST['nome'] 			= Kit::$control->string->lower(Kit::$control->string->clearLatinSpecials($_FILES['arquivo']['name']));				 
				$_POST['extensao']		= explode('.',$_POST['nome']);
				$_POST['extensao']		= $_POST['extensao'][1];
				$_POST['mime_type'] 	= $_FILES['arquivo']['type'];
			}
	  		$this->model = new ArquivosModel();
			if(isset(Config::$_imageWidth)) 		$this->_imageWidth 			= Config::$_imageWidth;
			if(isset(Config::$_imageHeight))		$this->_imageHeight 		= Config::$_imageHeight;
			if(isset(Config::$_imageWidthMiddle))	$this->_imageWidthMiddle 	= Config::$_imageWidthMiddle;
			if(isset(Config::$_imageHeightMiddle))	$this->_imageHeightMiddle 	= Config::$_imageHeightMiddle;
			if(isset(Config::$_imageWidthMini))		$this->_imageWidthMini 		= Config::$_imageWidthMini;
			if(isset(Config::$_imageHeightMini))	$this->_imageHeightMini 	= Config::$_imageHeightMini;
			if(isset(Config::$_imageWidthEditor))	$this->_imageWidthEditor	= Config::$_imageWidthEditor;
			if(isset(Config::$_imageHeightEditor))	$this->_imageHeightEditor	= Config::$_imageHeightEditor;
			/**/
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageWidth = 591;
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageHeight = 597;
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageWidthMiddle = 191;
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageHeightMiddle = 197;
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageWidthMini = 144;
			if(Kit::$control->getRequest("fk_template") == "31") $this->_imageHeightMini = 133;
			
			if(Kit::$control->getRequest("fk_template") == "35") $this->_imageWidthMiddle = 145;
			if(Kit::$control->getRequest("fk_template") == "35") $this->_imageHeightMiddle = 87;
			
			
			if(Kit::$control->getRequest("fk_template") == "38") $this->_imageWidth = 660;
			if(Kit::$control->getRequest("fk_template") == "38") $this->_imageHeight = 241;
			
	  }	  
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function index(){
			Kit::$login->checkLogin(1);
	  		$this->listing();
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function listing(){
	  	   //	View::$ACCEPTING_PARAMS = 1;
			Kit::$login->checkLogin(1);
	  	   	$this->model->getList();
	   		View::load("arquivos_lista");
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function listing_images(){
	  	   //	View::$ACCEPTING_PARAMS = 1;
			Kit::$login->checkLogin(1);
	  	   	$this->model->filterImages = true;
	  	   	$this->model->getList();
	   		View::load("arquivos_lista_imagens");
	  }	 
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function form(){
	  	   	$this->model->getListTemplates();
			if(isset($this->model->DATA_INT['ref'])) Kit::$login->checkLogin(2);
			else Kit::$login->checkLogin(3);
			
	  		if((Kit::$control->getPost("action") == "insert" ||Kit::$control->getPost("action") == "update") && count($this->model->ERRORS) <= 0){
			if($this->model->DATA_INT['arquivo']['size'] >= 30){
			    $checkImage = false;
			    if(isset($this->model->DATA_INT['arquivo']['tmp_name'])){
						$ext = explode('.',$this->model->DATA_INT['arquivo']['name']);
						if(count($ext) >= 2) $ext = $ext[1];
						if($ext == 'jpg' || $ext == 'png' || $ext == 'gif') $checkImage = true;
						else $checkImage = false;
				}
				if($checkImage ==  false){
				  try{ unlink(CFG_PATH."../pags/view/files/". $this->model->DATA['nome']); } catch(Exception $e){ };
				  move_uploaded_file($this->model->DATA_INT['arquivo']['tmp_name'], CFG_PATH."../pags/view/files/". $this->model->DATA['nome']);
				  if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
				}
			    else{
				  $IMG 				= new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->_imageWidthKit;
				  $IMG->height 		= $this->_imageHeightKit;		
				  $result_record 	= $IMG->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/kit_".Kit::$control->getPost("nome"));

				if(Kit::$control->getPost("modo_edicao") == "upload"){
				  try{ unlink( CFG_PATH."../pags/view/files/". $this->model->DATA['nome']); } catch(Exception $e){ };
				  move_uploaded_file($this->model->DATA_INT['arquivo']['tmp_name'], CFG_PATH."../pags/view/files/". $this->model->DATA['nome']);
				  if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));						
				}	
				else if(Kit::$control->getPost("modo_edicao") == "manual"){					
						try{ unlink(CFG_PATH."../pags/view/files/manual_".$this->model->DATA['nome']); } catch(Exception $e){ };
						move_uploaded_file($this->model->DATA_INT['arquivo']['tmp_name'], CFG_PATH."../pags/view/files/manual_".$this->model->DATA['nome']);
						header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA['nome']);
						exit;
				}
				else{
					try{
						$IMG 					= new Image();
						$IMG->crop 				= true;
						$IMG->resize			= true;
						$IMG->ajust 			= 'x';
						$IMG->position_x		= 'center';
						$IMG->position_y		= 'center';
						$IMG->width 			= $this->_imageWidth;
						$IMG->height 			= $this->_imageHeight;
						$IMG->box_width 		= $this->_imageWidth;
						$IMG->box_height 		= $this->_imageHeight;
						if(Kit::$control->getRequest("fk_template") == "38") $IMG->mask = CFG_PATH."../pags/view/img/masks/mask_banner_produto.png";	
						//$IMG->mask	 		= "img/masks/teste.png";		
				 		try{ unlink(CFG_PATH."../pags/view/files/".Kit::$control->getPost("nome")); } catch(Exception $e){ };				
						$result_record 			= $IMG->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/".Kit::$control->getPost("nome"));
					} catch(Exception $e){		}
						
					try{
						$IMG_MN 				= new Image();
						$IMG_MN->crop 			= true;
						$IMG_MN->resize			= true;
						$IMG_MN->ajust 			= 'x';
						$IMG_MN->position_x		= 'center';
						$IMG_MN->position_y		= 'center';
						$IMG_MN->width 			= $this->_imageWidthMiddle;
						$IMG_MN->height 		= $this->_imageHeightMiddle;
						if(Kit::$control->getRequest("fk_template") != "31") $IMG_MN->mask	 		= CFG_PATH."../pags/view/img/masks/mask.png";
						if(Kit::$control->getRequest("fk_template") == "35") $IMG_MN->mask	 		= CFG_PATH."../pags/view/img/masks/mask_doenca.png";
				 		try{ unlink(CFG_PATH."../pags/view/files/md_".Kit::$control->getPost("nome")); } catch(Exception $e){ };
						$result_record 			= $IMG_MN->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/md_".Kit::$control->getPost("nome"));
						//if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					} catch(Exception $e){		}
						
					try{
						$IMG_MN 				= new Image();
						$IMG_MN->crop 			= true;
						$IMG_MN->resize			= true;
						$IMG_MN->ajust 			= 'x';
						$IMG_MN->position_x		= 'center';
						$IMG_MN->position_y		= 'center';
						$IMG_MN->width 			= $this->_imageWidthMini;
						$IMG_MN->height 		= $this->_imageHeightMini;
						 if(Kit::$control->getRequest("fk_template") != "31") $IMG_MN->mask	 	= CFG_PATH."../pags/view/img/masks/mask_mini.png";
						//$IMG->mask	 		= "img/masks/teste.png";
				 		try{ unlink(CFG_PATH."../pags/view/files/mini_".Kit::$control->getPost("nome")); } catch(Exception $e){ };
						$result_record 			= $IMG_MN->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/mini_".Kit::$control->getPost("nome"));
						//if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					} catch(Exception $e){		}
						
					try{
						$IMG_MN 				= new Image();
						$IMG_MN->crop 			= true;
						$IMG_MN->resize			= true;
						$IMG_MN->ajust 			= 'x';
						$IMG_MN->position_x		= 'center';
						$IMG_MN->position_y		= 'center';
						$IMG_MN->width 			= $this->_imageWidthMiniHome;
						$IMG_MN->height 		= $this->_imageHeightMiniHome;
						if(Kit::$control->getRequest("fk_template") != "31") $IMG_MN->mask	 	= CFG_PATH."../pags/view/img/masks/mask_mini_home.png";
						//$IMG->mask	 		= "img/masks/teste.png";
				 		try{ unlink(CFG_PATH."../pags/view/files/mini_home_".Kit::$control->getPost("nome")); } catch(Exception $e){ };
						$result_record 			= $IMG_MN->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/mini_home_".Kit::$control->getPost("nome"));
						if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					} catch(Exception $e){		}
				  }
				}
			}
			}
	  	    View::load("arquivos_form");
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function edicao_manual(){
			if(isset($this->model->DATA_INT['ref'])) Kit::$login->checkLogin(2);
			else Kit::$login->checkLogin(3);
			if(Kit::$control->getGet("action") == "manual" && Kit::$control->getPost("action") != "manual"){
				if(!isset($this->model->DATA['pos_w'])) $this->model->DATA['pos_w'] = $this->_imageWidth;
				if(!isset($this->model->DATA['pos_h'])) $this->model->DATA['pos_y'] = $this->_imageHeight;
				if(!isset($this->model->DATA['pos_x1'])) $this->model->DATA['pos_x1'] = 0;
				if(!isset($this->model->DATA['pos_y1'])) $this->model->DATA['pos_y1'] = 0;
				if(!isset($this->model->DATA['pos_x2'])) $this->model->DATA['pos_x2'] = $this->model->DATA['pos_w'];
				if(!isset($this->model->DATA['pos_y2'])) $this->model->DATA['pos_y2'] = $this->model->DATA['pos_y'];
				$this->_imageWidthManual = $this->_imageWidth;
				$this->_imageHeightManual = $this->_imageHeight;
			}
			else if(Kit::$control->getGet("action") == "manual_middle" && Kit::$control->getPost("action") != "manual_middle"){
				$this->_imageWidthManual = $this->_imageWidthMiddle;
				$this->_imageHeightManual = $this->_imageHeightMiddle;
				if(!isset($this->model->DATA['pos_w'])) $this->model->DATA['pos_w'] = $this->_imageWidthMiddle;
				if(!isset($this->model->DATA['pos_h'])) $this->model->DATA['pos_y'] = $this->_imageHeightMiddle;
				if(!isset($this->model->DATA['pos_x1'])) $this->model->DATA['pos_x1'] = 0;
				if(!isset($this->model->DATA['pos_y1'])) $this->model->DATA['pos_y1'] = 0;
				if(!isset($this->model->DATA['pos_x2'])) $this->model->DATA['pos_x2'] = $this->model->DATA['pos_w'];
				if(!isset($this->model->DATA['pos_y2'])) $this->model->DATA['pos_y2'] = $this->model->DATA['pos_y'];
			}
			else if(Kit::$control->getGet("action") == "manual_mini" && Kit::$control->getPost("action") != "manual_mini"){
				$this->_imageWidthManual = $this->_imageWidthMini;
				$this->_imageHeightManual = $this->_imageHeightMini;
				if(!isset($this->model->DATA['pos_w'])) $this->model->DATA['pos_w'] = $this->_imageWidthMini;
				if(!isset($this->model->DATA['pos_h'])) $this->model->DATA['pos_y'] = $this->_imageHeightMini;
				if(!isset($this->model->DATA['pos_x1'])) $this->model->DATA['pos_x1'] = 0;
				if(!isset($this->model->DATA['pos_y1'])) $this->model->DATA['pos_y1'] = 0;
				if(!isset($this->model->DATA['pos_x2'])) $this->model->DATA['pos_x2'] = $this->model->DATA['pos_w'];
				if(!isset($this->model->DATA['pos_y2'])) $this->model->DATA['pos_y2'] = $this->model->DATA['pos_y'];
			}
			else if(Kit::$control->getGet("action") == "manual_mini_home" && Kit::$control->getPost("action") != "manual_mini_home"){
				$this->_imageWidthManual = $this->_imageWidthMiniHome;
				$this->_imageHeightManual = $this->_imageHeightMiniHome;
				if(!isset($this->model->DATA['pos_w'])) $this->model->DATA['pos_w'] = $this->_imageWidthMiniHome;
				if(!isset($this->model->DATA['pos_h'])) $this->model->DATA['pos_y'] = $this->_imageHeightMiniHome;
				if(!isset($this->model->DATA['pos_x1'])) $this->model->DATA['pos_x1'] = 0;
				if(!isset($this->model->DATA['pos_y1'])) $this->model->DATA['pos_y1'] = 0;
				if(!isset($this->model->DATA['pos_x2'])) $this->model->DATA['pos_x2'] = $this->model->DATA['pos_w'];
				if(!isset($this->model->DATA['pos_y2'])) $this->model->DATA['pos_y2'] = $this->model->DATA['pos_y'];
			}
			
	  		if(Kit::$control->getPost("action") == "manual"){
				  $IMG = new Image();
				  $IMG->crop 		= false;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];
				  $IMG->box_width 	= $this->_imageWidth;
				  $IMG->box_height 	= $this->_imageHeight;
				  if(Kit::$control->getRequest("fk_template") == "38") $IMG->mask = CFG_PATH."../pags/view/img/masks/mask_banner_produto.png";					
				  $result_record = $IMG->crop_to_save(CFG_PATH."../pags/view/files/manual_". $this->model->DATA_INT['nome_int'], CFG_PATH."../pags/view/files/". $this->model->DATA_INT['nome_int'], $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				  
				  
				  
				  if($result_record && Kit::$control->getRequest("fk_template") == "38"){
					  header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual_mini&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA_INT['nome_int']);
					  exit;
				  }
				  
				  if($result_record){
					  header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual_middle&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA_INT['nome_int']);
					  exit;
				  }
			}
			else if(Kit::$control->getPost("action") == "manual_middle"){
				  $IMG = new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];
				  $IMG->box_width 	= $this->_imageWidthMiddle;
				  $IMG->box_height 	= $this->_imageHeightMiddle;
					//$this->model->DATA_INT['pos_w'] = 234;
					//$this->model->DATA_INT['pos_h'] = 229;
				  if(Kit::$control->getRequest("fk_template") != "31") $IMG->mask	 	= CFG_PATH."../pags/view/img/masks/mask.png";
				  if(Kit::$control->getRequest("fk_template") == "35") $IMG->mask	 		= CFG_PATH."../pags/view/img/masks/mask_doenca.png";
				  $result_record = $IMG->crop_to_save(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int'], CFG_PATH."../pags/view/files/md_".$this->model->DATA_INT['nome_int'], $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				 // Debug::print_r($_REQUEST);
				 // echo "W/H ".$this->model->DATA_INT['pos_w']." /".$this->model->DATA_INT['pos_h']; exit;
				  if($result_record){
					  header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual_mini&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA_INT['nome_int']);
					  exit;
				  }
			}
			else if(Kit::$control->getPost("action") == "manual_middle_home"){
				  $IMG = new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];
				  $IMG->box_width 	= $this->_imageWidthMiddle;
				  $IMG->box_height 	= $this->_imageHeightMiddle;
					//$this->model->DATA_INT['pos_w'] = 234;
					//$this->model->DATA_INT['pos_h'] = 229;
				  $result_record = $IMG->crop_to_save(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int'], CFG_PATH."../pags/view/files/md_".$this->model->DATA_INT['nome_int'], $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				 // Debug::print_r($_REQUEST);
				 // echo "W/H ".$this->model->DATA_INT['pos_w']." /".$this->model->DATA_INT['pos_h']; exit;
				  if($result_record){
					  header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual_mini&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA_INT['nome_int']);
					  exit;
				  }
			}
			else if(Kit::$control->getPost("action") == "manual_mini"){
				  $IMG = new Image();
				  $IMG->crop 		= false;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];
				  $IMG->box_width 			= $this->_imageWidthMini;
				  $IMG->box_height 			= $this->_imageHeightMini;
				  if(Kit::$control->getRequest("fk_template") != "31") $IMG->mask	 	= CFG_PATH."../pags/view/img/masks/mask_mini.png";
				  //$IMG->mask	 	= "img/masks/teste.png";					
				  $result_record = $IMG->crop_to_save(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int'], CFG_PATH."../pags/view/files/mini_".$this->model->DATA_INT['nome_int'], $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				  
				  if($result_record){
					  if(Kit::$control->getRequest("fk_template") != "31") header("Location: ". Config::$PATH ."arquivos/edicao_manual/?&action=manual_mini_home&fk_template=". $this->model->DATA['fk_template']."&nome=". $this->model->DATA_INT['nome_int']);
				      else{ 
					  	unlink(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int']);
					  	if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					  }
					  exit;
				  }
			}
			else if(Kit::$control->getPost("action") == "manual_mini_home"){
				  $IMG = new Image();
				  $IMG->crop 		= false;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];
				  $IMG->box_width 			= $this->_imageWidthMiniHome;
				  $IMG->box_height 			= $this->_imageHeightMiniHome;
				  if(Kit::$control->getRequest("fk_template") != "31") $IMG->mask	 	= CFG_PATH."../pags/view/img/masks/mask_mini_home.png";
				  //$IMG->mask	 	= "img/masks/teste.png";					
				  $result_record = $IMG->crop_to_save(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int'], CFG_PATH."../pags/view/files/mini_home_".$this->model->DATA_INT['nome_int'], $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				  
				  if($result_record){
				      unlink(CFG_PATH."../pags/view/files/manual_".$this->model->DATA_INT['nome_int']);
					  if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					  exit;
				  }
			}
	  	    View::load("arquivos_manual");
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function edicao_middle(){
			if(isset($this->model->DATA_INT['ref'])) Kit::$login->checkLogin(2);
			else Kit::$login->checkLogin(3);
	  		if(Kit::$control->getPost("action") == "manual"){
				  $IMG = new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];		
				  $this->RESULT_RECORD = $IMG->crop_to_save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/".Kit::$control->getPost("nome"), $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				  if($this->RESULT_RECORD){
					  $this->DADOS['action'] = 'manual_middle';
					  $this->edicao_mini();
					  if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					  //header("Location: ".$this->model->SETTINGS['PREFIX_PAGE']."manual.php?ref=".$this->lastID."&action=manual_mini&nome=".$this->DADOS['nome']);
					  exit;
				  }
			}
	  	    View::load("arquivos_editor");
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function edicao_mini(){
			if(isset($this->model->DATA_INT['ref'])) Kit::$login->checkLogin(2);
			else Kit::$login->checkLogin(3);
	  		if(Kit::$control->getPost("action") == "manual"){
				  $IMG = new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->model->DATA_INT['pos_w'];
				  $IMG->height 		= $this->model->DATA_INT['pos_h'];		
				  $this->RESULT_RECORD = $IMG->crop_to_save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/".Kit::$control->getPost("nome"), $this->model->DATA_INT['pos_x1'], $this->model->DATA_INT['pos_x2'],$this->model->DATA_INT['pos_y1'], $this->model->DATA_INT['pos_y2'], $this->model->DATA_INT['pos_w'], $this->model->DATA_INT['pos_h']);
				  if($this->RESULT_RECORD){
					  $this->DADOS['action'] = 'manual_mini';
					  $this->edicao_mini();
					  if($this->model->getPost('redirect_upload') != "") header("Location: ".$this->model->getPost('redirect_upload'));
					  //header("Location: ".$this->model->SETTINGS['PREFIX_PAGE']."manual.php?ref=".$this->lastID."&action=manual_mini&nome=".$this->DADOS['nome']);
					  exit;
				  }
			}
	  	    View::load("arquivos_editor");
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function upload_image(){
			Kit::$login->checkLogin(2);
			if((Kit::$control->getPost("action") == "insert" ||Kit::$control->getPost("action") == "update")){
				  $IMG 				= new Image();
				  $IMG->crop 		= true;
				  $IMG->resize		= true;
				  $IMG->ajust 		= 'x';
				  $IMG->position_x	= 'center';
				  $IMG->position_y	= 'center';
				  $IMG->width 		= $this->_imageWidthKit;
				  $IMG->height 		= $this->_imageHeightKit;		
				  $result_record 	= $IMG->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/kit_".Kit::$control->getPost("nome"));

				  $IMG 						= new Image();
				  $IMG->crop 				= true;
				  $IMG->resize				= true;
				  $IMG->ajust 				= 'x';
				  $IMG->position_x			= 'center';
				  $IMG->position_y			= 'center';
				  $IMG->width 				= $this->_imageWidthEditor;
				  $IMG->height 				= $this->_imageHeightEditor;						
				  $result_record 			= $IMG->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/".Kit::$control->getPost("nome"));
				  
				  $IMG_MD 					= new Image();
				  $IMG_MD->crop 			= true;
				  $IMG_MD->resize			= true;
				  $IMG_MD->ajust 			= 'x';
				  $IMG_MD->position_x		= 'center';
				  $IMG_MD->position_y		= 'center';
				  $IMG_MD->width 			= $this->_imageWidthMiddle;
				  $IMG_MD->height 			= $this->_imageHeightMiddle;
				  $result_record 			= $IMG_MN->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/md_".Kit::$control->getPost("nome"));
				  
				  $IMG_MN 					= new Image();
				  $IMG_MN->crop 			= true;
				  $IMG_MN->resize			= true;
				  $IMG_MN->ajust 			= 'x';
				  $IMG_MN->position_x		= 'center';
				  $IMG_MN->position_y		= 'center';
				  $IMG_MN->width 			= $this->_imageWidthMini;
				  $IMG_MN->height 			= $this->_imageHeightMini;
				  $result_record 			= $IMG_MN->save($this->model->DATA_INT['arquivo'], CFG_PATH."../pags/view/files/mini_".Kit::$control->getPost("nome"));
			}
			else{
				  $this->model->validationShowErrors(2,3);
			}
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  public function view(){
			Kit::$login->checkLogin(2);
	  	    View::load("arquivos_view",ACCEPTING_PARAMS);
	  }
	  //-----------------------------------------------------------------------------------------------------------------------------
	  
}
// ********************************************************************************************************************************
?>