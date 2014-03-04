<?php
/**
* 
*/
class Check extends Controller {

	protected $check;
	
	function __construct() {
		parent::__construct();
		$this->check = new DB\SQL\Mapper($this->db,'checks');
	}

	function get($f3) {
		$f3->set('pageTitle', '支票信息');
		$f3->set('pageContent', 'check/_edit.html');
	}

	function post($f3) {
		# code...
	}

	function table($f3) {
		$f3->set('pageTitle', '支票列表');
		$f3->set('pageContent', 'check/_list.html');
	}
}
