<?php
/**
* 
*/
class Asset extends Controller {

  protected $a;

  function __construct($f3) {
    parent::__construct();
    $this->a = new DB\SQL\Mapper($this->db,'assets');
  }

  function get($f3) {
    $a = $this->a;
    $aid = $f3->get('PARAMS.aid');
    if ($f3->exists('PARAMS.vid')&&$f3->exists('PARAMS.iid')) {
      $f3->set('page', 
        array(
          "title"=>"固定资产明细",
          "view"=>"asset/_view.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } else if (empty($aid)) {
      $f3->set('page', 
        array(
          "title"=>"固定资产入账",
          "view"=>"asset/_new.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } elseif ($aid=='l') {
      $as = $a->find(); //所有供应商数据
      $f3->set('as', $as);
      $f3->set('page', 
        array(
          "title"=>"固定资产列表",
          "view"=>"asset/_list.html"
        )
      );
    } else {
      $a->load(array('aID=?', $aid));
      if ($a->dry()) $f3->error(404);
      else $a->copyto('a');
      $f3->set('page', 
        array(
          "title"=>"固定资产明细",
          // "js"=>"delete", //页面读取JS文件，添加删除method
          "method"=>"POST", //页面默认method为POST
          "view"=>"asset/_edit.html"
        )
      );
    }

  }

  function post($f3) {
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);
    $s = $this->s;
    $sid = $f3->get('PARAMS.sid');
    $l = array("table"=>'suppliers', "op"=>'NEW');
    if (!empty($sid)) {
      $s->load(array('sID=?', $sid));
      $l['op'] = "PUT";
    }
    $s->copyFrom('POST');
    $s->save();

    $l['opID'] = $s->sID;
    $this->writelog($f3, $l);

    $f3->reroute('/s');
  }

  function delete($f3) {
    if(!$this->doit($f3, 4)) {
      // $f3->set('ONERROR', $this->onerror($f3));
      $f3->error(401, $this->r);
    }
      $s = $this->s;
    $sid = $f3->get('PARAMS.sid');
    if (!empty($sid)) $s->load(array('sID=?', $sid));
    else $f3->error(404);
    $s->erase();

    $this->writelog($f3, array("table"=>'suppliers', "op"=>'DEL', "opID"=>$sid));

    $f3->reroute('/s');
  }

}
?>