<?php
require_once "Alipay/AliPayBaseAction.php";
/**
 * 支付宝支付操作类
 * Author: LiuZhengYong
 */
class AliPay extends payStandard
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
        $this->BaseAction = new AliPayBaseAction();
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
        $this->typeAction = $typeAction;
        $input = $this->checkInput($param);
        if( !$input ){
            return $this->getError();
        }
        $fun = $typeAction."BaseAction";
        $result = $this->BaseAction->$fun($input);
        if(isset($_GET['go'])){
            header("Location: ".$result);
        }
        return $this->doReturnResult($result);
    }

    /**
     * 处理返回的结果
     * @param $PayResult
     * @return array
     */
    public function doReturnResult($PayResult)
    {
        if($PayResult){
            switch ($this->typeAction){
                case "Pay";
                    break;
                case "Notify";
                    break;
                case "Refund";
                    break;
                case "Query";
                    $pay_status_arr = array(
                        "0"	=>	"成功 ",
                        "3"	=>	"全部退款 ",
                        "4"	=>	"部分退款 ",
                        "6"	=>	"处理中 ",
                        "7"	=>	"失败 ",
                    );

                    break;
            }
            $data = array("result" => $PayResult);
            return $this->output("SUCCESS",1,$data);
        }else{
            return $this->output("FAIL",901);
        }
    }

    /**
     * 检查参数
     * @param $input
     * @return array
     */
    public function checkInput($input)
    {
        switch ($this->typeAction){
            case "Pay";
                $input["service"] = $this->getService();
                break;
            case "Notify";
                break;
            case "Refund";
                break;
            default:
                return $input;
                break;
        }
        return $input;
    }

    /**
     * 获取支付宝的支付方式
     * @return bool
     */
    private function getService()
    {
        $Trade_type_arr = array(
            "APP"   =>  "alipay.trade.app.pay",
            "WAP"   =>  "alipay.wap.create.direct.pay.by.user",
            "WEB"   =>  "create_direct_pay_by_user",
        );
        return isset($Trade_type_arr[$this->Trade_type]) ? $Trade_type_arr[$this->Trade_type] : false;

    }

}
