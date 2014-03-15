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

}
?>