<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
    public function register(){
		$username = I('request.username','');
		$password = I('request.password','');
		$repassword = I('request.repassword','');
		$mod = M('user');
		if(!empty($username)){
			if(!empty($password)){
				if($password==$repassword){
					$user = $mod->where('username="'.$username.'"')->find();
					if(empty($user)){
						$cache['username'] = $username;
						$cache['password'] = md5($password);
						$mod->add($cache);
						$json = array('status'=>1,'message'=>'注册成功');
					}else{
						$json = array('status'=>2,'message'=>'用户名已存在');
					}
				}else{
					$json = array('status'=>2,'message'=>'两次密码输入不一致');
				}
			}else{
				$json = array('status'=>2,'message'=>'密码不能为空');
			}
		}else{
			$json = array('status'=>2,'message'=>'用户名不能为空');
		}
		$this->ajaxReturn($json);
    }
	
	public function login(){
		$username = I('request.username','');
		$password = I('request.password','');
		if(!empty($username)){
			if(!empty($password)){
				$mod = M('user');
				$user = $mod->where('username="'.$username.'"')->find();
				if(!empty($user)){
					if(md5($password)==$user['password']){
						$key = 'login_'.$username;
						$token = md5($username.$id.time());
						$value = array('id'=>$user['id'],'username'=>$username,'token'=>$token);
						S($key,$value);
						$json = array('status'=>1,'message'=>'登录成功','result'=>$value);
					}else{
						$json = array('status'=>2,'message'=>'密码错误');
					}
				}else{
					$json = array('status'=>2,'message'=>'用户名不存在');
				}
			}else{
				$json = array('status'=>2,'message'=>'密码不能为空');
			}
		}else{
			$json = array('status'=>2,'message'=>'用户名不能为空');
		}
		$this->ajaxReturn($json);
	}
	
	public function ucenter(){
		$id = I('request.id',0,'intval');
		if(checklogin()){
			$mod = M('user');
			$user = $mod->field('username,gender,nickname,birthday')->where('id='.$id)->find();
			$json = array('status'=>1,'message'=>'获取成功','result'=>$user);
		}else{
			$json = array('status'=>2,'message'=>'用户身份验证失败');
		}
		$this->ajaxReturn($json);
	}
	
	public function upload(){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小B
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','txt');// 设置附件上传类型
		$dir = 'public/uploads';
		$upload->rootPath  =      $_SERVER['DOCUMENT_ROOT'].$dir.'/'; // 设置附件上传根目录
		//上传多个文件
		$images = array();
		foreach($_FILES as $f){
			$info   =   $upload->uploadOne($f);
			if(!$info) {// 上传错误提示错误信息
				$json = array('status'=>2,'message'=>$upload->getError());
			}else{// 上传成功 打印图片文件路径
				array_push($images,'/'.$dir.'/'.$info['savepath'].$info['savename']);
			}
		}
		$json = array('status'=>1,'message'=>'上传成功','result'=>$images);
		$this->ajaxReturn($json);
	}
	
	public function _empty(){
		$json = array('status'=>2,'message'=>'你所访问的地址不存在');
		$this->ajaxReturn($json);
	}
}