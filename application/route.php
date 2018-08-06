<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
#动态注册

use\think\Route;

#Route::rule(路由表达式,路由地址，请求类型，路由参数（数组），变量规则（数组）);
#模块名/控制器名/方法名

//获取banner轮播图和信息
Route::get('api/:vertion/banner/:id','api/:vertion.Banner/getBanner');



Route::group('api/:vertion/theme',function (){
    //获取专题内容    ？ids=ids1,ids2,ids3...
    Route::get('','api/:vertion.Theme/getSimpleList');
    //获取专题页面内容
    Route::get('/:id','api/:vertion.Theme/getComplexOne');
});


Route::group('api/:vertion/product', function (){
    //获取最新商品    ？count=...
    Route::get('/recent','api/:vertion.Product/getRecent',[],['id' => '\d+']);
    //获取分类下的所有商品
    Route::get('/by_category/:id','api/:vertion.Product/getALLInCategory');
    //获取商品详细信息
    Route::get('/:id','api/:vertion.Product/getOne');
});


//获取商品分类
Route::get('api/:vertion/category/all','api/:vertion.Category/getALLCategories');

//用户获取Token令牌
Route::post('api/:vertion/token/user','api/:vertion.Token/getToken');
//第三方获取Token
Route::post('api/:vertion/token/app','api/:vertion.Token/getAppToken');
//检验token
Route::post('api/:vertion/token/verify','api/:vertion.Token/verifyToken');

//添加或更改用户收货地址
Route::post('api/:vertion/address','api/:vertion.Address/createOrUpdateAddress');
//获取用户收货地址
Route::get('api/:vertion/address','api/:vertion.Address/getUserAddress');

//下单
Route::post('api/:vertion/order','api/:vertion.Order/placeOrder');
//获取所有用户的订单
Route::get('api/:vertion/order/paginate','api/:vertion.Order/getSummary');
//获取用户历史订单简要信息并分页
Route::post('api/:vertion/order/by_user','api/:vertion.Order/getSummaryByUser');
//历史订单详细信息
Route::post('api/:vertion/order/:id','api/:vertion.Order/getDetail',[],['id' => '\d+']);

Route::group('api/:vertion/pay',function (){
    //预订单
    Route::post('/pre_order','api/:vertion.Pay/getPreOrder');
    //微信回掉接口
    Route::post('/notify','api/:vertion.Pay/receiveNotify');
});

