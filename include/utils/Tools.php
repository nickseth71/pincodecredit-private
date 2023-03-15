<?php

/**
* 
*/
class Tools
{
	
	function __construct()
	{
		
	}

	public static function getShopUrl(){
		$var = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];
		$shop_url = substr($var, strpos($var, "=") + 1);
		if(self::str_ends_with($shop_url, 'shopify.com')){
			return $shop_url;
		}else{
			$temp =  substr($var, strpos($var, "/") + 2);
			$shop_url = substr($temp.'/', 0, strpos($temp, '/'));

			return $shop_url;
		}
		
	}

	public static function str_ends_with($haystack, $needle)
	{
    return strrpos($haystack, $needle) + strlen($needle) === strlen($haystack);
	}
}

?>
