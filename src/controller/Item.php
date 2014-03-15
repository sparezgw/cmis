<?php
/**
* 
*/
class Item extends Controller {

  protected $i;
  
  function __construct() {
    parent::__construct();
    $this->i = new DB\SQL\Mapper($this->db,'items');
  }

  /**
  * URI:
  *  /i   new item form
  *  /i/l item list
  *  /i/a all of items list for amount
  *  /i/x show the item iid=x
  */
  function get($f3) {
    $i = $this->i;
    $t = new DB\SQL\Mapper($this->db,'itemtype');
    $its = $t->find(); // all data from table itemtype
    $iid = $f3->get('PARAMS.iid');
    switch(TRUE) {
      case (is_numeric($iid)): //URI: /i/@iid
        $i->load(array('iID=?', $iid));
        if ($i->dry()) {
          $f3->error(404);
          die;
        } else {
          $i->copyto('i');
        }
        $f3->set('its', $its);
        $f3->set('page', 
          array(
            "title"=>"器材明细",
            "view"=>"item/_edit.html",
            "method"=>"PUT",
            "plugin"=>"selectize",
            "js"=>array("select","delete")
          )
        );
        break;
      case ($iid=='l'): //URI: /i/l
        $itarr = array();
        foreach ($its as $it)
          $itarr[$it->tID] = $it->name;
        $f3->set('its', $itarr);
        $f3->set('items', $i->select('iID,tID,name,brand,type,unit,memo'));
        $f3->set('page', 
          array(
            "title"=>"器材总览",
            "view"=>"item/_list.html"
          )
        );
        break;
      case ($iid=='a'): //URI: /i/a
        $itarr = array();
        foreach ($its as $it)
          $itarr[$it->tID] = $it->name;
        $f3->set('its', $itarr);
        $f3->set('items', $i->select('iID,tID,name,brand,type,amount'));
        $f3->set('page', 
          array(
            "title"=>"库存总表",
            "view"=>"item/_all.html",
            "plugin"=>"selectize"
          )
        );
        break;
      default: //URI: /i
        $f3->set('its', $its);
        $f3->set('page', 
          array(
            "title"=>"新增器材",
            "view"=>"item/_edit.html",
            "method"=>"POST",
            "plugin"=>"selectize",
            "js"=>"select"
          )
        );
        break;
    }
  }

  /**
  * URI:
  *  /i   add new item
  *  /i/l search the item
  */
  function post($f3) {
    //权限控制：>＝3 库管员
    if(!$this->doit($f3, 3)) $f3->error(401, $this->r);

    $i = $this->i;
    $i->copyFrom('POST');
    $i->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'items', "op"=>'NEW', "opID"=>$i->iID));
    //页面跳转
    $f3->reroute('/i/l');
  }

  /**
  * URI:
  *  /i/x update the item iid=x
  */
  function put($f3) {
    //权限控制：>＝3 库管员
    if(!$this->doit($f3, 3)) $f3->error(401, $this->r);

    $i = $this->i;
    $iid = $f3->get('PARAMS.iid');
    if (!empty($iid)) $i->load(array('iID=?', $iid));
    else $f3->error(404);
    $i->copyFrom('POST');
    $i->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'items', "op"=>'PUT', "opID"=>$iid));
    //页面跳转
    $f3->reroute('/i/l');
  }

  /**
  * URI:
  *  /i/x delete the item iid=x
  */
  function delete($f3) {
    //权限控制：>＝4 财务员
    if(!$this->doit($f3, 4)) $f3->error(401, $this->r);

    $i = $this->i;
    $iid = $f3->get('PARAMS.iid');
    if (!empty($iid)) $i->erase(array('iID=?', $iid));
    else $f3->error(404);
    //记录日志
    $this->writelog($f3 ,array("table"=>'items', "op"=>'DEL', "opID"=>$iid));
    //页面跳转
    $f3->reroute('/i/l');
  }
}