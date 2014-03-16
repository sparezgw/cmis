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
    $c = $this->c;
    $s = new DB\SQL\Mapper($this->db,'suppliers');
    $ss = $s->select('sID,name');
    $cid = $f3->get('PARAMS.cid');
    if (empty($cid)) {
      $f3->set('ss', $ss);
      $f3->set('page',
        array(
          "title"=>"新支票",
          "view"=>"check/_new.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } elseif ($cid == 'l') {
      $sarr = array();
      foreach ($ss as $es)
        $sarr[$es->sID] = $es->name;
      $f3->set('ss', $sarr);
      $f3->set('cs', $c->find());
      $f3->set('page',
        array(
          "title"=>"支票浏览",
          "view"=>"check/_list.html"
        )
      );
    } elseif (is_numeric($cid)) {
      $c->load(array('cID=?', $cid));
      if ($c->dry()) $f3->error(404);
      else $c->copyto('c');
      $f3->set('ss', $ss);
      $f3->set('page',
        array(
          "title"=>"支票领取",
          "view"=>"check/_edit.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    }
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

  function put($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);
    $cid = $f3->get('PARAMS.cid');
    $c = $this->c;
    if(!empty($cid)) $c->load(array('cID=?', $cid));
    else $f3->error(404);
    if($c->dry()) $f3->error(404);
    else {
      $c->copyFrom('POST');
      $c->save();
    }
    //记录日志
    $this->writelog($f3 ,array("table"=>'checks', "op"=>'PUT', "opID"=>$c->cID));
    //页面跳转
    $f3->reroute('/c/l');
  }
}
