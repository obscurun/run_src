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
class HomeController extends Router{
	public $model;						 // instância de pagModel();
	public $autoLoadMethod = true;		 // especifica se carrega o método automaticamente pelo RouterMethods
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
		
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function index(){
		Run::$benchmark->mark("home1");
		Run::$router->acceptNextLevels(0);
		Run::$router->setTemplateData("title", "Home");
		$this->loadView("home");
		Run::$benchmark->writeMark("home1", "home2");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function teste(){
		Run::$router->acceptNextLevels(2);
		echo "teste";
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function dev(){
		Run::$router->acceptNextLevels(1);		
		echo "<br clear='all' /><br clear='all' /> <br clear='all' /> -------------------------------------- <br clear='all' /> ";
		
		Debug::print_r(Language::$phrases);
		Debug::print_r("_SESSION", $_SESSION);
		Debug::print_r("_SERVER", $_SERVER);
		Debug::print_r("path", Run::$router->path);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function dev__teste__teste2(){	
		echo "<br clear='all' /><br clear='all' /> <br clear='all' /> teste 2 <br clear='all' /> ";
		Debug::print_r(Language::$phrases);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function sair(){
		Run::$session->destroyAllSessions();
		echo "<br clear='all' /><br clear='all' /> <br clear='all' /> SAINDO -------------------------------------- <br clear='all' /> ";
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
}
//*********************************************************************************************************************************
?>