<?php
/**
 * 支付规范类
 * 继承类必须执行该类的方法
 * Class payStandard
 */
abstract class payStandard extends paySystem {

    /**
     * 支付请求
     * @param $param
     * @return mixed|void
     */
    abstract public function getPayRequest($param);

    /**
     * 获取回调请求
     * @param $param
     * @return mixed|void
     */
    abstract public function getNotifyRequest($param);

    /**
     * 获取退款请求
     * @param $param
     * @return mixed|void
     */
    abstract public function getRefundRequest($param);

    /**
     * 获取查账请求
     * @param $param
     * @return mixed|void
     */
    abstract public function getQueryRequest($param);

}