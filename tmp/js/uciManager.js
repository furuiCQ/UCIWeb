// JavaScript Document
var uploadUrl="http://127.0.0.1/index.php/home/uci/upload";
var uploadLocalHost="http://127.0.0.1";
//发布文章
function sendNews(eidtor){
	// 获取编辑器区域完整html代码
	var html = editor.$txt.html();
	// 获取编辑器纯文本内容
	var text = editor.$txt.text();
	// 获取格式化后的纯文本
	var formatText = editor.$txt.formatText();
	//window.alert(html); 
	var url="http://127.0.0.1/index.php/home/uci/sendNews?title=123"+"&content="+html+"&typeid=1&author_id=1";
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			console.log(xmlHttp.responseText);
			window.alert(xmlHttp.responseText);
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
function removeTableData(){
	var table=document.getElementById('news_table');
	var tbody=table.getElementsByTagName("tbody");
	var i=0;
	while(i<tbody.length){//当table中删除tbody以后，下一个tbody变成0位元素
	  table.removeChild(tbody[0]);
	  var td=table.getElementsByTagName("tbody");
	  if(td.length==0){
	  	return;
	  }
	}
}
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null){
	 	return  unescape(r[2]);
	 }else{
		return null;
	 }
}
function updateNews(id,title,typeid,content,author_id){
	var url="http://127.0.0.1/index.php/home/uci/updateNews?id="+id+"&title="+title
	+"&content="+content+"&typeid="+typeid+"&author_id="+author_id;
	console.log(url);
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			var response=JSON.parse(xmlHttp.responseText);
			window.alert(response.message);
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
//删除新闻
function deleteNews(id){
	var url="http://127.0.0.1/index.php/home/uci/deleteNews?id="+id;
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			//console.log(xmlHttp.responseText);
			var response=JSON.parse(xmlHttp.responseText);
			window.alert(response.message);
			getNewsList();
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
//恢复删除的新闻
function restoreDeletNews(id){
	var url="http://127.0.0.1/index.php/home/uci/restoreDeleteNews?id="+id;
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			var response=JSON.parse(xmlHttp.responseText);
			window.alert(response.message);
			getDeletedNewsList();
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
	
}
//获取已删除的文章
function getDeletedNewsList(){
	removeTableData();
	var url="http://127.0.0.1/index.php/home/uci/getDeletedNewsList";
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			//console.log(xmlHttp.responseText);
			//window.alert(xmlHttp.responseText);
			var response=JSON.parse(xmlHttp.responseText);
			//console.log(response.status);
			var result=response.result;
			var table=document.getElementById('news_table');
			if(result.length>0){
				for(i=0;i<result.length;i++){
			//	document.write(cars[i] + "<br>");
				var date=new Date();
				date.setTime(result[i].time*1000);
				var editUrl="http://127.0.0.1/tmp/home/news/edit.html?id="+result[i].id;
				table.innerHTML=table.innerHTML + "<tbody id='tid'><tr class='table_data'><td>"+result[i].title+"</td><td>"+result[i].author_name+"</td> <td>"+date.toLocaleString()+"</td><td  id="+result[i].id+"><button onclick='restoreDeletNews("+result[i].id+")'>恢复</button><button><a href='http://127.0.0.1/tmp/home/news/edit.html?id="+result[i].id+"'>编辑</a></button></td></tr></tbody>";
			}
			}
			
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
//获取文章类型列表
function getNewsTypeList(){
	var xmlHttp;
	var url="http://127.0.0.1/index.php/home/uci/getNewsTypeList";
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			var response=JSON.parse(xmlHttp.responseText);
			var result=response.result;
			var dropdown_content=document.getElementById('dropdown-content');
			for(i=0;i<result.length;i++){
			dropdown_content.innerHTML=dropdown_content.innerHTML+
			"<a onclick='setSelect(this.innerText,"+result[i].id+")' id="+result[i].id+">"+result[i].name+"</a>";
			}
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
//获取文章列表
function getNewsList(){
	removeTableData();
	var url="http://127.0.0.1/index.php/home/uci/getNewsList";
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			//console.log(xmlHttp.responseText);
			//window.alert(xmlHttp.responseText);
			var response=JSON.parse(xmlHttp.responseText);
			console.log(response.status);
			var result=response.result;
			var table=document.getElementById('news_table');
			for(i=0;i<result.length;i++){
			//	document.write(cars[i] + "<br>");
				var date=new Date();
				date.setTime(result[i].time*1000);
				var editUrl="http://127.0.0.1/tmp/home/news/edit.html?id="+result[i].id;
				table.innerHTML=table.innerHTML + "<tbody id='tid'><tr class='table_data'><td>"+result[i].title+"</td><td>"+result[i].author_name+"</td> <td>"+date.toLocaleString()+"</td><td id="+result[i].id+"><button onclick='deleteNews("+result[i].id+")'>删除</button><button><a href='http://127.0.0.1/tmp/home/news/edit.html?id="+result[i].id+"'>编辑</a></button></td></tr></tbody>";
			}
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
	
}
function openUrl(url){
	location.href=url;
}
//获取文章详情
function getNewsDetials(id,editor){
	var url="http://127.0.0.1/index.php/home/uci/getNewsDetails?id="+id;
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange=function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			//console.log(xmlHttp.responseText);
			//window.alert(xmlHttp.responseText);
			var response=JSON.parse(xmlHttp.responseText);
			var result=response.result;
			//console.log(response.status);
			var title=result.title;
			var typeid=result.type_id;
			var content=result.content;
			var title_input=document.getElementById('title_input');
			title_input.value=title;
			//editor.$txt.append(content);
			editor.$txt.html(escape2Html(content));	
		}
	  }
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
//将转义符转成普通字符
function escape2Html(str) {
 var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
 return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
}
function httpGet(url){
	var xmlHttp;
	xmlHttp= createXMLHttpRequest();
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			return xmlHttp.responseText;
		}
	  };
	xmlHttp.open("GET", url, true);// 异步处理返回 
	xmlHttp.setRequestHeader("Content-Type",	
			"application/x-www-form-urlencoded;");
	xmlHttp.send(null);
}
function httpPost(url){
	xmlHttp.open("POST", url, true);  
	xmlHttp.onreadystatechange =function()
	  {
	  if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{	
			return xmlHttp.responseText;
		}
	  };
	xmlHttp.setRequestHeader("Content-Type",  
	"application/x-www-form-urlencoded;");  
	xmlHttp.send(xml);  
}
function createXMLHttpRequest() {
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	return xmlhttp;
}