<?php
/**
* 
*/
class Login extends Controller {

  protected $u;

  function __construct() {
    parent::__construct();
    $this->u = new DB\SQL\Mapper($this->db,'users');
  }

  function beforeroute($f3) {}
  
  function get($f3) {
    $f3->set('page', 
      array(
        "title"=>"登录",
        "js"=>"login",
        "view"=>"user/_login.html"
      )
    );
  }

  function post($f3) {
    $u = $this->u;
    $pwd = $f3->get('POST.password');
    // $pwd = crypt($pwd,'gcs@2013');

    $u->load(array('uname=?', $f3->get('POST.username')));

    if(empty($u->uID)) $msg = "用户名不存在。";
    elseif ($pwd != $u->passwd) $msg = "密码输入有误。";
    elseif ($u->role == 0) $msg = "对不起您没有登录权限。";
    else { 
      $f3->set('SESSION.UUID', $u->uID);
      $f3->set('SESSION.MID', $u->mID);
      $f3->set('SESSION.ROLE', $u->role);
      $msg = FALSE;
    }
    $f3->set('page.msg', $msg);
  }

  function logout($f3) {
    $f3->clear('SESSION');
    $f3->reroute('/login');
  }
}