<?php
/**
* 
*/
class Controller {

	protected $db, $logs, $l;

	function __construct() {
		$f3=Base::instance();
		$this->db = new DB\SQL($f3->get('db'), $f3->get('db_user'), $f3->get('db_pwd'));
		$this->logs = new DB\SQL\Mapper($this->db,'logs');
		$uid = $f3->get('SESSION.UUID');
		$this->l = array(
			"save"=>false,
			"uid"=>$uid,
			"table"=>"",
			"op"=>"",
			"opID"=>0
		);
	}

	function beforeroute($f3) {
		if ($f3->get('SESSION.UUID') == "") 
			$f3->reroute('/user/login');
	}

	function afterroute($f3) {
		if ($f3->get('json'))
			echo $f3->get('msg');
		else {
			if($this->l['save']) {
				$this->logs->copyFrom($this->l);
				$this->logs->save();
			}
			$this->l['save'] = false;
			$f3->set('l',$this->l['uid']);
			echo Template::instance()->render('layout.html');
		}
	}

}