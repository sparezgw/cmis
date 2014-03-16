<?php
/**
* 
*/
class User extends Controller {

  protected $u;

  function __construct() {
    parent::__construct();
    $this->u = new DB\SQL\Mapper($this->db,'users');
  }

  function get($f3) {
    $f3->set('us',
      $this->db->exec(
        'SELECT uID,uname,passwd,name,role
        FROM users
        LEFT JOIN members
        ON users.mID = members.mID'
      )
    );
    $f3->set('page', 
      array(
        "title"=>"用户列表",
        "view"=>"user/_list.html",
        "plugin"=>"selectize",
        "js"=>"select"
      )
    );
  }

  function post($f3) {
    //权限控制：>＝5 管理员
    if(!$this->doit($f3, 5)) $f3->error(401, $this->r);

    $u = $this->u;
    $u->copyFrom('POST');
    $m = new DB\SQL\Mapper($this->db,'members');
    $name = $f3->get('POST.name');
    $m->load(array('name=?', $name));
    if($m->dry()) {
      $m->name = $name;
      $m->save();
      //记录日志
      $this->writelog($f3 ,array("table"=>'members', "op"=>'NEW', "opID"=>$m->mID));
    }
    $u->mID = $m->mID;
    $u->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'users', "op"=>'NEW', "opID"=>$u->uID));
    //页面跳转
    $f3->reroute('/u');
  }

}