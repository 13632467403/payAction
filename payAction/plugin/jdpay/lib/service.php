<?php
/* *
 * 快捷支付业务类
 * 版本：0.1
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 *
 * 提示：1.快捷支付代码流程一致，只是各种交易类型的参数略有不同，详细参数请参考文档
 *
 */
	class MotoPayService{
		/**
		 * 发起快捷支付方法
		 * @param $data_xml交易的xml格式数据
		 */
		function trade($data_xml){
			$config = new Config();
			//把data元素des加密
			$desObj = new DES($config->des);
			$dataDES = $desObj->encrypt($data_xml);
			$sign = myMd5($config->version.$config->merchant.$config->terminal.$dataDES,$config->md5);
			$xml = xml_create($config->version,$config->merchant,$config->terminal,$dataDES,$sign);
			//使用方法
			$param ='charset=UTF-8&req='.urlencode(base64_encode($xml));
			$resp = $this->post($param);
			return $resp;
		}
		/**
		 * @param $resp 网银在线返回的数据
		 * 数据的解析步骤：
		 * 1：截取resp=后面的xml数据
		 * 2: base64解码
		 * 3: 验证签名
		 * 4: 解析交易数据处理自己的业务逻辑
		 */
		function operate($resp){
			$config = new Config();
			$temResp = base64_decode(substr($resp,5));
			$xml = simplexml_load_string($temResp);
			//验证签名, version.merchant.terminal.data
			$text = $xml->VERSION.$xml->MERCHANT.$xml->TERMINAL.$xml->DATA;

			if(!md5_verify($text,$config->md5,$xml->SIGN)){
				return ;//表示没通过验证
			}

			//des密钥要网银在线后台设置
			$des = new DES($config->des);
			$decodedXML = $des->decrypt($xml->DATA);
			$dataXml = simplexml_load_string($decodedXML);
			//todo 处理自己的业务逻辑

			return $dataXml->asXML();
		}
		function post($param){
			$url = "https://quick.chinabank.com.cn/express.htm";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_PORT, 443);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //信任任何证书
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 检查证书中是否设置域名,0不验证
			//curl_setopt($ch, CURLOPT_VERBOSE, 1); //debug模式
//			curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__).'/quick.cer'); //client.crt文件路径
			//curl_setopt($ch, CURLOPT_SSLCERTPASSWD, ""); //client证书密码
			//curl_setopt($ch, CURLOPT_SSLKEY, "chinabank");
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			$file_contents = curl_exec($ch); // 执行操作
			curl_close($ch);
		    return $file_contents;
		}
		/**
		 * 发送请求至快捷支付地址
		 * 只支持post方式
		 * 测试时，请确认本地curl环境是否可用
		 * @param 请求参数
		 * 此方法废弃
		 */
		function post1($param){//curl
			$url = "https://quick.chinabank.com.cn/express.htm";
//			$url = "http://localhost:8080/web_motopay_express/express.htm";
			$ch = curl_init();
	        curl_setopt ($ch, CURLOPT_URL, $url);
	        curl_setopt ($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
	        curl_setopt($ch, CURLOPT_HEADER, false);
	        $file_contents = curl_exec($ch);
	        curl_close($ch);
	        return $file_contents;
		}
	}

?>
