<?php
	class Config {
	  // Database Details
	  private const DBHOST = 'localhost';
	  private const DBUSER = 'root';
	  private const DBPASS = '';
	  private const DBNAME = 'diane';
	  // Data Source Network
	  private $dsn = 'mysql:host=' . self::DBHOST . ';dbname=' . self::DBNAME . '';
	  // conn variable
	  protected $conn = null;

	  // Constructor Function
	  public function __construct() {
	    try {
	      $this->conn = new PDO($this->dsn, self::DBUSER, self::DBPASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	      $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    } catch (PDOException $e) {
	      die('Connectionn Failed : ' . $e->getMessage());
	    }
	    return $this->conn;
	  }

	  // Sanitize Inputs
	  public function test_input($data, $striptags = true) {
      
      // Sanitize.
      /* $id = mysqli_real_escape_string($con, (int)$request->id);
      $name = mysqli_real_escape_string($con, trim($request->name));
      $password = mysqli_real_escape_string($con, trim($request->password));
      $email = mysqli_real_escape_string($con, trim($request->email)); */
			if($striptags) {
				$data = strip_tags($data);
				$data = htmlspecialchars($data);
			}
			$data = stripslashes($data);
			$data = trim($data);
			
	    return $data;

	  }

	  // JSON Format Converter Function
	  public function message($content, $status) {
	    return json_encode(['message' => $content, 'error' => $status]);
		//return null;
	  }
	}

?>