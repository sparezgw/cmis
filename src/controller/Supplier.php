<?php
/**
* 
*/
class Supplier extends Controller {

	protected $s;

	function __construct() {
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
					"view"=>"supplier/_edit.html"
				)
			);
		}

	}

	function post($f3) {
		$s = $this->s;
		$sid = $f3->get('PARAMS.sid');
		if (!empty($sid)) $s->load(array('sID=?', $sid));
		$s->copyFrom('POST');
		$s->save();

		$f3->reroute('/s');
	}

	function delete($f3) {
	    $s = $this->s;
		$sid = $f3->get('PARAMS.sid');
		if (!empty($sid)) $s->load(array('sID=?', $sid));
		else $f3->error(404);
		$s->erase();

		$f3->reroute('/s');
	}

}
?>