<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require 'db_connection.php';

class Stores extends DB_Connection
{

	private $table_name = "stores";

	public function __construct()
	{
		$this->createTable();
	}

	public function addData($data)
	{
		return $this->add($this->table_name, $data);
	}

	public function updateData($data, $criteria)
	{
		return $this->update($this->table_name, $data, $criteria);
	}

	public function isShopExists($shop)
	{
		return $this->isExitsselect($this->table_name, "*", "store_url = '$shop'");
	}

	public function getData($columns="*",$shop)
	{
		return $this->select($this->table_name, $columns, "store_url = '$shop'");
	}
}
