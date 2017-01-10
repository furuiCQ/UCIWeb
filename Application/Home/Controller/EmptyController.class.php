<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {
	
	public function _empty(){
		$json = array('status'=>2,'message'=>'你所访问的地址不存在2');
		$this->ajaxReturn($json);
	}
}