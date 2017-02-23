<?php
require_once "lib/WxPay.Api.php";
require_once "lib/WxPay.Notify.php";
require_once "lib/WxPay.NativePay.php";
require_once "lib/WxPay.JsApiPay.php";
require_once "lib/WxPay.MicroPay.php";
/**
 * 微信支付基础操作类
 * Author: LiuZhengYong
 */
class WeixinPayBaseAction extends WxPayNotify
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
     * 初始化
     */
    public function __construct($Trade_type)
    {
        $this->Trade_type = $Trade_type;
        $this->input = new WxPayUnifiedOrder();
    }

    /**
     * 支付处理
     * @param $param
     * @return mixed|void
     */
    public function PayBaseAction($param)
    {
        $this->input->SetBody($param['Body']);
        $this->input->SetAttach($param['Attach']);
        $this->input->SetOut_trade_no($param['Out_trade_no']);
        $this->input->SetTotal_fee($param['Total_fee']);
        $this->input->SetTime_start(date("YmdHis",$param['Time_start']));
        $this->input->SetTime_expire(date("YmdHis", $param['Time_start'] + 600));
        $this->input->SetGoods_tag($param['Goods_tag']);
        $this->input->SetNotify_url(WxPayConfig::Notify_url);
        $this->input->SetTrade_type($this->Trade_type);
        return $this->unifiedOrderAction($param);
    }

    /**
     * 回调处理
     * @param $param
     * @return mixed|void
     */
    public function NotifyBaseAction($param)
    {
        $this->Handle(false);
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
            // Log::DEBUG("call back:" . json_encode($data));
            $notfiyOutput = array();
            if(!array_key_exists("transaction_id", $data)){
                throw new Exception("输入参数不正确");
            }
            //查询订单，判断订单真实性
            if(!$this->Queryorder($data["transaction_id"])){
                throw new Exception("订单查询失败");
            }
            return true;
    }
    /**
     * 退款处理
     * @param $param
     * @return mixed|void
     */
    public function RefundBaseAction($param)
    {
        extract($param);
        $input = new WxPayRefund();
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);
        $input->SetOut_refund_no($refund_no);
        $input->SetOp_user_id(WxPayConfig::MCHID);
        return WxPayApi::refund($input);
    }

    /**
     * 查账处理
     * @param $param
     * @return mixed|void
     */
    public function QueryBaseAction($param)
    {
        $out_trade_no = $param["out_trade_no"];
        $input = new WxPayOrderQuery();
        $input->SetOut_trade_no($out_trade_no);
        printf_info(WxPayApi::orderQuery($input));
    }

    /**
     * @return mixed
     */
    public function unifiedOrderAction($param)
    {
        switch ($this->Trade_type){
            //扫码支付
            case "NATIVE" :
                $this->input->SetProduct_id($param['Product_id']);
                break;
            //JSAPI支付
            case "JSAPI" :
                $tools = new JsApiPay();
                $openId = $tools->GetOpenid();
                $this->input->SetOpenid($openId);
                break;
            //APP支付
            case "APP" :
                break;
            default:
                return  false;
                break;
        }
        return WxPayApi::unifiedOrder($this->input);
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        //Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

}
