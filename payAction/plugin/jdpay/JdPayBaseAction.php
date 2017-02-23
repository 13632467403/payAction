<?php
include("lib/des.php");
include("lib/service.php");
require_once("lib/xml.php");
require_once("lib/md5.php");

/**
 * 京东支付基础操作类
 * Author: LiuZhengYong
 */
class JdPayBaseAction
{
    /**
     * 交易类型
     */
    public $Trade_type;

    /**
     * 处理input
     * @var
     */
    private $input;

    /**
     * 通知地址
     * @var string
     */
    private $trade_notice = "http://www.test.com/callback/chinabank.php";
    //货币
    private $trade_currency = "CNY";
    //服务对象
    private $serviceObj;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->serviceObj = new MotoPayService();    //发起京东支付服务对象
    }

    /**
     * 支付处理
     * @param $param
     * @return mixed|void
     */
    public function PayBaseAction($param)
    {
        $data_xml = !empty($param['trade_code']) ? $this->getS_data_xml($param) : $this->getV_data_xml($param);
        //发起交易至快捷支付
        $resp = $this->serviceObj->trade($data_xml);
        //解析响应结果
        return $this->serviceObj->operate($resp);
    }

    /**
     * 回调处理
     * @param $param
     * @return mixed|void
     */
    public function NotifyBaseAction($param)
    {
        //解析响应结果
        return $this->serviceObj->operate($param);
    }

    /**
     * 退款处理
     * @param $param
     * @return mixed|void
     */
    public function RefundBaseAction($param)
    {
        $data_xml = $this->getR_data_xml($param);
        //发起交易至快捷支付
        $resp = $this->serviceObj->trade($data_xml);
        //解析响应结果
        return $this->serviceObj->operate($resp);
    }

    /**
     * 查账处理
     * @param $param
     * @return mixed|void
     */
    public function QueryBaseAction($param)
    {
        $data_xml = $this->getQ_data_xml($param);
        //发起交易至快捷支付
        $resp = $this->serviceObj->trade($data_xml);
        //解析响应结果
        return $this->serviceObj->operate($resp);
    }

    /**
     * 获取消费xml
     * @param $param
     * @return string
     */
    public function getS_data_xml($param)
    {
        extract($param);
        $trade_notice = $this->trade_notice;
       return s_data_xml_create($card_bank,$card_type,$card_no,
            $card_exp,$card_cvv2,$card_name,
            $card_idtype,$card_idno,$card_phone,
            $trade_type,$trade_id,$trade_amount,
           $this->trade_currency,$trade_date,$trade_time,
            $trade_notice,$trade_note,$trade_code);
    }

    /**
     * 获取签约xml
     * @param $param
     * @return string
     */
    public function getV_data_xml($param)
    {
        extract($param);
        return v_data_xml_create($card_bank,$card_type,$card_no,
            $card_exp,$card_cvv2,$card_name,
            $card_idtype,$card_idno,$card_phone,
            $trade_type,$trade_id,$trade_amount,
            $this->trade_currency);
    }

    /**
     * 获取退款xml
     * @param $param
     * @return string
     */
    public function getR_data_xml($param)
    {
        extract($param);
        $trade_type = "R";
        $trade_notice = "";
        return r_data_xml_create($trade_type,$trade_id,$trade_oid,$trade_amount,
            $this->trade_currency,$trade_date,$trade_time,$trade_notice,$trade_note);
    }

    /**
     * 获取查账xml
     * @param $param
     * @return string
     */
    public function getQ_data_xml($param)
    {
        extract($param);
        $trade_type = "Q";
        return q_data_xml_create($trade_type,$trade_id);
    }

}
