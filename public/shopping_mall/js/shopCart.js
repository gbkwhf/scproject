  $(function() {
  	
  //点击管理出现删除按钮
	 	$('#manngerId').click(function(){
	 		if($('#deleIddd').css("display")=="none"){
	 			$('#deleIddd').show();
	 		}else{
	 			$('#deleIddd').hide();
	 		}
		})

	 	
	//全选
    var allInput = $(".input1");
    allInput.click(function () {
        if (this.checked == true) {
            $(".input").prop('checked', true);
            $("label").addClass("checked");
            allsetTotal();
        } else {
            $(".input").prop('checked', false);
            $("label").removeClass("checked");
            $(".totalPrice").text("0.00"); 
        }
    });
    //点击确定删除的事件
	$('#confirmId').click(function(){
			//循环这些数组，判断前面的复选框选中之后，就把相应的数组删除
			var nval=$('.totalPrice').text();
			console.log(nval);
			if(nval!=0.00||nval!=0){
				$(".shopCartList").each(function () {
		    	var isChecked=$(this).find('label[class*=label1]').hasClass("checked");
		        if(isChecked){
			       	$(this).remove();
			    	  layer.closeAll()
		        }
	    	})
			}else{
				layer.msg('请选择你要删除的节点');
			}
			var quanxuan =$('.shopFotter label'); 
			if(quanxuan.hasClass('checked')){
				$('.totalPrice').text('0.00')
				$('.shopFotter label').removeClass('checked')
			}
			allsetTotal();
	})
	//点击取消删除框的事件
	$('#cancelsId').click(function(){
		 layer.closeAll()
	})
  //单选  
    
    $(".input").click(function () {
    	//给单选框添加checked选项
	        $(this).parent().toggleClass("checked");
	    //如果这个单选框选中
				   if($(this).parent().hasClass('checked')){
				   	$(this).parent().parent().parent().find('input[class*=inputNum]').removeAttr("readonly");
				   	//取当前的选中的数量
				   	    var t = $(this).parent().parent().parent().find('input[class*=inputNum]').val();
				   	    console.log(t);
				   	//取当前选中的单价
		            var p = $(this).parent().parent().parent().find('span[class*=priceEvery]').text();
		            console.log(p);
		            if(parseInt(t)==""||undefined||null || isNaN(t) || isNaN(parseInt(t))){  
		                t=1;  
		            }  
		        //计算总价
		            var s=parseInt(t) * parseFloat(p);
		            console.log(s+'总价');
		        //获取合计
		            var totalParice=$(".totalPrice").text();
		            console.log(totalParice+"合计");
		        //合计加上当前选中的总价（最后的合计）
		            var t=parseFloat(s)+parseFloat(totalParice);
		            console.log(t+'最后的合计');
		        //给合计赋值    
		            $(".totalPrice").text(t.toFixed(2))
				   }else{  //当前的复选框没有选中
				   	//取当前的选中的数量
				   			var t = $(this).parent().parent().parent().find('input[class*=inputNum]').val();
				   	    console.log(t);
				   	//取当前选中的单价
		            var p = $(this).parent().parent().parent().find('span[class*=priceEvery]').text();
		            console.log(p);
		            if(parseInt(t)==""||undefined||null || isNaN(t) || isNaN(parseInt(t))){  
		                t=1;  
		            } 
		        //计算总价
		            var s=parseInt(t) * parseFloat(p);
		            console.log(s+'总价');
		         //获取合计
		            var totalParice=$(".totalPrice").text();
		            console.log(totalParice+"合计");
		         //合计减去当前选中的总价（最后的合计）
		            var t=parseFloat(totalParice)-parseFloat(s);
		            console.log(t+'最后的合计');
		        //给合计赋值      
		            $(".totalPrice").text(t.toFixed(2))
				   }

        //判断如果所有的上面框选择，复选框是否选择
        var s = $(".input").length;
        var a = $(".input:checked").length;
        if (s == a) {
            allInput.prop('checked', true);
            allInput.parent().addClass("checked");
        } else {
            allInput.prop('checked', false);
            allInput.parent().removeClass("checked");
        }
         
    });
   
 
 	//点击增加按钮触发事件++++
    $(".addClass").click(function(){
    	//前面的复选框有没有选中
         var isTrue=$(this).parent().parent().parent().find('label[class*=label1]').hasClass('checked');   
         console.log(isTrue);
	         if(isTrue){
						 var num = $(this).parent().find('input[class*=inputNum]');  
			//单品数量增加
			        num.val(parseInt(num.val())+1);
			//计算总价
			        var goods_price = parseInt($(this).parent().parent().find('span[class*=priceEvery]').text());
			        console.log(goods_price+'这是单价');
			        var val=parseInt($(num).val());
			        console.log(val+'数量');
			        console.log(goods_price*val+'总价');
			        var nextVal=parseInt($(".totalPrice").text())+(goods_price);
			        $(".totalPrice").text(nextVal.toFixed(2));
					 }
	    });
 	
    //点击减少按钮触发事件--------
     $(".minus").click(function(){
		     	//判断前面的复选框有没有选择
						 var isTrue=$(this).parent().parent().parent().find('label[class*=label1]').hasClass('checked');
					//获取到input框的值
						 var m = $(this).parent().find('input[class*=inputNum]');  
						 var val=parseInt($(m).val());
					   console.log(val+'数量+++++++++++++++++++++++++');
					//判断如果这个框的值为1，就不能再有点击事件
					    if(val<=1){
					     	  isTrue=false;
					     	  layer.msg('亲，这个数量不能再少了');
					    }
					//表示前面的复选框已经选中，就可以进行减法计算
				  if(isTrue){
						//如果这个框的值为下面的情景，都赋值为1
					  		var t = $(this).parent().find('input[class*=inputNum]');   
								if(t.val()==""||undefined||null){  
								    t.val(1);  
								}  
								t.val(parseInt(t.val()) - 1)  
								if(parseInt(t.val()) <=1) {  
								    t.val(1);  
								} 
						//这是进行减的计算，添加到合计中
								var goods_price = parseInt($(this).parent().parent().find('span[class*=priceEvery]').text());
				        console.log(goods_price+'这是单价--------------------------');
				        var neVal=parseInt($(".totalPrice").text())-(goods_price);
				        console.log(neVal+'合计的值******************************');
				        $(".totalPrice").text(neVal.toFixed(2));
					}
    	});        
    	
 
   //input框输入的时候计算价格	
    $(".inputNum").focus(function(){
    	var that=this;
    	var isTrue=$(this).parent().parent().parent().parent().find('label[class*=label1]').hasClass('checked');
     	  console.log(isTrue);
     	   if(isTrue){
		     	  var k  = $(this).parent().find('input[class*=inputNum]');
		        if(parseInt(k.val())==""||undefined||null || isNaN(k.val())||parseInt(k.val())<=1) {  
		            k.val(1);  
		        } 
		        var v= $(k).val();
		        console.log(v);
			  	 changeFn(v,that);
			  }
     })
     function changeFn(a,that){
     	console.log('我去调change事件');
     		var Y=parseInt($(".totalPrice").text());
     	  $(".inputNum").change(function(){
     	  	console.log($(that));
     	  		console.log(a+'这是原来的input框的值');
			    
						var b = $(that).parent().find('input[class*=inputNum]').val();
						console.log(b+'这是现在的input框的值');
						var y=$(that).parent().parent().parent().parent().find('span[class*=priceEvery]').text()
						console.log(y+'这是现在选取的单价');
						
						Y += (b-a)*y;
						console.log(Y+'合计');
						// Y是总价，y是单价；a是原始val，b是改变时的val
						 $(".totalPrice").text(Y.toFixed(2))
	
     	  })
   } 
  
//点击删除按钮出现的弹框
   
   $(".delete").click(function(){
		var Layer = layer.open({
					type: 1,
					title: false,
					content: $('.popBox'),
//					btn:['取消','确定'],
					btnAlign: 'c',
					area: ["278px", "97px"],
					closeBtn: 0,
					shadeClose: true, //点击遮罩层消失
					yes: function(Layer) {
						//vm.updateGoodsClass();
						layer.close(Layer);
					},
					//关闭按钮的回调函数
					cancel: function() {
						layer.close();
					}
				});
	})
 
   
   //全选的价格计算
   function  allsetTotal(){
   	 		var s = 0; 
        $(".shopCartBox .shopCartList").each(function () {
        	var isChecked=$(this).find('label[class*=label1]').hasClass("checked");
	        if(isChecked){
		        //计算选中的价格
		        var t = $(this).find('input[class*=inputNum]').val();  
		        console.log(t+'复选框选择到的数量');
		        var p = $(this).find('span[class*=priceEvery]').text();
		         console.log(p+'复选框选择到的单价');
		        
		        if(parseInt(t)==""||undefined||null || isNaN(t) || isNaN(parseInt(t))){  
		            t=0;  
		        }  
		        s += parseInt(t) * parseFloat(p);  
		        $(".totalPrice").text(s.toFixed(2));  
	        }else{
	        		$(".totalPrice").text("0.00");  
	        }
    		})
        
   }

   //虚拟的数组
  var arrOO=[

				{ "firstName": "Isaac", "lastName": "Asimov", "genre": "science fiction" },
				
				{ "firstName": "Tad", "lastName": "Williams", "genre": "fantasy" },
				
				{ "firstName": "Frank", "lastName": "Peretti", "genre": "christian fiction" }

];
  
 $.each(arrOO, function(k,v) {
		var t="<div class='shopCartList'><div class='shopCartLeft'><label class='label1'><input  type='checkbox'  class='input'   /></label></div><div class='shopCartRight'><div class='catRightTop'><div class='rightCart1'><span class='imgBoxBorder'><img src='images/shopLogoYY.png' alt='' class='carImage'/></span></div><div class='rightCart2'><div class='rightTitleCat'><span>"+arrOO[k].firstName+"</span></div><div class='catPrice'><span class='catPrice1'><span style='font-size: 10px;'>￥</span><span class='priceEvery'>6.00</span></span></div></div></div><div class='AddJianBox'><span class='jianId minus' id='minus'>-</span><span style='display: inline-block;' class='numkk'><input type='text' name='' id='' value='2' class='inputNum' readonly='readonly'/></span><span class='jianId addClass' id='plus'>+</span></div></div></div>"
		$('.shopCartBox').append(t);
 });
 
  
});