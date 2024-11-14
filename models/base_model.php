<?php
namespace Models;

class BaseModel {
	protected $connection;
	protected $geocode_key;

	function __construct() {
		$this->connection = DbConnect::connect();
		$this->geocode_key = DbConnect::get_geocode_key();
	}
	function __destruct() {
		$this->connection = DbConnect::close_connection();
	}
	
	/**
	 * Get the Geocode API key.
	 *
	 * Added after graduated uni to prevent leaking of the api key in JS as it was previously.
	 * @return string
	 */
	public function get_geocode_key() {
		return $this->geocode_key;
	}

	/**
	 * Select all rows and columns from specified table
	 *
	 * @param string $table_name The table name
	 * @return array
	 */
	public function select_all($table_name) {

		$query = "SELECT * FROM $table_name";

		$results = $this->connection->query($query);
		return $results->fetchAll();
	}

	/**
	 * Select one column from specified table where a key matches the value.
	 *
	 * @param string $table_name The table name
	 * @param string $column The column name
	 * @param array $where An associated array of the key being matching a table column to check
	 * and the value to check against.
	 *
	 * @return array|false The database result
	 */
	public function select_one($table_name, $column, $where) {
		$key = array_keys($where)[0];

		$query = "SELECT $column FROM $table_name WHERE $key = :val";

		$prepare = $this->connection->prepare($query);
		$prepare->bindValue(":val", $where[$key]);

		$prepare->execute();
		
		return $prepare->fetch();
	}

	/**
	 * Insert into the database
	 *
	 * @param string $table_name
	 * @param array $data An associated array of the table column names as keys and the report data as the values.
	 *
	 * @return boolean
	 */
	public function insert($table_name, $data) {
		$keys = array_keys($data);
		$fields = implode(",", $keys);

		$placeholders = str_repeat('?,', count($keys) - 1) . '?';

		// $values = array_values($data);
		$query = "INSERT INTO $table_name ($fields)
		VALUES ($placeholders)";

		$prepare = $this->connection->prepare($query);
		return $prepare->execute(array_values($data));
	}

	/**
	 * Get the last inserted ID.
	 *
	 * @return string|false The ID
	 */
	public function get_last_id() {
		$query = "SELECT LAST_INSERT_ID()";

		$results = $this->connection->query($query);
		return $results->fetchColumn();
	}

	/**
	 * Get full details of the report and user.
	 *
	 * @param string $id The incident reference
	 *
	 * @return array The data
	 */
	public function get_report($id) {
		$query = "SELECT *, camera_fittings.name AS camera_fitting_name FROM user INNER JOIN incident_report ON user.id = incident_report.user_id INNER JOIN camera_fittings ON incident_report.camera_fitting_id = camera_fittings.id WHERE incident_report.incident_id = :id";

		$prepare = $this->connection->prepare($query);
		$prepare->bindValue(":id", $id);
		$prepare->execute();

		return $prepare->fetchAll();
	}
}
