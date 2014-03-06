<?php
/**
* 
*/
class User extends Controller {

	protected $user;

	function __construct() {
		parent::__construct();
		$this->user = new DB\SQL\Mapper($this->db,'users');
	}

	function beforeroute($f3) {}

	function get($f3) {
		$f3->set('page', 
				array(
					"title"=>"登录",
					"view"=>"user/_login.html"
				)
			);
	}

	function post($f3) {
		$u = $this->user;
		$pwd = $f3->get('POST.password');
		// $pwd = crypt($pwd,'gcs@2013');

		$u->load(array('uname=?', $f3->get('POST.username')));

		if(empty($u->uID)) $msg = "用户名不存在。";

		else if ($pwd != $u->passwd) $msg = "密码输入有误。";

		else { 
			$f3->set('SESSION.UUID', $u->uID);
			// $f3->set('SESSION.PID', $u->pID);
			$msg = "false";
		}
		$f3->set('msg', $msg);
		$f3->set('json', true);
	}

	function logout($f3) {

		$f3->clear('SESSION');
		$f3->reroute('/user/login');
	}

	/*function reg($f3) {

		if ($f3->exists('POST.reg')) {
			$u = $this->user;
			$email = $f3->get('POST.username');
			$name = $f3->get('POST.name');
			$u->load(array('uname=?', $email));
			if ($f3->get('POST.password') != $f3->get('POST.confirm'))
				$msg = "密码输入两次不一样。";
			elseif (!empty($this->user->uID))
				$msg = "用户名已存在。";
			else {
				$u->uname = $email;
				$pwd = $f3->get('POST.password');
				$u->passwd = crypt($pwd,'gcs@2013');
				$u->pID = '000000';
				$u->insert();

				$u->load(array('uname=?', $email));
				$uid = $u->uID;

				$p = $this->db->exec(
					array(
						'INSERT INTO People (uID,name,email) VALUES (:uid,:name,:email)',
						'SELECT pID FROM People WHERE uID=:uid'
					),
					array(
						array(':uid'=>(int)$uid, ':name'=>$name, ':email'=>$email),
						array(':uid'=>(int)$uid)
					)
				);
				$u->load(array('pID=?', '000000'));
				$u->pID = $p[0]['pID'];
				$u->update();

				$f3->reroute('/');

			}
			$f3->set('msg', $msg);
		}
		$f3->set('pageTitle', '用户注册');
		$f3->set('pageContent', 'users/_reg.html');
	}*/
}