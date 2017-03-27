<?php
require_once "weixin/WxPayBaseAction.php";
/**
 * 微信支付操作类
 * Author: LiuZhengYong
 */
class WxPay extends payStandard
{
    /**
     * 交易类型
     */
    public $Trade_type;

    /**
     * 处理器类型
     * @var
     */
    private $typeAction;

    /**
     * 处理input
     * @var
     */
    private $BaseAction;

    /**
     * 初始化
     */
    public function __construct($Trade_type)
    {
        $this->Trade_type = $Trade_type;
        $this->BaseAction = new WeixinPayBaseAction($this->Trade_type);

    }

    /**
     * 支付处理
     * @param $param
     * @return mixed|void
     */
    public function getPayRequest($param)
    {
        return $this->getResult("Pay",$param);
    }

    /**
     * 获取回调结果
     * @param $param
     * @return mixed|void
     */
    public function getNotifyRequest($param)
    {
        return $this->getResult("Notify",$param);
    }

    /**
     * 获取退款结果
     * @param $param
     * @return mixed|void
     */
    public function getRefundRequest($param)
    {
        return $this->getResult("Refund",$param);
    }

    /**
     * 获取查账结果
     * @param $param
     * @return mixed|void
     */
    public function getQueryRequest($param)
    {
        return $this->getResult("Query",$param);
    }

    /**
     * 获取处理结果
     * @param $typeAction
     * @param $param
     * @return array
     */
    public function getResult($typeAction,$param)
    {
        try{
            $this->typeAction = $typeAction;
            $input = $this->checkInput($param);
            if( !$input ){
                return $this->getError();
            }
            $fun = $this->typeAction."BaseAction";
            $result = $this->BaseAction->$fun($input);
            return $this->doReturnResult($result);
        }catch(Exception $ex){
            return $this->output($ex->getMessage(),3009);
        }
    }

    /**
     * 处理返回的结果
     * @param $PayResult
     * @return array
     */
    public function doReturnResult($PayResult)
    {
        if ($PayResult["result_code"]=="SUCCESS") {
            switch ($this->typeAction) {
                case "Pay";
                    $result = $this->Trade_type == "NATIVE" ? $PayResult['code_url'] :
                        $PayResult['prepay_id'];
                    break;
                case "Notify";
                    break;
                case "Refund";
                    break;
                case "Query";
                    $pay_status_arr = array(
                        "0" => "成功 ",
                        "3" => "全部退款 ",
                        "4" => "部分退款 ",
                        "6" => "处理中 ",
                        "7" => "失败 ",
                    );
                    break;
            }//switch
            $data = array("result" => $result);
            return $this->output($PayResult['return_msg'],1,$data);
        }else{
            return $this->output($PayResult['err_code_des'],$PayResult['result_code']);
        }//if
    }

    /**
     * 获取支付返回的结果
     * @param $PayResult
     * @return array
     */
    public function getReturnPayResult($PayResult)
    {
        switch ($this->Trade_type){
            //扫码支付
            case "NATIVE":
                $result = $PayResult['code_url'];
                break;
            //JSAPI支付
            case "JSAPI":
            //APP支付
            case "APP":
                $result = $PayResult['prepay_id'];
                break;
            default:
                $result = false;
                break;
        }
        return array("result"=>$result);
    }

    /**
     * 检查参数
     * @param $input
     * @return array
     */
    private function checkInput($input)
    {
        $param = array();
        $param['Out_trade_no'] = $input['trade_no'];
        //Body 长度限制为128字节
        $param["Body"] =  mb_substr($input["order_name"], 0, 42, 'utf-8');
        $param['Attach'] = empty($input['Attach']) ? $param['Body'] : $input['Attach'];
        $param['Product_id'] = empty($input['Product_id']) ? "" : $input['Product_id'];
        if( empty($input['Goods_tag']) ){
            $param['Goods_tag'] = "";
        }
        $param["Total_fee"] = $input["amount"] * 100;    //元转为分
        $param['Time_start'] = $input['add_time'];
        return $param;
    }



}
