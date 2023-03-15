<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require '../include/db/Store.php';
require '../include/utils/Shopify.php';
require '../include/utils/Tools.php';
/**
 * 
 */

class Messages extends Shopify
{
	
	function __construct()
	{
		$this->getMessagesFromOrders();
	}

	public function getMessagesFromOrders(){
		$messages=[];
		$Store = new Stores();		
		$shop_url = "angel-delivery-nz.myshopify.com";//Tools::getShopUrl()
		$shop_info = $Store->isShopExists($shop_url);
		$response = parent::getOrders($shop_url, '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');
		
		// echo "Data".json_encode($shop_url);
		// return;
		// print_r(json_encode($response->orders));
		// return;
		if(isset($response->orders)){
			foreach ($response->orders as $key => $value) {
				for ($i=0; $i < sizeof($value->line_items); $i++) { 
				if(sizeof($value->line_items[$i]->properties)  > 0 && ($value->line_items[$i]->properties[sizeof($value->line_items[$i]->properties)-1]->value !== "N/A" && $value->line_items[$i]->properties[sizeof($value->line_items[$i]->properties)-1]->value !== "n/a"
					&& $value->line_items[$i]->properties[sizeof($value->line_items[$i]->properties)-1]->value !== "n/A"
					&& $value->line_items[$i]->properties[sizeof($value->line_items[$i]->properties)-1]->value !== "N/a")){
			// print_r(json_encode($value->id));
			// print_r(json_encode($value->line_items));
			// echo "<br><br><br>";
			$obj = array("ordreId" => $value->name, "message" => $value->line_items[$i]->properties[sizeof($value->line_items[$i]->properties)-1]->value);
			array_push($messages, $obj);
			}					# code...
				}
			}
		// print_r(json_encode($messages));
		echo json_encode(array("method"=>"get_messages",
				"status" => "success",
				"data" => $messages
	),JSON_UNESCAPED_SLASHES);
	}else{
				echo json_encode(array("method"=>"get_messages",
				"status" => "error",
				"message" => "Something went wrong."
	),JSON_UNESCAPED_SLASHES);
	}

		// echo "called";
	}
}

new Messages();
?>