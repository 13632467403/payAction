<?php
/* *
 * 配置文件
 * 版本：3.4
 * 修改日期：2016-03-08
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */

class AliPayConfig{
    public static $alipay_config = array(
        //合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
        "partner"=>'',

        //收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
        "seller_id"=>'',

        // MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
        "key"=>'',

        // 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        "notify_url"=> "http://www.t.com/alipay.wap.create.direct.pay.by.user-PHPUTF-8/notify_url.php",

        // 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        "return_url"=> "http://www.t.com/alipay.wap.create.direct.pay.by.user-PHP-UTF-8/return_url.php",

        //签名方式
        "sign_type"=>'MD5',

        //字符编码格式 目前支持utf-8
        "input_charset"=>'utf-8',

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        "cacert"=>'\\cacert.pem',

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        "transport"=>'http',

        // 支付类型 ，无需修改
        "payment_type"=> "1",

        // 产品类型，无需修改
        "service"=> "create_direct_pay_by_user",
    );
}