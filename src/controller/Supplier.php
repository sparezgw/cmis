<?php
/**
* 
*/
class Supplier extends Controller {

	protected $s;

	function __construct($f3) {
		parent::__construct();
		$this->s = new DB\SQL\Mapper($this->db,'suppliers');
	}

	function get($f3) {
		$s = $this->s;
		$sid = $f3->get('PARAMS.sid');
		if (empty($sid)) {
			$ss = $s->find(); //所有供应商数据
			$f3->set('ss', $ss);
			$f3->set('page', 
				array(
					"title"=>"供应商列表",
					"view"=>"supplier/_list.html"
				)
			);
		} else {
			$s->load(array('sID=?', $sid));
			if ($s->dry()) $f3->error(404);
			else $s->copyto('s');
			$f3->set('page', 
				array(
					"title"=>"供应商明细",
					"js"=>"delete", //页面读取JS文件，添加删除method
					"method"=>"POST", //页面默认method为POST
					"view"=>"supplier/_edit.html"
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