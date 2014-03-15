<?php
/**
* 
*/
class Check extends Controller {

  protected $c;
  
  function __construct() {
    parent::__construct();
    $this->c = new DB\SQL\Mapper($this->db,'checks');
  }

  function get($f3) {
    $s = new DB\SQL\Mapper($this->db,'suppliers');
    $f3->set('ss', $s->select('sID,name'));
    $f3->set('page',
      array(
        "title"=>"新支票",
        "view"=>"check/_edit.html",
        "plugin"=>"selectize",
        "js"=>"select"
      )
    );
  }

  function post($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);

    $c = $this->c;
    $c->copyFrom('POST');
    $c->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'checks', "op"=>'NEW', "opID"=>$c->cID));
    //页面跳转
    $f3->reroute('/c/l');
  }

  function table($f3) {
    $f3->set('pageTitle', '支票列表');
    $f3->set('pageContent', 'check/_list.html');
  }
}
