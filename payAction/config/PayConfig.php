<?php
defined('ROOT_PATH') or exit("ROOT_PATH is undefined!");
/* *
 * 系统配置文件
 */

class PayConfig
{
    /**
     * 公共不能为空的参数数组
     * 操作类型=>字段=>描述
     * @var array
     */
    public static $commonInputNoEmptyArr = array(
            "Pay"   =>  array(
                "trade_no"    =>  array(
                    "code"  =>  "210",
                    "desc"  =>  "订单号",
                ),
                "amount"    =>  array(
                    "code"  =>  "211",
                    "desc"  =>  "订单金额",
                ),
                "order_name"    =>  array(
                    "code"  =>  "212",
                    "desc"  =>  "订单名称",
                ),
            ),
            "Refund"   =>  array(
                "refund_no"    =>  array(
                    "code"  =>  "220",
                    "desc"  =>  "退款订单号",
                ),
                "amount"    =>  array(
                    "code"  =>  "221",
                    "desc"  =>  "退款金额",
                ),
            ),
            "Query"   =>  array(
                "trade_no"    =>  array(
                    "code"  =>  "230",
                    "desc"  =>  "查询订单号",
                ),
            ),
    );

    /**
     * 支付类型数组
     * 支付平台=>支付方式列表
     * @var array
     */
    public static $payTypeArr = array(
        //微信支付
        "WxPay_NATIVE", //扫码支付
        "WxPay_JSAPI", //JSAPI支付
        "WxPay_APP", //APP支付
        //支付宝
        "AliPay_WEB",       //即时到账支付
        "AliPay_WAP",       //手机网页支付
        "AliPay_APP",       //APP支付
        //京东快捷支付
        "JdPay_Credit", //信用卡支付
        "JdPay_Debit",  //借记卡支付
    );

    /**
     * 引入支付平台的文件
     * @path 路径
     * @desc 描述
     * @code 返回代码
     * @var array
     */
     public static  $requireFileArr =  array(
             array(
                 "path"  =>  MERCHANT_CONFIG_PATH,
                 "desc"  =>  "配置文件",
                 "code"  =>  204,
             ),
             array(
                 "path"  =>  PLUGIN_PATH,
                 "desc"  =>  "类文件",
                 "code"  =>  202,
             ),
         );
}
