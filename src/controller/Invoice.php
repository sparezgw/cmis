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
    // 新增发票
    $v = $this->v;
    $v->copyFrom('POST');
    $v->save();
    // 处理货物备件
    $d = new DB\SQL\Mapper($this->db,'depots');
    $items = json_decode($v->items);
    $is = array();
    foreach ($items as $key => $value) {
      $d->iID = (int)$key;
      $d->amount = $value->a;
      $d->price = $value->p;
      $d->datetime = date("Y-m-d H:i:s");
      $d->operate = "XG0";
      $d->mID = $f3->get('SESSION.MID');
      $d->detail = $v->vID;
      $d->save();
      $this->writelog($f3, array("table"=>'depots', "op"=>'NEW', "opID"=>$d->dID));
      $is[] = $d->dID;
      $d->reset();
    }
    // 更新记录，完善货物出入库ID
    $v->items = json_encode($is);
    $v->save();
    //记录日志
    $this->writelog($f3, array("table"=>'invoices', "op"=>'NEW', "opID"=>$v->vID));
    //页面跳转
    $f3->reroute('/v');
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
    $this->writelog($f3, array("table"=>'checks', "op"=>'PUT', "opID"=>$c->cID));
    //页面跳转
    $f3->reroute('/c/l');
  }
}
