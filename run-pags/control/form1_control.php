<?
//*********************************************************************************************************************************
/**
 * Controle da página, é chamada pela Classe RouterMethods;
 * Cada método desse controle é referenciado por um ou mais níveis do caminho da url;
 * @example www.dominio.com.br/exemplo/: carrega a página exemplo_control.php, instância: ExemploController, chama método index()
 * @example www.dominio.com.br/exemplo/interna/: instância ExemploController e chama método interna();
 * @example www.dominio.com.br/exemplo/interna/sub/: instância ExemploController e chama interna__sub() se existir, ou interna();
 * Utilize Run::$router->acceptNextLevels(NUM_PROX_LEVELS_PERMITIDO) para proteger as urls dentro de cada método;
 **/
require_once(PAGS_PATH."model/form1_model.php");
class Form1Controller extends Router{
	public $model;						 // instância de pagModel();
	public $autoLoadMethod = true;		 // especifica se carrega o método automaticamente pelo RouterMethods
	public $acceptNextIndexUnknownLevels = 1;
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){		
	//	Debug::print_r($_POST);
	//	Debug::print_r($_FILES);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function index(){
		Run::$benchmark->mark("Form1Controller/Inicio");
		Run::$router->acceptNextLevels(1);
		Run::$router->setTemplateData("title", "Form1");
		$this->model = new Form1Model("form_cadastro");
		Run::$benchmark->writeMark("Form1Controller/Inicio", "Form1Controller/newModel");
		$this->loadView("form1");
		Run::$benchmark->writeMark("Form1Controller/newModel", "Form1Controller/loadView");
		Run::$benchmark->writeMark("Form1Controller/Inicio", "Form1Controller/Final");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function form(){
		Run::$router->acceptNextLevels(1);
		$this->model = new Form1Model("form_cadastro");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
}
//*********************************************************************************************************************************
?>