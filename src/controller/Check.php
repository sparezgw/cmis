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
    $cid = $f3->get('PARAMS.cid');
     if ($cid == 'l' || !$f3->devoid('PARAMS.page')) {
      $page = $f3->get('PARAMS.page');
      if (empty($page)) $page = 1;
      // $f3->set('cs', $c->select(
      //   'cID,checkno,holder,invoicedate,money,purpose,duedate,memo',
      //   NULL,
      //   array('order'=>'invoicedate'))
      // );
      $cs = $c->paginate($page-1,$f3->get('ep'),NULL,array('order'=>'invoicedate'));
      $f3->set('cs', $cs['subset']);
      $f3->set('page',
        array(
          "title"=>"支票浏览",
          "view"=>"check/_list.html",
          "count"=>$cs['count'],
          "pos"=>$cs['pos']
        )
      );
    } elseif (is_numeric($cid)) {
      $c->load(array('cID=?', $cid));
      if ($c->dry()) $f3->error(404);
      else $c->copyto('c');
      $f3->set('ss', $s->select('sID,name'));
      $f3->set('page',
        array(
          "title"=>"支票领取",
          "view"=>"check/_edit.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } elseif (empty($cid)) {
      $f3->set('ss', $s->select('sID,name'));
      $f3->set('page',
        array(
          "title"=>"新支票",
          "view"=>"check/_new.html",
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
