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

流程支付
getPayResult() //获取支付请求结果
setTypeAction()	//设置处理器类型
Action()	//公共处理器
checkInput()	//检查传入的参数
setPayAction()	//设置支付处理器
$this->payAction->getResult()	//发起请求
