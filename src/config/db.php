<?php
// file created db operations
// we are using pdo here

/**
* 
*/
class db
{
	
	function __construct()
	{
		# code...
	}

	private $db_host = 'localhost';
	private $user = 'root';
	private $password = '';
	private $db_name = 'slim_app';

	// connecting to db
	public function connect()
	{	
			
		
			// WITH PDO
			// using this here as '$db_host' is a class property
			$mysql_connect_str = "mysql:host=$this->db_host;dbname=$this->db_name";
			
			$dbcon = new PDO($mysql_connect_str,$this->user,$this->password);
			
			// getting errors if there is any in creating connection with mysql
			$dbcon->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		

		
		/*		
			// WITH SQLI
			$dbcon = mysqli_connect($this->db_host,$this->user,$this->password,$this->db_name);

			// Check connection
			if(mysqli_connect_errno()){

			  throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
			
			}
		*/

		return $dbcon;
	}
	 
}




?>