<?php
//用户路由

Route::group(['prefix' => 'api/v1/baseuser','namespace' => 'Baseuser' ,
'middleware'=> ['print.request','check.version']
],
function () {

	
	
});



