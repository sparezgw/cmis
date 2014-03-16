<?php
/**
*
*/
class Home extends Controller {

  function __construct() {
    parent::__construct();
  }

  function main($f3) {
    $f3->reroute('/home');
  }

  function get($f3) {
  //  $uid = $f3->get('SESSION.UUID');
  //  $api = new API;
  //  $f3->set('num', $api->usage($f3, $uid));
  //  $f3->set('act', $api->activity($f3, $uid));

    $f3->set('page', 
      array(
        "title"=>"首页",
        "view"=>"home/_main.html"
      )
    );
  }

  // function help($f3) {

  //  $f3->set('pageTitle', '帮助');
  //  $f3->set('pageContent', 'home/_help.html');
  // }

  // function un($f3) {

  //  $f3->set('pageTitle', '未完成');
  //  $f3->set('pageContent', 'home/un.html');
  // }


}
