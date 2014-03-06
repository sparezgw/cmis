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
	*  /i/x show the item iid=x
	*/
	function get($f3) {
		$i = $this->i;
		$t = new DB\SQL\Mapper($this->db,'itemtype');
		$its = $t->find(); // all data from table itemtype
		$iid = $f3->get('PARAMS.iid');
		if (empty($iid)) {
			$f3->set('its', $its);
			$f3->set('method', 'post');
			$f3->set('page', 
				array(
					"title"=>"器材总览",
					"view"=>"item/_edit.html"
				)
			);
		} elseif ($iid=="l") {
			$itarr = array();
			foreach ($its as $it)
				$itarr[$it->tID] = $it->name;
			$f3->set('its', $itarr);
			$f3->set('items', $i->find());
			$f3->set('page', 
				array(
					"title"=>"器材总览",
					"view"=>"item/_list.html"
				)
			);
		} else {
			$i->load(array('iID=?', $iid));
			if ($i->dry()) {
				$f3->error(404);
				die;
			} else {
				$i->copyto('i');
			}
			$f3->set('its', $its);
			$f3->set('method', 'put');
			$f3->set('page', 
				array(
					"title"=>"器材明细",
					"view"=>"item/_edit.html"
				)
			);
		}

	}

	/**
	* URI:
	*  /i   add new item
	*  /i/l search the item
	*/
	function post($f3) {
		$i = $this->i;
		$i->copyFrom('POST');
		$i->save();

		$f3->reroute('/i/l');
	}

	/**
	* URI:
	*  /i/x update the item iid=x
	*/
	function put($f3) {
		$i = $this->i;
		$iid = $f3->get('PARAMS.iid');
		if (!empty($iid)) $i->load(array('iID=?', $iid));
		else $f3->error(404);
		$i->copyFrom('POST');
		$i->save();

		$f3->reroute('/i/l');
	}

	/**
	* URI:
	*  /i/x delete the item iid=x
	*/
	function delete($f3) {
		$i = $this->i;

		$iid = $f3->get('PARAMS.iid');
		if (!empty($iid)) $i->load(array('iID=?', $iid));
		else $f3->error(404);
		$i->erase();

		$f3->reroute('/i/l');
	}
}