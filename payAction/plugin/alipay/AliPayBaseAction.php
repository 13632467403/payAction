<?php
/**
 * 支付宝支付基础操作类
 */
class AliPayBaseAction
{
    /**
     * 交易类型
     */
    public $Trade_type;

    /**
     * 初始化
     */
    public function __construct()
    {
    }

    /**
     * 支付处理
     * @param $param
     * @return mixed|void
     */
    public function PayBaseAction($param)
    {
        require_once("lib/alipay_submit.class.php");

        /**************************请求参数**************************/
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $param['trade_no'];

        //订单名称，必填
        $subject = $param['order_name'];

        //付款金额，必填
        $total_fee = $param['amount'];

        //商品描述，可空
        $body = "";
        $alipay_config = AliPayConfig::$alipay_config;
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $parameter = array(
            /**
             * "service"       => "alipay.wap.create.direct.pay.by.user",
            "partner"       => $payment_config ['alipay_partner'],
            "seller_id"  => $payment_config ['alipay_partner'],
            "payment_type"	=> "1",
            "notify_url"	=> $notify_url,
            "return_url"	=> $call_back_url,
            "_input_charset"	=> strtolower('utf-8'),
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject, //名称
            "total_fee"	=> $price,	//价格
            "show_url"	=> "http://m.zuzuche.com",
            "app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
            "body"	=> "",//商品描述
            'it_b_pay'           => $it_b_pay,
             */
            "service"       => $param['service'],
            "partner"       => $alipay_config['partner'],
            "seller_id"  => $alipay_config['seller_id'],
            "payment_type"	=> $alipay_config['payment_type'],
            "notify_url"	=> $alipay_config['notify_url'],
            "return_url"	=> $alipay_config['return_url'],
            //"anti_phishing_key"=>AliPayConfig::anti_phishing_key,
            //"exter_invoke_ip"=>AliPayConfig::exter_invoke_ip,
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "body"	=> $body,
            //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
            //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
            //如"参数名"=>"参数值"

        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter);
        return $html_text;
    }

    /**
     * 回调处理
     * @param $param
     * @return mixed|void
     */
    public function NotifyBaseAction($param)
    {
        require_once("lib/alipay_notify.class.php");
        $alipay_config = AliPayConfig::$alipay_config;
        $AlipayNotifyObj = new AlipayNotify($alipay_config);
        return $AlipayNotifyObj->verifyNotify();
    }

    /**
     * 退款处理
     * @param $param
     * @return mixed|void
     */
    public function RefundBaseAction($param)
    {
        require_once("lib/alipay_submit.class.php");

        /**************************请求参数**************************/

        //批次号，必填，格式：当天日期[8位]+序列号[3至24位]，如：201603081000001

        $batch_no = $param['WIDbatch_no'];
        //退款笔数，必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）

        $batch_num = $param['WIDbatch_num'];
        //退款详细数据，必填，格式（支付宝交易号^退款金额^备注），多笔请用#隔开
        $detail_data = $param['WIDdetail_data'];


        /************************************************************/
        $alipay_config = AliPayConfig::$alipay_config;
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => trim($alipay_config['service']),
            "partner" => trim($alipay_config['partner']),
            "notify_url"	=> trim($alipay_config['notify_url']),
            "seller_user_id"	=> trim($alipay_config['seller_user_id']),
            "refund_date"	=> trim($alipay_config['refund_date']),
            "batch_no"	=> $batch_no,
            "batch_num"	=> $batch_num,
            "detail_data"	=> $detail_data,
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))

        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }

    /**
     * 查账处理
     * @param $param
     * @return mixed|void
     */
    public function QueryBaseAction($param)
    {
        require_once("lib/alipay_submit.class.php");
        $alipay_config = AliPayConfig::$alipay_config;
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "account.page.query",
            "partner" => trim($alipay_config['partner']),
            "page_no"	=> 1,
            "page_size"	=> 1,
            "gmt_start_time"	=>  date("Y-m-d H:i:s",time()-24*3600),
            "gmt_end_time"	=>  date("Y-m-d H:i:s"),

        );
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $parameter = $alipaySubmit->buildRequestPara($parameter);
        $goUrl  = "https://mapi.alipay.com/gateway.do?";
        $url = $goUrl.http_build_query($parameter);
        header("Location: ".$url);
    }


}
