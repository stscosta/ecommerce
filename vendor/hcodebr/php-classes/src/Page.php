<?php 
/* serve de base para a maioria das paginas do sistema */

namespace Hcode;
use Rain\Tpl;


class Page{


	private $tpl;
	private $options =[];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];
    
    // PARAMETRIZACAO DA BUSCA NA VAIRÁVEL NO TEMPLATE
	private function setData($data = array()){
		foreach ($data as $key => $value) {
			$this->tpl->assign($key, $value);
		}		
	}


	//GERA O CABEÇALHO DA PÁGINA
	public function __construct($opts = array()){ 
 
 		$this->options = array_merge($this->defaults, $opts);

 		$config = array(
		    "base_url"      => null,
		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT']."/views/",
		    "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
		    "debug"         => false
		);

		Tpl::configure($config);
		$this->tpl = new Tpl;
		if ($this->options['data']) $this->setData($this->options['data']);
		if ($this->options['header'] === true) $this->tpl->draw("header", false);
	
	}

	// BUSCA O CONTEUDO DA PÁGINA
	public function setTpl($tplname, $data = array(), $returnHTML = false){
		$this->setData($data);
		return $this->tpl->draw($tplname, $returnHTML);

	}

	//GERA O RODAPÉ DA PÁGINA
 	public function __destruct(){
		if ($this->options['footer'] === true) $this->tpl->draw("footer", false);
	}

}





?>