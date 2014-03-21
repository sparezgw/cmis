<?php
/**
* 
*/
class ItemType extends Controller {

  protected $t;

  function __construct($f3) {
    parent::__construct();
    $this->t = new DB\SQL\Mapper($this->db,'itemtype');
  }

  function get($f3) {
    $t = $this->t;
    $f3->set('ts', $t->find());
    $f3->set('page', 
      array(
        "title"=>"器材分类",
        "view"=>"item/_type.html"
      )
    );
  }
  function post($f3) {
    //权限控制：>＝2 工作人员
    if(!$this->doit($f3, 2)) $f3->error(401, $this->r);

    $t = $this->t;
    $t->copyFrom('POST');
    $t->save();
    //记录日志
    $this->writelog($f3 ,array("table"=>'itemtype', "op"=>'NEW', "opID"=>$t->tID));
    //页面跳转
    $f3->reroute('/t');
  }
}
?>