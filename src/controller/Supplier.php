<?php
/**
* 
*/
class Supplier extends Controller {

	protected $supplier;

	function __construct() {
		parent::__construct();
		$this->supplier = new DB\SQL\Mapper($this->db,'suppliers');
	}

	function get($f3) {
		$f3->set('pageTitle', '新供应商');
		$f3->set('pageContent', 'supplier/_edit.html');
	}

	function table($f3) {
		$f3->set('pageTitle', '供应商列表');
		$f3->set('pageContent', 'supplier/_list.html');
	}
}
?>