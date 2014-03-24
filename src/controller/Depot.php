<?php
/**
* 
*/
class Depot extends Controller {

  protected $d;
  
  function __construct() {
    parent::__construct();
    $this->d = new DB\SQL\Mapper($this->db,'depots');
  }

  function get($f3) {
    $i = new DB\SQL\Mapper($this->db,'items');
    $m = new DB\SQL\Mapper($this->db,'members');
    $ms = $m->find();
    $f3->set('ms', $ms);

    $did = $f3->get('PARAMS.did');
    if (!is_numeric($did)) {
      $is = $i->select('iID,name,brand,type');
      $f3->set('is', $is);
      if(!empty($did)) $f3->set('iid', (int)substr($did, 1));
      $f3->set('page', 
        array(
          "title"=>"出入库操作",
          "view"=>"depot/_edit.html",
          "method"=>"POST",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } else {
      // $d = $this->d;
      // $ds = $d->find(array('iID=?'), $iid);
      // $f3->set('ds', $ds);
    }
  }

  function table($f3) {
    $d = $this->d;
    $iid = $f3->get('PARAMS.iid');
    $ds = $d->find('iID='.$iid, array('order'=>'dID'));
    $f3->set('ds', $ds);
    // 操作方式 数组
    $ops = array_merge($f3->get('op.out'),$f3->get('op.in'));
    $ops['XG0'] = "发票";
    $f3->set('ops', $ops);
    // 设备明细
    $i = new DB\SQL\Mapper($this->db,'items');
    $i->load('iID='.$iid);
    $i->copyTo('i');
    // 操作人 数组
    $m = new DB\SQL\Mapper($this->db,'members');
    $ms = $m->find();
    $mss = array();
    foreach ($ms as $mm)
      $mss[$mm->mID] = $mm->name;
    $f3->set('ms', $mss);
    // page信息
    $f3->set('page',
      array(
        "title"=>"出入库流水",
        "view"=>"depot/_list.html"
      )
    );
  }

  function post($f3) {
    //权限控制：>＝3 库管员
    if(!$this->doit($f3, 3)) $f3->error(401, $this->r);

    $d = $this->d;
    $d->copyFrom('POST');
    //将当前时间加入数据字段
    $d->datetime .= " ".date("H:i:s");
    //添加新的操作人
    if(!empty($d->mID) && !is_numeric($d->mID)) {
      $m = new DB\SQL\Mapper($this->db,'members');
      $m->name = $d->mID;
      $m->save();
      $d->mID = $m->mID;
      //记录日志
      $this->writelog($f3, array("table"=>'members', "op"=>'NEW', "opID"=>$m->mID));
    }
    //修改货物表中的库存数量
    $i = new DB\SQL\Mapper($this->db,'items','iID,amount');
    $i->load(array('iID=?', $d->iID));
    if((int)substr($d->operate, -1))
      $i->amount += $d->amount;
    else
      $i->amount -= $d->amount;
    $i->save();
    //记录日志：修改库存
    $this->writelog($f3, array("table"=>'items', "op"=>'PUT', "opID"=>$i->iID));
    //数据存储
    if(!$i->dry()) $d->save();
    else $f3->error(404);
    //记录日志
    $this->writelog($f3, array("table"=>'depots', "op"=>'NEW', "opID"=>$d->dID));
    //页面跳转
    $f3->reroute('/i/a');
  }
}