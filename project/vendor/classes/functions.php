<?php
/**
 * @package     MilliyCore
 * @link        https://milliysoft.uz/mCore
 * @copyright   Copyright (C) 2022-2023 MilliySoft DTM
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      https://riack5h3n.uz
 */

defined('_MILLIY_CORE') or die('403');
class functions extends core
{
	public static function check_in($query){
		$check =  self::$conn->real_escape_string($query);
		return $check;
    }
	
	public static function check_out($str){
        $str = htmlentities(trim($str), ENT_QUOTES, 'UTF-8');
        return trim($str);
    }
}