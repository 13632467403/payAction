<?php
/**
 * *******************************************************
 * 支付处理器 | PayAction,轻松接入支付！
 * *******************************************************
 * 该类实现了支付分发处理的流程，包括：
 * 支付：getPayResult()
 * 回调：getNotifyResult()
 * 退款：getRefundResult()
 * 查账：getQueryResult()
 * 流程如下：
 * 1、初始化时，定义支付方式如JdCredit 京东信用卡支付
 * 2、选择处理类型，如支付 getPayResult() ，传入相关参数
 * 3、执行plugin文件夹里的JdPay.php
 * 4、返回执行的结果
 * *******************************************************
 * Version: 1.0
 * Author: 爱煮饭的程序猿
 * Link:http://www.itipai.com
 * Q Q:1257099678
 * PayAction遵循Apache2开源许可协议发布，意味着你可以免费使用PayAction，
 * 甚至允许把你的PayAction应用采用商业闭源发布，但因此引起的纠纷和造成的一切后果,
 * 其责任概由单位承担,与PayAction无关。
 *
 */
require_once "payAction/paySystem.php"; //系统操作类
require_once "payAction/payStandard.php"; //系统规范类
class PayAction extends paySystem {
    //支付处理器
    private $payAction;
    //支付类型
    private $payType;
    //处理器类型
    private $typeAction;
    //商户配置路径
    private $merchantConfigPath = "payAction/config/merchant";
    //类库路径
    private $pluginPath = "payAction/plugin";

    /**
     * 初始化
     */
    public function __construct($payType)
    {
        $this->payType = $payType;
    }

    /**
     * 获取支付请求结果
     * @param $param
     * @return bool
     */
    public function getPayResult($param)
    {
        return $this->setTypeAction("Pay",$param);
    }

    /**
     * 获取回调结果
     * @param $param
     * @return bool
     */
    public function getNotifyResult($param)
    {
        return $this->setTypeAction("Notify",$param);
    }

    /**
     * 获取退款结果
     * @param $param
     * @return bool
     */
    public function getRefundResult($param)
    {
        return $this->setTypeAction("Refund",$param);
    }

    /**
     * 获取查账结果
     * @param $param
     * @return bool
     */
    public function getQueryResult($param)
    {
        return $this->setTypeAction("Query",$param);
    }

    /**
     * 设置处理器类型
     * @param $typeAction
     * @return array
     */
    private function setTypeAction($typeAction,$param)
    {
        $this->typeAction = $typeAction;
        return $this->Action($param);
    }

    /**
     * 公共处理器
     * @param $param
     * @return array
     */
    private function Action($param)
    {
        $PayAction = $this->setPayAction();
        if( !$PayAction ){
            return $this->getError();
        }
        $param = $this->checkInput($param);
        if( !$param ){
            return $this->getError();
        }
        return $this->payAction->getResult($this->typeAction,$param);
    }

    /**
     * 检查传入的参数
     * @param $param
     * @return bool|mixed
     */
    private function checkInput($param){
        $checkStatus = $this->checkNoEmptyInput($param);
        if( !$checkStatus ){
            return false;
        }
        return $this->filterInput($param);
    }

    /**
     * 过滤传入的参数
     * @param $param
     * @return mixed
     */
    public function filterInput($param)
    {
        foreach ($param as $key => $item) {
            $param[$key] = strip_tags(trim(urldecode($item)));
        }
        return $param;
    }

    /**
     * 检查不能为空的参数
     * @param $param
     * @return bool
     */
    private function checkNoEmptyInput($param)
    {
        $typeAction = $this->typeAction;
        /**
         * 不能为空的选项处理
         */
        $no_empty_arr = array(
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

    /**
     * 设置支付方式
     * @return bool
     */
    private function setPayAction()
    {
        switch ($this->payType){
            /**
             *****************
             *  微信支付
             *****************
             */
            //扫码支付
            case "NATIVE":
                //JSAPI支付
            case "JSAPI":
                //APP支付
            case "APP":
                $class = "WeixinPay";
                break;

            /**
             *****************
             *  京东支付
             *****************
             */
            //信用卡支付
            case "JdCredit":
            //借记卡支付
            case "JdDebit":
                $class = "JdPay";
                break;
            default:
                return $this->setError("支付类型不存在" ,201);
                break;
        }

        $requireFileStatus = $this->requireFile($class);
        if( !$requireFileStatus ){
            return false;
        }

        if( !class_exists($class) ){
            return $this->setError("该方法不存在" ,203);
        }
        $this->payAction = new $class($this->payType);
        return true;
    }

    /**
     * 引入文件
     * @param $file
     * @return bool
     */
    private function requireFile($file)
    {
        $fileArr = array(
            array(
                "path"  =>  $this->merchantConfigPath,
                "desc"  =>  "配置文件",
                "code"  =>  204,
            ),
            array(
                "path"  =>  $this->pluginPath,
                "desc"  =>  "类文件",
                "code"  =>  202,
            ),
        );
        foreach ($fileArr as $item) {
            $fileName = __DIR__."/".$item['path']."/".$file.".php";

            if( !file_exists($fileName) ){
                return $this->setError("{$item['desc']}不存在" ,$item['code']);
            }
            require_once $fileName;
        }
        return true;
    }

}