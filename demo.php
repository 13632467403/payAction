<?php
/**
 * PayAction插件demo
 */
date_default_timezone_set('PRC');
header("Content-type: text/html; charset=utf-8");

$Trade_type = $_GET['payType'] ? : "WxPay_APP";
$param = array(
    "order_name"=>"测试订单测试订单测试订单测试订单测试订单试订单测试订单",
    "trade_no"=>time(),
    "amount"=>"1",
    "add_time"=>time(),
);

//引入PayAction插件
require_once "PayAction/PayAction.php";
$PayAction = new PayAction($Trade_type);
switch ($Trade_type){
    case "JdPay_Debit":
    case "JdPay_Credit":
        /**
         * 京东支付
         */
        $param = array(
            "order_name"=>"测试订单名称",
            "trade_no"=>date("YmdHis"),
            "amount"=>"0.01",
            "card_bank"=>"BOC",
            "card_no"=>"6227xxxxxxxxxxxxx",
            "card_exp"=>"1910",
            "card_cvv2"=>"123",
            "card_name"=>"测试",
            "card_idtype"=>"I",
            "card_idno"=>"440xxxxxxxxx",
            "card_phone"=>"13800138000",
            "trade_code"=>"",
            "add_time"=>time(),
        );
        break;
    case "WxPay_APP":
    case "WxPay_NATIVE":
    case "WxPay_JSAPI":
        break;
    case "AliPay_WAP":
    case "AliPay_APP":
    case "AliPay_WEB":
        break;
    default:
        break;
}


$result = $PayAction->getPayResult($param);
print_r($result);die;





