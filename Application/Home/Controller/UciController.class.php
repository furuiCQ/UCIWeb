<?php
namespace Home\Controller;
use Think\Controller;
class UciController extends Controller {
	//登录接口
	public function login(){
		$username=I('request.username','');
		$password=I('request.password','');
		$mod=M('user');
		if(!empty($username) && !empty($password)){
			$user=$mod->where('pinyin="'.$username.'"')->find();
			if(empty($user)){
				$user=$mod->where('phone_number="'.$username.'"')->find();
					if(empty($user)){
						$user=$mod->where('qq="'.$username.'"')->find();
						if(!empty($user)){
							if(md5($password)==$user['password']){
									$key = 'login_'.$username;
									$token = md5($username.$id.time());
									$value = array('id'=>$user['id'],'username'=>$username,'token'=>$token);
									S($key,$value);
									$data=array('id'=>$user['id'],'token'=>$token);
									$mod->save($data);
									$json = array('status'=>1,'message'=>'登录成功','result'=>$value);
							}else{
								$json = array('status'=>2,'message'=>'密码错误');
							}
						}else{
							$json = array('status'=>2,'message'=>'用户名不存在');
						}
					}else{
						if(md5($password)==$user['password']){
								$key = 'login_'.$username;
								$token = md5($username.$id.time());
								$value = array('id'=>$user['id'],'username'=>$username,'token'=>$token);
								S($key,$value);
								$data=array('id'=>$user['id'],'token'=>$token);
								$mod->save($data);
								$json = array('status'=>1,'message'=>'登录成功','result'=>$value);
						}else{
							$json = array('status'=>2,'message'=>'密码错误');
						}
					}
			}else{
				if(md5($password)==$user['password']){
						$key = 'login_'.$username;
						$token = md5($username.$id.time());
						$value = array('id'=>$user['id'],'username'=>$username,'token'=>$token);
						S($key,$value);
						$data=array('id'=>$user['id'],'token'=>$token);
						$mod->save($data);
						$json = array('status'=>1,'message'=>'登录成功','result'=>$value);
				}else{
					$json = array('status'=>2,'message'=>'密码错误');
				}
			}
		}else{
			$json = array('status'=>2,'message'=>'用户名或密码不能为空');
		}
		$this->ajaxReturn($json);
	}
	//个人信息的修改
	public function updateUserData(){
		$id=I('request.id','');
		$username=I('request.username','');
		$password=I('request.password','');
		$phonenumber=I('request.phonenumber','');
		$address=I('request.address','');
		$pinyin=I('request.pinyin','');
		$qq=I('request.qq','');
		if(empty($username)){
			$json = array('status'=>2,'message'=>'用户名不能为空');
			$this->ajaxReturn($json);
		}	
		if(empty($password)){
			$json = array('status'=>2,'message'=>'密码不能为空');
			$this->ajaxReturn($json);
		}
		if(empty($phonenumber)){
			$json = array('status'=>2,'message'=>'手机号不能为空');
			$this->ajaxReturn($json);
		}
		$mod = M('user');
		$user = $mod->where('id="'.$id.'"')->find();
		if(!empty($user)){
			$cache['name'] = $username;
			$cache['password'] = md5($password);
			$cache['phone_number'] = $phonenumber;
			$cache['address'] = $address;
			$cache['qq'] = $qq;
			$cache['pinyin'] = $pinyin;
			$data=$mod->where('id="'.$id.'"')->save($cache);
			if($data>0){
				$json = array('status'=>1,'message'=>'更新数据成功');
			}else{
				$json = array('status'=>2,'message'=>'更新数据失败');
			}
		}else{
			$json = array('status'=>2,'message'=>'用户名不存在');
		}
		$this->ajaxReturn($json);
	}
	//添加UCI员工信息接口
	public function addMember(){
		$username=I('request.username','');
		$password=I('request.password','');
		$typeId=I('request.typeid','');
		$phonenumber=I('request.phonenumber','');
		$address=I('request.address','');
		$qq=I('request.qq','');
		$pinyin=I('request.pinyin','');
		if(empty($username)){
			$json = array('status'=>2,'message'=>'用户名不能为空');
			$this->ajaxReturn($json);
		}	
		if(empty($password)){
			$json = array('status'=>2,'message'=>'密码不能为空');
			$this->ajaxReturn($json);
		}
		if(empty($typeId)){
			$json = array('status'=>2,'message'=>'用户类型不能为空');
			$this->ajaxReturn($json);
		}
		if(empty($phonenumber)){
			$json = array('status'=>2,'message'=>'手机号不能为空');
			$this->ajaxReturn($json);
		}
		$mod = M('user');
		$user = $mod->where('name="'.$username.'"')->find();
		if(empty($user)){
			$cache['name'] = $username;
			$cache['password'] = md5($password);
			$cache['type_id'] = $typeId;
			$cache['phone_number'] = $phonenumber;
			$cache['address'] = $address;
			$cache['qq'] = $qq;
			$cache['pinyin'] = $pinyin;
			$mod->add($cache);
			$json = array('status'=>1,'message'=>'注册成功');
		}else{
			$json = array('status'=>2,'message'=>'用户名已存在');
		}
		$this->ajaxReturn($json);
	}
	//获取员工类型列表
	public function getMemberTypeList(){
		$mode=M('user_type');
		$type=$mode->select();
		if(!empty($type)){
			$json = array('status'=>1,'message'=>'登录成功','result'=>$type);
		}else{
			$json = array('status'=>2,'message'=>'数据为空');
		}
		
		$this->ajaxReturn($json);

	}
	//发布新闻接口
	public function sendNews(){
		$title=I('request.title','');
		$content=I('request.content','');
		$typeid=I('request.typeid','');
		$author_id=I('request.author_id','');
		if(empty($title)){
			$json = array('status'=>2,'message'=>'新闻标题不能为空');
			$this->ajaxReturn($json);
		}	
		if(empty($content)){
			$json = array('status'=>2,'message'=>'新闻内容不能为空');
			$this->ajaxReturn($json);
		}
		$savePath='Public/news/'.time().'.txt';
		$filename=$_SERVER['DOCUMENT_ROOT'].$dir.$savePath;
		file_put_contents($filename,$content);
		
		$mod = M('news');
		$cache['title'] = $title;
		$cache['content'] = $savePath;
		$cache['time'] = time();
		$cache['type_id'] = $typeid;
		$cache['author_id'] = $author_id;
		$mod->add($cache);
		$json = array('status'=>1,'message'=>'新闻发布成功');
		$this->ajaxReturn($json);
	}
	//获得新闻列表接口
	public function getNewsList(){
		$mode=M('news');
		$type=$mode->field('news.id,title,news.type_id,author_id,name as author_name,time')
		->join('user ON news.author_id=user.id')
		->where('news.isDelete=0')->select();
		if(!empty($type)){
			$json = array('status'=>1,'message'=>'获取数据成功','result'=>$type);
		}else{
			$json = array('status'=>2,'message'=>'数据为空');
		}
		
		$this->ajaxReturn($json);
	}
	//获得新闻列表接口
	public function getDeletedNewsList(){
		$mode=M('news');
		$type=$mode
		->field('news.id,title,news.type_id,author_id,name as author_name,time')
		->join('user ON news.author_id=user.id')
		->where('news.isDelete=1')->select();
		if(!empty($type)){
			$json = array('status'=>1,'message'=>'获取数据成功','result'=>$type);
		}else{
			$json = array('status'=>2,'message'=>'数据为空');
		}
		
		$this->ajaxReturn($json);
	}
	//获取新闻详情
	public function getNewsDetails(){
		$id=I('request.id','');
		$mode=M('news');
		$data=$mode
		->field('news.id,title,news.type_id,content,news_type.name as type_name,author_id,time')
		->join('news_type ON news.type_id=news_type.id')
		->where('news.id='.$id.' AND isDelete=0')->find();
		$filename=$_SERVER['DOCUMENT_ROOT'].$dir.$data['content'];
		$content=file_get_contents($filename);
		$data['content']=$content;
		if(!empty($data)){
			$json = array('status'=>1,'message'=>'获取数据成功','result'=>$data);
		}else{
			$json = array('status'=>2,'message'=>'该文章已被删除');
		}
		$this->ajaxReturn($json);
	}
	
	//更新新闻内容
	public function updateNews(){
		$id=I('request.id','');
		$title=I('request.title','');
		$content=I('request.content','');
		$typeid=I('request.typeid','');
		$author_id=I('request.author_id','');
		if(empty($title)){
			$json = array('status'=>2,'message'=>'新闻标题不能为空');
			$this->ajaxReturn($json);
		}	
		if(empty($content)){
			$json = array('status'=>2,'message'=>'新闻内容不能为空');
			$this->ajaxReturn($json);
		} 
	/*	file_put_contents('磁盘路径','储存内容','是否追加或覆盖 默认覆盖false'); 写入文件
		file_get_contents('磁盘路径'); 读取文件
		file_exists('磁盘路径'); 判断文件是否存在
		is_dir('磁盘路径'); 判断文件夹是否存在
		mkdir('磁盘路径',权限0777,是否联级创建true); 新建文件夹
		$_SERVER['DOCUMENT_ROOT'] 网站访问根目录磁盘路径
		mkdir('磁盘路径',权限0777,是否联级创建true);
		这个默认是false*/
	//	$filename=$_SERVER['DOCUMENT_ROOT'].$dir.'Public/news/'.time().'.txt';
		//file_put_contents($filename,$content,$type='txt');
		$updateContent=M('news');
		$update=$updateContent->where('id='.$id.' AND isDelete=0')->find();
		$filename=$_SERVER['DOCUMENT_ROOT'].$dir.$update['content'];
		file_put_contents($filename,$content,false);
		$mod = M('news');
		$cache['title'] = $title;
		$cache['time'] = time();
		$cache['type_id'] = $typeid;
		$cache['author_id'] = $author_id;
		$data=$mod->where('id='.$id)->save($cache);
		if($data>0){
			$json = array('status'=>1,'message'=>'新闻修改成功');
		}else{
			$json = array('status'=>2,'message'=>'新闻修改失败或与上次提交无差别');
		}
		$this->ajaxReturn($json);
	}
	public function restoreDeleteNews(){
		$id=I('request.id','');
		$mod = M('news');
		$cache['isDelete'] = 0;
		$data=$mod->where('id='.$id)->save($cache);
		if($data>0){
				$json = array('status'=>1,'message'=>'新闻恢复成功');
		}else{
				$json = array('status'=>2,'message'=>'新闻恢复失败或与上次提交无差别');
		}
		$this->ajaxReturn($json);
	}
	//删除新闻
	public function deleteNews(){
		$id=I('request.id','');
		$mod = M('news');
		$cache['isDelete'] = 1;
		$data=$mod->where('id='.$id)->save($cache);
		if($data>0){
				$json = array('status'=>1,'message'=>'新闻删除成功');
		}else{
				$json = array('status'=>2,'message'=>'新闻删除失败或与上次提交无差别');
		}
		$this->ajaxReturn($json);
	}
	//获得新闻类型列表接口
	public function getNewsTypeList(){
		$mode=M('news_type');
		$type=$mode->select();
		if(!empty($type)){
			$json = array('status'=>1,'message'=>'登录成功','result'=>$type);
		}else{
			$json = array('status'=>2,'message'=>'数据为空');
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
}