/*-------------------------------------------------------------------------
//处理所有按钮的JS操作
//--------------------------------------------------------------------------
//接收的参数：
----------------------------------------------------------------------------
	property:当前按钮的属性，string，各个属性之间用“，”分隔。属性包括
	    		        id：当前按钮的id
	    		       pen：当前按钮的父节点的按钮英文名称
	    		        en：当前按钮的英文名称      以上两个参数是用来生成操作页面的URL，格式为：“pen/en”，其中pen还是新窗口的图片文件名
	    	       optType：当前按钮的操作类型，用来判断使用的提交方式，
	    	               0：默认框架跳转
	    	               1：打开新窗口
	    	               2：ajax方式提交
	    	               3：本页跳转
	    	               4：form表单提交，在进入本function之前进行判断，当optType=4时，将不会执行本函数，所以在本函数中没有optType=4的状态
	    	               5：父页面跳转
				reloadType：optType=2时有效，执行完以后的页面刷新方式
	    		     title：optType=1时有效，新窗口的标题文字
	    		     width：optType=1时有效，新窗口的宽度
	    		    height：optType=1时有效，新窗口的高度
	    		 isConfirm：optType=2时有效，执行ajax提交之前是否需要提示确认，一般用于删除操作
	    	   confirmText：optType=2时有效，提示确认的文字
	  data：当前操作所针对的目标信息ID，如执行修改时，传入的被修改信息的ID
      page：当前操作是否需要从页面中动态获取参数,比如，针对一个信息，进行参数的批量处理，dataId记录该信息的ID，page=1，则，函数会自动从页面中的隐含变量中获取批量处理选中的参数信息
---------------------------------------------------------------------------- 
//传递的参数:
----------------------------------------------------------------------------
		   id：当前按钮的ID
		 data：当前操作针对的目标信息ID
	parameter：从页面获取的其他参数
    
    负责接收的Controller要注意参数的名称必须对应
 ------------------------------------------------------------------------*/
var operateAjax=(function(property,data,page){
	//获取属性参数
	if(data==''){
		data='NONE';
	}
	if(page>0){
		var par=window.document.getElementById('optAjaxData').value;
		if(par.substr(0,1)==','){
			par=par.substr(1);
		}
	}
	else{
		var par='NONE';
	}
	
	if(par == ''){
		par='NONE';
	}
	
	var property=property.split(',');
	var id=property[0], pEN=property[1], en=property[2], optType=property[3], reloadType=property[4];
	var winUrl=APP_PATH+'/Home/'+pEN+'/'+en+'/id/'+id+'/data/'+data+'/parameter/'+par;

	if(optType==1){
		var title=property[5], width=property[6], height=property[7];
		windown(title,winUrl,width,height,pEN);
	}
	else if(optType==2){

			var isConfirm=property[5], confirmText=property[6];
			if(isConfirm>0){
				if(confirm(confirmText)){
					doAjax();
				}
			}
			else{
				doAjax();
			}
		
	}
	else if(optType==0){
		window.optFrame.location=winUrl;
	}
	else if(optType==5){
		window.parent.location=winUrl;
	}
	else{
		window.location=winUrl;
	}
	
	function doAjax(){
		//先声明一个异步请求对象
	    var xmlHttpReg = null;
	    try {
	    	// Firefox, Opera 8.0+, Safari
	    	xmlHttpReg = new XMLHttpRequest(); // 实例化对象
	    } catch(e) {
	    	try {
	    		xmlHttpReg = new ActiveXObject("Msxml2.XMLHTTP");
	    	} catch(e) {
	    		try {
	    			xmlHttpReg = new ActiveXObject("Microsoft.XMLHTTP");
	    		} catch(e) {
	    			alert("您的浏览器不支持AJAX！");
	    			return false;
	    		}
	    	}
	    }
	    xmlHttpReg = new XMLHttpRequest(); //实例化一个xmlHttpReg
	    if (xmlHttpReg != null) {
	    	var url=APP_PATH+"/Home/Ajax/doAjax";
	    	url=url+'/id/'+id+'/data/'+data+'/parameter/'+par;
	        xmlHttpReg.open("post", url, true);
	        xmlHttpReg.send(null);
	        xmlHttpReg.onreadystatechange = doResult; //设置回调函数
	    }
	    
	  //回调函数
	    //一旦readyState的值改变,将会调用这个函数,readyState=4表示完成相应
	    //设定函数doResult()
	    function doResult(){
	        if(xmlHttpReg.readyState==4 && xmlHttpReg.status == 200){
				var result=xmlHttpReg.responseText;
				//res=hexToDec(res);
	    		if(result=='"E"'){
	    			alert("错误：服务器异常,请稍后再试！");
	    		}
	    		else{
	    			alert("操作成功！");
	    			switch(result){
	    				case '"1"':
	    					window.location.reload();
	    					break;
	    				case '"2"':
	    					window.top.mainFrame.treeFrame.location.reload();
	    					break;
	    				case '"3"':
	    					window.top.mainFrame.optFrame.location.reload();
	    					break;
	    				case '"4"':
	    					window.location.reload();	    					
	    					window.top.mainFrame.optFrame.location.reload();
	    					window.parent.hiddenWindow()
	    					break;
	    				case '"5"':
	    					window.location.reload();
	    					window.top.mainFrame.treeFrame.location.reload();
	    					break;
	    				case '"6"':
	    					window.top.mainFrame.treeFrame.location.reload();
	    					window.top.mainFrame.optFrame.location.reload();
	    					break;
	    				case '"7"':
	    					window.location.reload();
	    					window.top.mainFrame.treeFrame.location.reload();
	    					window.top.mainFrame.optFrame.location.reload();
	    					break;
	    			}
	    		}
	        }
	    }
	    /*字符编码转换
	    function hexToDec(str) {
		    str=str.replace(/\\/g,"%");
		    return unescape(str);
		}*/
	}
});