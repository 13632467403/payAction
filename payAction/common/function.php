<?php
defined('ROOT_PATH') or exit("ROOT_PATH is undefined!");
/**
 * 系统公共函数
 */

/**
 * xml转为数组
 * @param string $xml
 * @return array
 */
function xmlToArray($xml='')
{
    include_once TOOL_PATH."/xmlToArrayParser.php";
    $xml_obj = new xmlToArrayParser($xml);
    return $xml_obj->array;
}

