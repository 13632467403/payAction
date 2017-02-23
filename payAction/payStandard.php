<?php
/**
 * 支付规范类
 * 继承类必须执行该类的方法
 * Class payStandard
 */
abstract class payStandard extends paySystem {
    /**
     * 获取处理结果
     * @param $typeAction
     * @param $param
     * @return array
     */
    abstract public function getResult($typeAction,$param);
    /**
     * 处理返回的结果
     * @param $PayResult
     * @return array
     */
    abstract public function doReturnResult($PayResult);
}