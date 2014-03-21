<?php
/**
* 
*/
class Invoice extends Controller {

  protected $v;
  
  function __construct() {
    parent::__construct();
    $this->v = new DB\SQL\Mapper($this->db,'invoices');
  }

  function get($f3) {
    $v = $this->v;
    $vid = $f3->get('PARAMS[1]');
    $s = new DB\SQL\Mapper($this->db,'suppliers');
    $ss = $s->select('sID,name');
    $i = new DB\SQL\Mapper($this->db,'items');
    $is = $i->select('iID,name,brand,type');
    switch (TRUE) {
      case ($vid == 'i'):
        $f3->set('page.single', 'invoice/_new_item.html');
        break;
      case ($vid == 'a'):
        $f3->set('page.single', 'invoice/_add_item.html');
        break;
      default:
        $f3->set('ss', $ss);
        $f3->set('is', $is);
        $f3->set('cs', "");
        $f3->set('page',
          array(
            "title"=>"新发票",
            "view"=>"invoice/_new.html",
            "plugin"=>"selectize",
            "js"=>"invoice"
          )
        );
        break;
    }

    // } elseif ($cid == 'l') {
    //   $sarr = array();
    //   foreach ($ss as $es)
    //     $sarr[$es->sID] = $es->name;
    //   $f3->set('ss', $sarr);
    //   $f3->set('cs', $c->find());
    //   $f3->set('page',
    //     array(
    //       "title"=>"支票浏览",
    //       "view"=>"check/_list.html"
    //     )
    //   );
    // } elseif (is_numeric($cid)) {
    //   $c->load(array('cID=?', $cid));
    //   if ($c->dry()) $f3->error(404);
    //   else $c->copyto('c');
    //   $f3->set('ss', $ss);
    //   $f3->set('page',
    //     array(
    //       "title"=>"支票领取",
    //       "view"=>"check/_edit.html",
    //       "plugin"=>"selectize",
    //       "js"=>"select"
    //     )
    //   );
  }

  function post($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);

    $v = $this->v;
    $v->copyFrom('POST');
    // $c->save();
    //记录日志
    // $this->writelog($f3 ,array("table"=>'checks', "op"=>'NEW', "opID"=>$c->cID));
    //页面跳转
    // $f3->reroute('/c/l');
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
