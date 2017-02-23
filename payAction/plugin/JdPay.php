<?php
require_once "jdpay/JdPayBaseAction.php";
/**
 * 微信支付操作类
 * Author: LiuZhengYong
 */
class JdPay extends payStandard
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
        $this->BaseAction = new JdPayBaseAction();
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
        if( !$result ){
            return $this->output("接口请求异常，请稍后重试",3001);
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
        $rt = $this->xmlToArray($PayResult);
        $rt = $rt['DATA'];
        $desc = $rt['RETURN']['DESC'];
        if ($rt['RETURN']['CODE']=="0000") {
            switch ($this->typeAction){
                case "Pay";
                    return $rt['TRADE']['TYPE'] == "V" ?
                        $this->output($desc,1,"sms") :   //签约
                        $this->output($desc,1,"pay");    //消费
                    break;
                case "Notify";
                    return $this->output($desc,1);
                    break;
                case "Refund";
                    return $this->output($desc,1,$rt);
                    break;
                case "Query";
                    $pay_status_arr = array(
                        "0"	=>	"成功 ",
                        "3"	=>	"全部退款 ",
                        "4"	=>	"部分退款 ",
                        "6"	=>	"处理中 ",
                        "7"	=>	"失败 ",
                    );
                    $rt['TRADE']['STATUS'] = $pay_status_arr[$rt['TRADE']['STATUS']];
                    return $this->output($desc,1,$rt);
                    break;
            }
        } else {
            return $this->output($desc,$rt['RETURN']['CODE']);
        }
    }

    /**
     * 检查参数
     * @param $input
     * @return array
     */
    public function checkInput($input)
    {
        $input["trade_id"] = $input["trade_no"];
        $input["trade_date"] = date('yyyyMMdd',$input["add_time"]);
        $input["trade_time"] = date('HHmmss',$input["add_time"]);
        $checkStatus =  $this->checkNoEmptyInput($input);
        if( !$checkStatus ){
            return false;
        }
        switch ($this->typeAction){
            case "Pay";
                $input["trade_note"] = $input["order_name"];
                $input["trade_amount"] = $input["amount"]*100;;
                $input["card_idtype"] = "I";
                $input["card_type"] = $this->Trade_type == "JdCredit" ? "C" : "D";;
                $input["trade_type"] = empty($input['trade_code']) ? "V" : "S";  //V签约 S消费
                break;
            case "Notify";
                if(isset($input['resp'])){
                    $input = "resp=".$input['resp'];
                }else{
                    return $this->setError("系统异常" ,1002);
                }
                break;
            case "Refund";
                $input["trade_id"] = $input["refund_no"];
                $input["trade_oid"] = $input["trade_no"];
                $input["trade_amount"] = $input["amount"]*100;;
                break;
            default:
                return $input;
                break;
        }
        return $input;
    }

    /**
     * @return mixed
     */
    public function checkNoEmptyInput($param)
    {
        $typeAction = $this->typeAction;
        /**
         * 不能为空的选项处理
         */
        $no_empty_arr = array(
            "Pay"   =>  array(
                "card_bank"    =>  array(
                    "code"  =>  "310",
                    "desc"  =>  "银行简码",
                ),
                "card_no"    =>  array(
                    "code"  =>  "312",
                    "desc"  =>  "卡号",
                ),
                "card_exp"    =>  array(
                    "code"  =>  "313",
                    "desc"  =>  "卡号有效日期",
                ),
                "card_cvv2"    =>  array(
                    "code"  =>  "314",
                    "desc"  =>  "CVV码",
                ),
                "card_name"    =>  array(
                    "code"  =>  "315",
                    "desc"  =>  "持卡人姓名",
                ),
                "card_idtype"    =>  array(
                    "code"  =>  "316",
                    "desc"  =>  "持卡人证件类型",
                ),
                "card_idno"    =>  array(
                    "code"  =>  "317",
                    "desc"  =>  "证件号码",
                ),
                "card_phone"    =>  array(
                    "code"  =>  "318",
                    "desc"  =>  "手机号码",
                ),
            ),
        );
        if( !isset($no_empty_arr[$typeAction]) ){
            return true;
        }
        foreach ($no_empty_arr[$typeAction] as $code => $v) {
            if( !isset($param[$code]) || empty($param[$code]) ){
                return $this->setError($v["desc"]."不能为空" ,$v["code"]);
            }
        }
        return true;
    }

}
