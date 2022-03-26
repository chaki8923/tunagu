<?php

require_once(dirname(__FILE__) . '/../function.php');

class WebTest  extends PHPUnit\Framework\TestCase{
  public function testGetUserResult(){
    $result= getUser(13);
    $this->assertTrue(count($result) > 0);
  }
 
}