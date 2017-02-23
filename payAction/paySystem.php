<?php
/**
 * 支付系统操作类
 * Author: 爱煮饭的程序猿
 */
class paySystem
{
    /**
     * @var string 错误信息
     *
     */
    private $_error_msg = null;
    /**
     * @var int 错误代码
     */
    private $_error_code = -1;

    /**
     * 设置错误信息
     * @param $error_msg
     * @param int $error_code
     * @return bool
     */
    protected function setError($error_msg, $error_code = -1)
    {
        $this->_error_msg = $error_msg;
        $this->_error_code = $error_code;
        return false;
    }

    /**
     * 获取错误数据
     * @return array
     */
    protected function getError()
    {
        return array(
            "return_code"   =>  $this->_error_code,
            "return_msg"    =>  $this->_error_msg,
        );
    }

    /**
     * 数据输出
     * @param $return_msg
     * @param $return_code
     * @param string $data
     * @return array
     */
    protected function output($return_msg, $return_code ,$data = "")
    {
        $return =  array(
            "return_msg"    =>  $return_msg ? $return_msg : "系统异常，请稍后重试",
            "return_code"   =>  $return_code ? $return_code :  "0001",
        );
        if(!empty($data)){
            $return["data"]  =  $data;
        }
        return $return;
    }

    /**
     * xml转为数组
     * @param string $xml
     * @return array
     */
    public function xmlToArray($xml='')
    {
        if(!class_exists("xmlToArrayParser"))
            include_once "tool/xmlToArrayParser.php";
        $xml_obj = new xmlToArrayParser($xml);
        return $xml_obj->array;
    }

    function __call($name, $arguments)
    {
        return $this->output("该方法不存在！",3004);
    }

}