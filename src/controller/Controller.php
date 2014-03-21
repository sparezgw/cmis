<?php
/**
* 
*/
class Controller {

	protected $db;
	public $l,$r = "对不起您没有权限进行操作！";

	function __construct() {
		$f3=\Base::instance();
		$this->db = new DB\SQL($f3->get('db'), $f3->get('db_user'), $f3->get('db_pwd'));
	}

	function beforeroute($f3) {
		if ($f3->get('SESSION.UUID') == "") 
			$f3->reroute('/login');
	}

	function afterroute($f3) {
		if ($f3->exists('page.msg'))
			echo $f3->get('page.msg');
		elseif ($f3->exists('page.single'))
			echo Template::instance()->render($f3->get('page.single'));
		else {
			echo Template::instance()->render('layout.html');
		}
	}

	function doit($f3,$r) { //权限验证
		$role = (int)$f3->get('SESSION.ROLE');
		if ($role == 5) return TRUE;
		elseif ($role >= $r) return TRUE;
		else return FALSE;
	}

	function onerror($f3) {
		$f3->set('page', 
			array(
				"title"=>"ERROR",
				"view"=>"_error.html"
			)
		);
		// echo "dfdfdfdfdfdf";
		// echo Template::instance()->render('layout.html');
		return TRUE;
	}

	function writelog($f3, $l) {
		$logs = new DB\SQL\Mapper($this->db,'logs');
		$f3->set('lll', $l);
		$logs->copyFrom('lll');
		$uid = $f3->get('SESSION.UUID');
		$logs->uid = $uid;
		$logs->time = date('Y-m-d H:i:s');
		$logs->save();
	}
}