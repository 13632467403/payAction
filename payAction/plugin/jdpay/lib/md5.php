<?php

	/**
	 * md5加密方法
	 */
    function myMd5($text,$key){
		return md5($text.$key);
    }
    /**
     * 验证签名方法
     */
	function md5_verify($text,$key,$md5){
		$md5Text = myMd5($text,$key);
		return $md5Text==$md5;
	}
?>
