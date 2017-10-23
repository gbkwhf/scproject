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
/**
 * Created by Administrator on 2015/12/17 0017.
 */
function setCookie(name, value, iDay)
{
    var oDate=new Date();
    if(iDay == null || iDay == ''){
        iDay = 30;
    }
    oDate.setDate(oDate.getDate()+iDay);

    document.cookie=name+'='+encodeURIComponent(value)+';expires='+oDate;
}

function getCookie(name)
{
    var arr=document.cookie.split('; ');
    var i=0;
    for(i=0;i<arr.length;i++)
    {
        //arr2->['username', 'abc']
        var arr2=arr[i].split('=');

        if(arr2[0]==name)
        {
            var getC = decodeURIComponent(arr2[1]);
            return getC;
        }
    }

    return '';
}

function removeCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
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

