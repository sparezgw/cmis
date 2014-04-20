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
    $vid = $f3->get('PARAMS.vid');
    $iid = $f3->get('PARAMS.iid');
    if (!empty($vid)&&!empty($iid)) {
      $f3->set('page', 
        array(
          "title"=>"固定资产明细",
          "view"=>"asset/_view.html",
          "plugin"=>"selectize",
          "js"=>"select"
        )
      );
    } elseif (empty($aid)) {
      $f3->set('page', 
        array(
          "title"=>"固定资产入账",
          "view"=>"asset/_new.html",
          "plugin"=>"selectize",
          "js"=>"asset"
        )
      );
    } elseif ($aid=='l') {
      $as = $this->db->exec(
        'SELECT aID,a.vID,invoiceno,a.iID,name,brand,type,assetno,internalno,departno,a.memo
        FROM assets as a
        LEFT JOIN invoices as v 
        ON a.vID = v.vID
        LEFT JOIN items as i
        ON a.iID = i.iID'
      );
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
    $a = $this->a;
    $a->copyFrom('POST');
    $a->save();

    $this->writelog($f3, array("table"=>'asset', "op"=>'NEW', "opID"=>$a->aID));

    $f3->reroute('/a/l');
  }


}
?>