<?php
/**
 * 系统公共文件
 */

// 系统目录定义
defined('COMMON_PATH') 	            or define('COMMON_PATH', dirname(__FILE__).'/');
defined('ROOT_PATH') 	            or define('ROOT_PATH', COMMON_PATH.'../');
defined('MERCHANT_CONFIG_PATH') 	or define('MERCHANT_CONFIG_PATH', ROOT_PATH.'config/merchant');
defined('PLUGIN_PATH') 	            or define('PLUGIN_PATH', ROOT_PATH.'plugin');
defined('TOOL_PATH') 	            or define('TOOL_PATH', ROOT_PATH.'tool');

//系统操作类
require_once COMMON_PATH."paySystem.php";
//系统规范类
require_once COMMON_PATH."payStandard.php";
//系统函数
require_once COMMON_PATH."function.php";
//系统配置类
require_once ROOT_PATH."/config/PayConfig.php";
