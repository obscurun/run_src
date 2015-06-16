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
require_once(PAGS_PATH."model/form2_model.php");
require_once(PAGS_PATH."model/form3_model.php");
class TestesController extends Router{
	public $model;						 // instância de pagModel();
	public $autoLoadMethod = true;		 // especifica se carrega o método automaticamente pelo RouterMethods
	public $acceptNextIndexUnknownLevels = 1;
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){		
	//	Debug::print_r($_POST);
	//	Debug::print_r($_FILES);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function form1(){
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
	public function form2(){
		Run::$benchmark->mark("Form2Controller/Inicio");
		Run::$router->acceptNextLevels(1);
		Run::$router->setTemplateData("title", "Form2");
		$this->model = new Form2Model("form2_cadastro");
		Run::$benchmark->writeMark("Form2Controller/Inicio", "Form2Controller/newModel");
		$this->loadView("form2");
		Run::$benchmark->writeMark("Form2Controller/newModel", "Form2Controller/loadView");
		Run::$benchmark->writeMark("Form2Controller/Inicio", "Form2Controller/Final");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function form3(){
		Run::$benchmark->mark("Form3Controller/Inicio");
		Run::$router->acceptNextLevels(1);
		Run::$router->setTemplateData("title", "Form3");
		$this->model = new Form3Model("form3_cadastro");
		Run::$benchmark->writeMark("Form3Controller/Inicio", "Form3Controller/newModel");
		$this->loadView("form3");
		Run::$benchmark->writeMark("Form3Controller/newModel", "Form3Controller/loadView");
		Run::$benchmark->writeMark("Form3Controller/Inicio", "Form3Controller/Final");
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