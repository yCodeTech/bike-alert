<?php

namespace Models;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class DbConnect {
	private static $connection;
	private static $dbhost;
	private static $dbport;
	private static $dbname;
	private static $dbuser;
	private static $dbpass;
	
	/**
	 * Set up the connection to the database
	 *
	 * @return PDO
	 */
	public static function connect() {
		self::get_env();

		if (!self::$connection) {
			try {
				/* Less secure way of establishing a connection.*/
				self::$connection = new PDO("mysql:host=". self::$dbhost .";port=". self::$dbport .";dbname=" . self::$dbname, self::$dbuser, self::$dbpass);
				
				// echo "<script>console.error('PHP error reporting is on. Please turn off on live sites.');</script>";
				// self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
				// Sets the global fetch mode for fetch() and fetchAll() to PDO::FETCH_ASSOC
				// Allows the returning array to be the keys as the column names. Otherwise default was named and zero-based keys, meaning duplicated values...
				// Thanks to StackOverflow https://stackoverflow.com/a/13843417/2358222
				self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}
			catch (PDOException $exception) {
				echo "Couldn't connect to the database.<br>" . $exception->getMessage();
			}
		}
		return self::$connection;
	}

	/**
	 * Close the connection.
	 */
	public static function close_connection() {
		if (self::$connection) {
			return self::$connection = null;
		}
	}

	/**
	 * Get the database credentials from the .env file.
	 */
	private static function get_env() {
		$dotenv = self::load_dotenv();
		$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']);

		self::$dbhost = getenv("DB_HOST");
		self::$dbport = getenv("DB_PORT");
		self::$dbname = getenv("DB_NAME");
		self::$dbuser = getenv("DB_USER");
		self::$dbpass = getenv("DB_PASSWORD");
	}

	/**
	 * Load the .env variables.
	 *
	 * Added after graduated uni.
	 *
	 * @return Dotenv
	 */
	private static function load_dotenv() {
		$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__, 1));
		$dotenv->load();
		return $dotenv;
	}

	/**
	 * Get the Geocode API key from .env.
	 *
	 * Added after graduated uni to prevent leaking of the api key in JS as it was previously.
	 *
	 * @return string
	 */
	public static function get_geocode_key() {
		self::load_dotenv();
		return getenv("GEOCODE_API_KEY");
	}
}
