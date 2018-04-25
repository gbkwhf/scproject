<?php
/**
 * 增加支付方式时候需要添加相应方式的购买方式
 * 和getOrderInfo方法
 */
namespace Acme\Repository;
use App\Http\Requests\Request;

/**
 * 统一支付处理类
 * User: hanli
 * Date: 16-2-23
 * Time: 上午10:39
 */
class UnitePay
{
    /**
     * 支付方式
     * @param $type
     */
    private $payWay;

    /**
     * 业务类型
     * @var
     */
    private $businessType;

    /**
     * 返回值
     * UnitePay constructor.
     * @param $type
     * @param $businessType
     */
    private $response=null;

    /**
     * 请求
     * UnitePay constructor.
     * @param $type
     * @param $businessType
     */
    private $request=null;

    /**
     * 选择支付方式
     */
    public function __construct($type,$businessType)
    {
        $this->payWay=$type; //alipay
        $this->businessType=$businessType; //ysbt_pay
    }

    /**
     * 统一购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param array $option
     * @return mixed
     */
    public function purchase($order_id,$title,$money,$extend=array()){
        $type=$this->payWay; //alipay

        //获取业务路由
        $route=config('notify-url.'.$this->businessType); //ys::goods_notify
        $notify=route($route,['type'=>$type]); //http://127.0.0.1/gongxiangshangcheng/public/api/gxsc/ys-goods-notify/wechatpay_js

        return  $this->$type($order_id,$title,$money,array_merge(['notifyUrl'=>$notify],$extend));
    }

    /**
     * 返回订单相关信息
     * @param $post   收到的信息
     * @return array
     * order_id 订单号
     * price  订单支付款
     * trade_no 流水号
     * return_msg _ok成功返回的信息
     * return_msg _fail 业务失败执行
     */
    private function getOrderInfo($post){
        switch ($this->payWay){

            case 'alipay':
                if($post['trade_status']!='TRADE_SUCCESS'){
                    return [];
                }
                return [
                    'order_id'=>$post['out_trade_no'],
                    'price'=>$post['total_fee'],
                    'trade_no'=>$post['trade_no'],

                    'return_msg_ok'=>'success',
                    'return_msg _fail'=>'fail',
                ];                
            case 'wechatpay':
            case 'wechatpay_js':
            case 'wechatpay_web':
                return [
                    'order_id'=>$post['out_trade_no'],
                    'price'=>$post['total_fee']/100,
                    'trade_no'=>$post['transaction_id'],

                    'return_msg_ok'=>\Omnipay\WechatPay\Helper::array2xml(['return_code'=>'SUCCESS','return_msg'=>'OK']),
                    'return_msg_fail'=>\Omnipay\WechatPay\Helper::array2xml(['return_code'=>'FAIL','return_msg'=>'OK'])

                ]; 
           case 'alipay_web':
               if($post['trade_status']!='TRADE_SUCCESS'){
                		return [];
                	}
                	return [
                	'order_id'=>$post['out_trade_no'],
                	'price'=>$post['total_fee'],
                	'trade_no'=>$post['trade_no'],
                
                	'return_msg_ok'=>'success',
                	'return_msg _fail'=>'fail',
              ];                              
        }
    }


    /**
     * 验签并返回订单信息验签失败返回
     * @return array|bool
     */
    public  function  completePurchase(){
        //实例化支付网关
        $gateway = \Omnipay::gateway($this->payWay);

        if($this->payWay=='wechatpay' or $this->payWay=="wechatpay_js" or $this->payWay=="wechatpay_web"){
            $request_params=file_get_contents('php://input');
            $response=\Omnipay\WechatPay\Helper::xml2array($request_params);
        }else{
            $request_params=$_REQUEST;
            $response=$request_params;
        }

        $request = $gateway->completePurchase(['request_params'=>$request_params])->send();

        $this->request=$request;

        if($request->isSuccessful()){

            return $this->getOrderInfo($response);
        }else{
            return false;
        }
    }
    /**
     * 获取完整响应
     * @return null
     */
    public function getResponse(){
        return $this->response;
    }

    /**
     * 获取完整请求
     * @return null
     */
    public function getRequest(){
        return $this->request;
    }

    /**
     * 银联支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function unionpay($order_id,$title,$money,$extend){
        $gateway = \Omnipay::gateway('unionpay');

        $options = [
            'orderNumber' => $order_id, //订单号
            'title'       => $title,    //订单标题
            'orderAmount' => $money*100,    //金额

            'orderTime'   => date('YmdHis'),
        ];

        $response = $gateway->purchase(array_merge($options,$extend))->send();

        $this->response=$response;

        return $response->isSuccessful() ? ['tn'=>$response->getData()['tn']] :false;
    }

    /**
     * 支付宝支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function alipay($order_id,$title,$money,$extend){
        $gateway = \Omnipay::gateway('alipay');

        
        $options = [
            'out_trade_no' => $order_id,     //订单号
            'subject'       => $title,        //订单标题
            'total_fee' => $money,            //金额
        ];

        $response = $gateway->purchase(array_merge($options,$extend))->send();
        $this->response=$response;

        return $response->isSuccessful() ? ['order_info_str'=>$response->getData()['order_info_str']] : false;
    }
    /**
     * 支付宝支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function alipay_web($order_id,$title,$money,$extend){
    	
    	
    	
    	
    	$gateway = \Omnipay::gateway('alipay_web');
    	$options = [
	    	'out_trade_no' => $order_id,     //订单号
	    	'subject'       => $title,        //订单标题
	    	'total_fee' => $money,            //金额
    	];
    	$response = $gateway->purchase(array_merge($options,$extend))->send();
    	

    	$this->response=$response;
    	
    	//dd($response->isSuccessful());

    	return $response->getRedirectUrl();
    }    

    /**
     * 微信支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function wechatpay($order_id,$title,$money,$extend){
        $gateway = \Omnipay::gateway('wechatpay');

        $options = [
            'out_trade_no' => $order_id,     //订单号
            'body'       => $title,        //订单标题
            'total_fee' => $money*100,            //金额
            'spbill_create_ip' => \Request::getClientIp(),
            'fee_type'          => 'CNY',
        ];

        $response = $gateway->purchase(array_merge($options,$extend))->send();

        $this->response=$response;

        if(!$response->isSuccessful()) return false;

        $data= $response->getAppOrderData();

        return $data;

    }
    /**
     * 公众号支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function wechatpay_js($order_id,$title,$money,$extend){
    	$gateway = \Omnipay::gateway('wechatpay_js');
    
    	$options = [
    	'out_trade_no' => $order_id."_".getRandomID(4),     //订单号,为了解决商户订单号重复201问题，给订单生成一个随机数
    	'body'       => $title,        //订单标题
    	'total_fee' => $money*100,            //金额
    	'spbill_create_ip' => \Request::getClientIp(),
    	'fee_type'          => 'CNY',
    	];
    
    	$response = $gateway->purchase(array_merge($options,$extend))->send();
    
    	$this->response=$response;
    
    	if(!$response->isSuccessful()) return false;
    
    	$data= $response->getJSOrderData();
    
    	return $data;
    
    }  


   
    
    
    
    /**
     * 微信网站支付购买
     * @param $order_id
     * @param $title
     * @param $money
     * @param $extend
     * @return array
     */
    private function wechatpay_web($order_id,$title,$money,$extend){
    	$gateway = \Omnipay::gateway('wechatpay_web');

    	$options = [
	    	'out_trade_no' => $order_id,     //订单号
	    	'body'       => $title,        //订单标题
	    	'total_fee' => $money*100,            //金额
	    	'spbill_create_ip' => \Request::getClientIp(),
	    	'fee_type'          => 'CNY',
    	];
    
    	$response = $gateway->purchase(array_merge($options,$extend))->send();
    
    	//dump($response->getData());
    	$this->response=$response;
    
    	if(!$response->isSuccessful()) return false;
  
    
    	$data= $response->getCodeUrl();

    	return $data;
    
    }   
    
}