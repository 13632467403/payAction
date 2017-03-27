<?php
/**
 * ------------------------------------------------------
 * 支付处理器 | PayAction,轻松接入支付！
 * ------------------------------------------------------
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
 * ------------------------------------------------------
 * Version: 1.0
 */
require_once "common/common.php";
class PayAction extends paySystem {
    //支付处理器
    private $payAction;
    //支付平台_支付方式
    private $PlatformType;
    //支付平台
    private $payPlatform;
    //支付方式
    private $payType;
    //处理器类型
    private $typeAction;

    /**
     * 初始化，处理支付平台_支付方式
     * PayAction constructor.
     * @param $PlatformType
     */
    public function __construct($PlatformType)
    {
        $this->handlePlatformType($PlatformType);
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
     * 支付平台_支付方式处理
     * @param $PlatformType
     */
    private function handlePlatformType($PlatformType)
    {
        if(strstr($PlatformType,"_")){
            $arr = explode("_",$PlatformType);
            $this->payPlatform = $arr[0];
            $this->payType = $arr[1];
        }
        $this->PlatformType = $PlatformType;
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
        $fun = $this->getFun();
        return $this->payAction->$fun($param);
    }

    /**
     * 获取函数
     * @return string
     */
    private function getFun()
    {
        return "get".$this->typeAction."Request";
    }

    /**
     * 设置支付处理器
     * @return bool
     */
    private function setPayAction()
    {
        $payType = $this->checkPayType();
        if(!$payType){
            return $this->setError("支付类型不存在" ,201);
        }

        $requireFileStatus = $this->requireFile($payType);
        if( !$requireFileStatus ){
            return false;
        }
        if( !class_exists($payType) ){
            return $this->setError("该方法不存在" ,203);
        }
        $this->payAction = new $payType($this->payType);
        return true;
    }

    /**
     * 引入文件
     * @param $file
     * @return bool
     */
    private function requireFile($file)
    {
        $requireFileArr = PayConfig::$requireFileArr;
        foreach ($requireFileArr as $item) {
            $fileName = $item['path']."/".$file.".php";
            if( !file_exists($fileName) ){
                return $this->setError("{$item['desc']}不存在" ,$item['code']);
            }
            require_once $fileName;
        }
        return true;
    }

    /**
     * 检查支付方式是否存在
     * @return bool|int|string
     */
    private function checkPayType(){
        $payTypeArr = PayConfig::$payTypeArr;
        return in_array($this->PlatformType,$payTypeArr) ? $this->payPlatform : false;
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
        if(is_array($param))
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
        //不能为空的选项处理
        $commonInputNoEmptyArr = PayConfig::$commonInputNoEmptyArr;
        if( !isset($commonInputNoEmptyArr[$typeAction]) ){
            return true;
        }
        foreach ($commonInputNoEmptyArr[$typeAction] as $code => $v) {
            if( !isset($param[$code]) || empty($param[$code]) ){
                return $this->setError($v["desc"]."不能为空" ,$v["code"]);
            }
        }
        if($typeAction == "Pay" && $param["amount"] <= 0){
            return $this->setError("金额不能为负数" ,280);
        }
        return true;
    }

}