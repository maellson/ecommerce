<?php 

namespace Hcode;

use Rain\Tpl;

class Page{
	private $tpl;
	private $options = [];
	private $defautls = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]

	];



	public function __construct($opts = array(),$tpl_dir="/views/"){

		$this->options = array_merge($this->defautls,$opts);

		$config = array(//configurando o Rain para as rotas das views
					"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
					"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
					"debug"         => false // set to false to improve the speed
				   );

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);


		if($this->options["header"]===true) $this->tpl->draw("header");


	}

	

	public function setTpl($name, $data = array(),$returnHTML = false){

			$this->setData($data);

			return $this->tpl->draw($name,$returnHTML);
	}

	public function __destruct(){
		if($this->options["footer"]===true) $this->tpl->draw("footer");

	}

	private function setData($data = array()){//funcao setData usada nas demais para fazer o assig do Slim com Rain

			foreach ($data as $key => $value) {
				$this->tpl->assign($key,$value);
				
			}
	}
	
}


 ?>