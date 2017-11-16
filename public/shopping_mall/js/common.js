
function htmlencode(s){
    var div = document.createElement('div');  
    div.appendChild(document.createTextNode(s));  
    return div.innerHTML;  
}  
function htmldecode(s){  
    var div = document.createElement('div');  
    div.innerHTML = s;  
    return div.innerText || div.textContent;  
} 
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();


function setCookie(el,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    //document.cookie = name+"="+value+expires+"; path=/";
    valueencode = encodeURIComponent(value);
    console.log(valueencode);
    valueencode = window.btoa(valueencode);
    console.log(valueencode);
    document.cookie = el+'='+valueencode+expires+';path=/';
}

function getCookie(name) {
    var arr=document.cookie.split('; ');
    var i=0;
    for(i=0;i<arr.length;i++)
    {
        //arr2->['username', 'abc']
        var arr2=arr[i].split('=');

        if(arr2[0]==name)
        {
            //var getC = $.base64.decode(arr2[1]);
            var getC = decodeURIComponent(window.atob(arr2[1]));
            return getC;
        }
    }
    return '';
}

function removeCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
}




function trimStr(str){return str.replace(/(^\s*)|(\s*$)/g,"");}

function testIdcard(value){
    var reg=/^[1-9]{1}[0-9]{14}$|^[1-9]{1}[0-9]{16}([0-9]|[xX])$/;
    if(reg.test(value)){
        return true;
    }else{
        return false;
    }
}

function testTel(val){

    var reg = /^1[034578][0-9]{9}$/;

    if(!reg.test(val)){
        return false;
    }else{
        return true;
    }

}
function testMail(val){

    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;

    if(!reg.test(val)){
        return false;
    }else{
        return true;
    }

}

function testKey(val){
    if(!val){
        return false;
    }else{
        return true;
    }
}

function numchange(doc){
	doc.value = doc.value.replace(/\D/gi, "");
}

//array删除指定val
Array.prototype.removeByValue = function(val) {
    for(var i=0; i<this.length; i++) {
        if(this[i] == val) {
            this.splice(i, 1);
            break;
        }
    }
}

//关闭微信window
function closewin(){
    //关闭窗口
    WeixinJSBridge.invoke('closeWindow',{},function(res){});
}

//关闭弹框
function cancelPop(){
	layer.closeAll();
}

function getWxConfig(){
    $.ajax({
        type:'get',
        url:commonsUrl + 'api/gxsc/get/user/sign/package' + versioninfos,
        success:function(data){
            wx.config({
                debug: false,
                appId: data.result.appId,
                timestamp: data.result.timestamp,
                nonceStr: data.result.nonceStr,
                signature: data.result.signature,
                jsApiList: [
                    // 所有要调用的 API 都要加到这个列表中
                    'getLocation',//经纬度
                    'scanQRCode',//微信扫一扫
                    'onMenuShareTimeline',//分享到朋友圈
                    'onMenuShareAppMessage',//分享给朋友
                    'onMenuShareQQ',//分享到QQ
                    'onMenuShareWeibo',//分享到腾讯微博
                    'onMenuShareQZone',//分享到QQ空间
                ]
            });
        }
    })
}
