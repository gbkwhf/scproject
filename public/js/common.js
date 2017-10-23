/*
common js
 */
commonUrl = 'http://192.168.20.8/serverApi/shopapi/1.0/';

//校验手机号
function testTel(val){

    var reg = /^1[034578][0-9]{9}$/;

    if(!reg.test(val)){
        return false;
    }else{
        return true;
    }
}