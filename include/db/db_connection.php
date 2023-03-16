<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require 'config.php';


class DB_Connection
{

	private $connection;

	public function __construct()
	{
	}

	public function connect()
	{
		$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $con;
	}

	public function add($table_name, $data)
	{
		$connection = $this->connect();
		$query = "INSERT INTO $table_name (store_url,access_token) VALUES (";
		$columns = [];
		foreach ($data as $column => $value) {
			$columns[] = "'$value'";
		}
		$query .= implode(",", $columns) . ")";
		$result = mysqli_query($connection, $query);
		return $result;
	}

	public function update($table_name, $data, $criteria)
	{
		$connection = $this->connect();
		$query = "UPDATE $table_name SET ";
		$columns = [];
		foreach ($data as $column => $value) {
			$columns[] = "$column = '$value'";
		}

		$query .= implode(',', $columns);

		if (!empty($criteria)) {
			$query .= " WHERE $criteria";
		}
		$result = mysqli_query($connection, $query);
		return $result;
	}

	public function createTable()
	{
		$connection = $this->connect();
		$query = "CREATE TABLE IF NOT EXISTS stores
					(
					    id SERIAL UNIQUE,
					    store_url varchar(255) NOT NULL,
					    access_token varchar(255) NOT NULL,
						user_id varchar(255) NULL,
						installation_date varchar(255) NULL,
						update_date varchar(255) NULL
					)";
		$result = mysqli_query($connection, $query);
		return $result;
	}

	public function isExitsselect($table_name, $columns = "*", $criteria = null)
	{
		$connection = $this->connect();
		$query = "SELECT $columns FROM $table_name";

		if (!empty($criteria)) {
			$query .= " WHERE $criteria";
		}
		try {
			$result = mysqli_query($connection, $query);
			$row = mysqli_num_rows($result);
			return $row;
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function select($table_name, $columns = "*", $criteria = null)
	{
		$connection = $this->connect();
		$query = "SELECT $columns FROM $table_name";

		if (!empty($criteria)) {
			$query .= " WHERE $criteria";
		}
		try {
			$result = mysqli_query($connection, $query, MYSQLI_USE_RESULT);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $row;
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function delete($table_name, $criteria)
	{
		$connection = $this->connect();
		$query = "DELETE FROM $table_name WHERE $criteria";
		$result = mysqli_query($connection, $query);
		return $result;
	}

	public function truncateTable($table_name)
	{
		$connection = $this->connect();
		$query = "TRUNCATE TABLE $table_name";
		$result = mysqli_query($connection, $query);
		return $result;
	}
}
