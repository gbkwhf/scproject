<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/common.css">
	<link rel="stylesheet" href="css/newAddress.css">
	<meta charset="UTF-8">
	<title>新增收货地址</title>
</head>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script src="js/plug.js"></script>
<script src="js/Popt.js"></script>
<script src="js/cityJson.js"></script>
<script src="js/citySet.js"></script>
<style type="text/css">
    ._citys {width:100%; height:100%;display: inline-block; position: relative; }
    ._citys p{height: 35px;text-align: center;line-height: 35px;font-size: 15px;}
    ._citys span { line-height: 15px; text-align: center; border-radius: 3px; position: absolute; right: 1em; top: 10px;  cursor: pointer;font-size: 20px}
    ._citys0 { width: 100%; height: 34px; display: inline-block;  padding: 0; margin: 0; }
    ._citys0 li { float:left; height:34px;line-height: 34px;overflow:hidden; font-size: 15px; color: #888; text-align: center; cursor: pointer;margin-left: 20px  }
    .citySel { border-bottom: 2px solid #eb3738; }
    ._citys1 { width: 100%;height:80%; display: inline-block; padding: 10px 0; overflow: auto;}
    ._citys1 a {  height: 35px; display: block; color: #666; padding-left: 6px; margin-top: 3px; line-height: 35px; cursor: pointer; font-size: 13px; overflow: hidden; }
    ._citys1 a:hover { color: #fff; background-color: #eb3738; }
    .ui-content{
        border: 1px solid #EDEDED;
    }li{
         list-style-type: none;
     }
    .backop{width:100%;height: 30%;position: fixed;top: 0;background: rgba(0,0,0,0.5);display: none}
</style>



<body>
    <div class="backop _citys"></div>
	<div class="form">
		<div>
			<label for="">收货人:</label>
			<input type="text" name="name" id="">
		</div>
		<div>
			<label for="">手机号码:</label>
			<input type="text" name="phone" id="" maxlength="11">
		</div>
		<div>
			<label for="" >所在地区:
                <span id="city">请选择 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </label>
			<img src="images/addback.png" alt="">
		</div>
		<div>
			<label for="">详细地址:</label>
			<input type="text" name="site" id="">
		</div>

		<button>保存</button>
	</div>

</body>

</html>
<script type="text/javascript">
    $("#city").click(function (e) {
        SelCity(this,e);
        console.log(this);
        $(".backop").show()
    });

    if($_GET["id"]==1){
        $.ajax({
            type: "post",
            url: commonsUrl + "/api/gxsc/get/delivery/goods/address" + versioninfos,
            data: {
                address_id:$_GET["address_id"],
                ss: getCookie('openid')
            },
            success: function(data) {
                console.log(data)
                $("input[name='name']").val(data.result[0].name)
                $("input[name='phone']").val(data.result[0].mobile)
                $("#city").text(data.result[0].address.split(" ")[0])
                $("input[name='site']").val(data.result[0].address.split(" ")[1])
            }
        });
    }
    
    
    $("button").click(function () {
        let name=$("input[name='name']").val()
		let phone=$("input[name='phone']").val()
		let site=$("input[name='site']").val()
        var pattern = /^1[34578]\d{9}$/;
        if (name==""){
            layer.msg("名字为空")
		} else if(phone==""){
		    layer.msg("手机号为空")
		}else if(!pattern.test(phone)){
            layer.msg("手机号格式不正确")
		}else if(site==""){
            layer.msg("地址为空")
		}else {
            let city=$("#city").text()+" "+site
            let url="/api/gxsc/add/delivery/goods/address"
            let msg="保存成功"
            if($_GET["id"]==1){
                url="/api/gxsc/edit/delivery/goods/address"
                msg="编辑成功"
                request(url,msg,phone,name,city)
            }else{
                request(url,msg,phone,name,city)
            }
            
		}

        function request(url,msg,phone,name,city){
            $.ajax({
                type: "post",
                url: commonsUrl + url + versioninfos,
                data: {
                    mobile:phone,
                    name:name,
                    address:city,
                    address_id:$_GET["address_id"],
                    ss: getCookie('openid')
                },
                success: function(data) {
					console.log(data.result.address_id)
					layer.msg(msg)
					let address_id=data.result.address_id
					setTimeout(function () {
                        if($_GET['id']==3){
                            location.href='formOrder.php'
                        }else{
                            location.href='address.php?address_id='+address_id
                        }
                        
                    },1000)
                }
            });
        }
    })
</script>
