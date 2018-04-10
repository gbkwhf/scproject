<?php

Route::group([

'prefix' => 'api/gxsc','namespace' => 'SecondExploit', 'middleware'=> ['check.session:ys_session_info']//,'check.version']

],function (){


	Route::post('getgoodsextendinfo','GoodsController@getGoodsExtendInfo');


});

