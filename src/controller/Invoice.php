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
      case (substr($vid,0,1) == 'p'):
        $method = $f3->get('GET.m');
        if ($method == 'c') {
          $c = new DB\SQL\Mapper($this->db,'checks');
          $cs = $c->select('cID,checkno,invoicedate,purpose,money',
            array('sID=? and holder=?', $f3->get('GET.s'), $f3->get('GET.h')),
            array(
              'order' => 'cID',
              'limit' => 5,
              'offset' => 0
              )
            );
          $carr = array();
          foreach ($cs as $cc) {
            $carr[] = array(
              "value"=>$cc->cID,
              "text"=>$cc->invoicedate." ".$cc->checkno." ".$cc->purpose." ".$cc->money
            );
          }
          $re = json_encode($carr);
        } else {
          # code...
        }
        
        $f3->set('page.json', $re);
        break;
      case ($vid == 'l'):
        $sarr = array();
        foreach ($ss as $es)
          $sarr[$es->sID] = $es->name;
        $f3->set('ss', $sarr);
        $f3->set('vs', $v->find());
        $f3->set('page',
          array(
            "title"=>"发票浏览",
            "view"=>"invoice/_list.html"
          )
        );
        break;
      case (is_numeric($vid)):
        $v->load(array('vID=?', $vid));
        if ($v->dry()) $f3->error(404);
        $v->copyto('v');
        // 获取供应商名称
        $s->load(array('sID=?', $v->sID));
        $f3->set('v.sname', $s->name);
        // 读取出入库表，列出发票物品明细
        // $d = new DB\SQL\Mapper($this->db,'depots');
        $items = substr($v->items, 1, strlen($v->items)-2);
        $f3->set('is',
          $this->db->exec(
            'SELECT depots.amount,price,name,brand,type
            FROM depots
            LEFT JOIN items
            ON depots.iID = items.iID
            WHERE dID in ('.$items.')'
          )
        );
        // $is = $d->select('iID,amount,price', 'dID in ('.$items.')');
        // $f3->set('is', $is);
        $f3->set('page',
          array(
            "title"=>"发票明细",
            "view"=>"invoice/_edit.html",
            "plugin"=>"selectize",
            "js"=>"updateinvoice"
          )
        );
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
            "js"=>"newinvoice"
          )
        );
        break;
    }

    // } elseif ($cid == 'l') {
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
      $d->detail = json_encode(array($v->vID=>$v->invoiceno));
      $d->memo = "发票日期: ".$v->invoicedate." 账户: ".$f3->get('ch.'.$v->holder);
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
    $f3->reroute('/v/l');
  }

  function put($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);
    $vid = $f3->get('PARAMS[1]');
    $v = $this->v;
    if($vid && is_numeric($vid)) $v->load(array('vID=?', $vid));
    else $f3->error(404);
    if($v->dry()) $f3->error(404);
    else {
      $v->copyFrom('POST');
      $v->payment = json_encode(array($f3->get('POST.p_m')=>$f3->get('POST.p_id')));
      $v->save();
    }
    //记录日志
    $this->writelog($f3, array("table"=>'invoices', "op"=>'PUT', "opID"=>$vid));
    //页面跳转
    $f3->reroute('/v/l');
  }
}
