<?php
/**
* 
*/
class Cash extends Controller {

  protected $h;
  
  function __construct() {
    parent::__construct();
    $this->h = new DB\SQL\Mapper($this->db,'cash');
  }

  function get($f3) {
    $h = $this->h;
    // $s = new DB\SQL\Mapper($this->db,'suppliers');
    // $ss = $s->select('sID,name');
    $hid = $f3->get('PARAMS.hid');
    if (is_numeric($hid)) {
      $h->load(array('hID=?', $hid));
      if ($h->dry()) $f3->error(404);
      else $h->copyto('h');
      $f3->set('page',
        array(
          "title"=>"刷卡信息",
          "view"=>"cash/_edit.html",
          "method"=>"PUT"
        )
      );
    } elseif ($hid == 'l' || !$f3->devoid('PARAMS.page')) {
      $page = $f3->get('PARAMS.page');
      if (empty($page)) $page = 1;
      $hs = $h->paginate($page-1,$f3->get('ep'));
      // $f3->set('hs', $h->find());
      $f3->set('hs', $hs['subset']);
      $f3->set('page',
        array(
          "title"=>"现金结算浏览",
          "view"=>"cash/_list.html",
          "count"=>$hs['count'],
          "pos"=>$hs['pos']
        )
      );
    } elseif (empty($hid)) {
      $f3->set('page',
        array(
          "title"=>"刷卡信息",
          "view"=>"cash/_edit.html",
          "method"=>"POST"
        )
      );
    }
  }

  function post($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);

    $h = $this->h;
    $h->copyFrom('POST');
    $h->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'cash', "op"=>'NEW', "opID"=>$h->hID));
    //页面跳转
    $f3->reroute('/h/l');
  }

  function put($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);
    $hid = $f3->get('PARAMS.hid');
    $h = $this->h;
    if(!empty($hid)) $h->load(array('hID=?', $hid));
    else $f3->error(404);
    if($h->dry()) $f3->error(404);
    else {
      $h->copyFrom('POST');
      $h->save();
    }
    //记录日志
    $this->writelog($f3 ,array("table"=>'cash', "op"=>'PUT', "opID"=>$h->hID));
    //页面跳转
    $f3->reroute('/h/l');
  }
}
