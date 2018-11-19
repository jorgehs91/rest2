<?php
    
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		
		CREATE TABLE IF NOT EXISTS `users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `email` varchar(50) NOT NULL,
		  `password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "rest";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD, self::DB);			
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status

			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $this->_request['email'];
			$password = $this->_request['pwd'];

			$return  = null;
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysqli_query($this->db, "SELECT id FROM `users` WHERE email = '".$email."' AND password = '".md5($password)."' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$result = mysqli_fetch_array($sql,MYSQLI_NUM);								
						$return  = $result[0];
					}
				}
			}
			return $return ;
		}

		function GetAll(){
			if($this->login() > 0){
				$table_name = $this->_request['table'];					
				$conditions = $this->_request['conditions'];
				if(isset($this->_request['order_by']))
					$order_by = $this->_request['order_by'];	
				else 
					$order_by = null;		   
			    $sql0 = "SELECT * FROM ".$table_name." WHERE 1=1";
			    if($conditions != null) {
					foreach($conditions as $key=>$value){
						$sql0 .= " AND {$key} = '${value}'";
					}
			    }
			    if($order_by != null) {
			        $sql0 .=" ORDER BY ";
			        foreach($order_by as $key=>$value){
			            $sql0 .= " {$key} ${value}";
			        }
			    }  //  echo $sql0;
			    $sql = mysqli_query($this->db, $sql0);  
			    if(mysqli_num_rows($sql) > 0){
					while($row =  mysqli_fetch_array($sql, MYSQLI_ASSOC))
	        			$result[] = $row;
					$this->response($this->json($result), 200);
				}
			}
			$error = array('status' => "Failed", "msg" => "Sorry It's Not Working");
			$this->response($this->json($error), 400);
		}	

		function Delete(){
			if($this->login() > 0){
				$table_name = $this->_request['table'];				
				$conditions = $this->_request['conditions'];

			    $sql0 = "DELETE FROM ".$table_name." WHERE 1=1";
			    foreach ($conditions as $key=>$value) {
			        if(is_numeric($value))
			            $sql0 .= " AND ".$key."=".$value.",";
			        else
			            $sql0 .= " AND ".$key."=".$this->db_escape($value).",";
			    }
			    $sql0 = substr($sql0, 0, -1);
			    $sql = mysqli_query($this->db, $sql0);
				if(mysqli_affected_rows($this->db) > 0){				
					$this->response($this->json(array( 'Deleted_id' => mysqli_affected_rows($this->db))), 200);
				}
				$this->response('', 204);
			}
			$error = array('status' => "Failed", "msg" => "Failed To Delete");
			$this->response($this->json($error), 400);
		}	

		function GetRow(){
			if($this->login() > 0){
				$table_name = $this->_request['table'];				
				$conditions = $this->_request['conditions'];
				if(isset($this->_request['order_by']))
					$order_by = $this->_request['order_by'];	
				else 
					$order_by = null;	   
			    $sql0 = "SELECT * FROM ".$table_name." WHERE 1=1";
			    if($conditions != null) {
					foreach($conditions as $key=>$value){
						$sql0 .= " AND {$key} = '${value}'";
					}
			    }
			    if($order_by != null) {
			        $sql0 .=" ORDER BY ";
			        foreach($order_by as $key=>$value){
			            $sql0 .= " {$key} ${value}";
			        }
			    }  //  echo $sql0;
			    $sql0 .= " LIMIT 1"; 
			    $sql = mysqli_query($this->db, $sql0);  
			    if(mysqli_num_rows($sql) > 0){
					if($row =  mysqli_fetch_array($sql, MYSQLI_ASSOC))        				
						$this->response($this->json($row), 200);
					else
						$this->response(array('status' => "Failed", "msg" => "Sorry It's Not Working"), 400);
				}
				$this->response('', 204);	// If no records "No Content" status
			}
			$error = array('status' => "Failed", "msg" => "Sorry It's Not Working");
			$this->response($this->json($error), 400);
		}

		
		function GetSingleValue(){
			if($this->login() > 0){
				$tablename = $this->_request['table'];	
				$column_single = $this->_request['column_single'];	
				if(isset($this->_request['conditions']))
					$conditions = $this->_request['conditions'];
				else
					$conditions = null; 

				$sql0 = "SELECT ".$column_single." FROM ".$tablename." WHERE 1=1";
			    if($conditions != null) {
			        foreach($conditions as $key=>$value){
			            if(is_numeric($value))
			                $sql0 .= " AND {$key} = ${value}";
			            else
			                $sql0 .= " AND {$key} = '${value}'";
			        }
			    }  
			    $sql = mysqli_query($this->db, $sql0);  
			    if(mysqli_num_rows($sql) > 0){
					if($row =  mysqli_fetch_array($sql, MYSQLI_ASSOC))        				
						$this->response($this->json($row), 200);
					else
						$this->response(array('status' => "Failed", "msg" => "Sorry It's Not Working"), 400);
				}
				$this->response('', 204);	// If no records "No Content" status
			}
			$error = array('status' => "Failed", "msg" => "Sorry It's Not Working");
			$this->response($this->json($error), 400);
		}
		
		function Insert(){
			if($this->login() > 0){
				$table_name = $this->_request['table'];	
				$data = $this->_request['data'];

			    $sql0 = "INSERT INTO ".$table_name." (";
			    $sql1 = " VALUES (";
			    foreach($data as $key=>$value){
			        $sql0 .= $key.",";
					if(is_array($value)) { 
						if($value[1] == 'date')				
							$sql1 .=  $this->db_escape($value[0]).",";
						if($value[1] == 'float')
							$sql1 .= $value.",";
					}else 
						$sql1 .= $this->db_escape($value).",";
			    }
			    $sql0 = substr($sql0, 0, -1).")";
			    $sql1 = substr($sql1, 0, -1).")";
			    //$string =  str_replace('\"', '',$sql0.$sql1);
			    $string = stripslashes($sql0.$sql1);
			   // $this->response($string, 400);
				$sql = mysqli_query($this->db, $sql0.$sql1);
				if(mysqli_insert_id($this->db) > 0){				
					$this->response($this->json(array( 'inserted_id' => mysqli_insert_id($this->db))), 200);
				}
				//$this->response('', 204);
				$error = array('status' => "Failed", "msg" => "Failed To Insert ".$string);
				$this->response($this->json($error), 400);
			}else{
				$error = array('status' => "Failed", "msg" => "Sorry You are not authenticated.");
				$this->response($this->json($error), 400);
			}
		}


		function Update(){
			if($this->login() > 0){
				$table_name = $this->_request['table'];	
				$data = $this->_request['data'];
				$primary_key = $this->_request['primary_key'];
			   
			    $sql0 = "UPDATE ".$table_name." SET ";
			    foreach($data as $key=>$value){
					if(is_array($value)) { 
						if($value[1] == 'date')				
							$sql0 .= $key." = ". $this->db_escape(date2sql($value[0])).",";
						if($value[1] == 'float')
							$sql0 .= $key." = ". $value.",";
					}else {
						if(is_numeric($value))
							$sql0 .= $key." = ".$value.",";
						else
							$sql0 .= $key." = ".$this->db_escape($value).",";
					}
			    }
			   	$sql0 = substr($sql0, 0, -1);
			    $sql0 .= " where ";

			     foreach($primary_key as $key=>$value){
			        if(is_array($value)) { 
			            if($value[1] == 'date')             
			                $sql0 .= $key." = ". $this->db_escape(date2sql($value[0])).",";
			            if($value[1] == 'float')
			                $sql0 .= $key." = ". $value.",";
			        }else{
						if(is_numeric($value))
							$sql0 .= $key." = ".$value.",";
						else
							$sql0 .= $key." = ".$this->db_escape($value).",";
					} 			           
			    }
			    $sql0 = substr($sql0, 0, -1);
			    $sql = mysqli_query($this->db, $sql0);
				if($sql){				
					$this->response($this->json(array( 'response' => 'Updated')), 200);
				}
				$this->response($this->json(array( $sql0)), 200);
			}else{
				$error = array('status' => "Failed", "msg" => "Sorry You are not authenticated.");
				$this->response($this->json($error), 400);
			} 
		}	

		public	function db_escape($value = "", $nullify = false){			
			$value = @html_entity_decode($value);
			$value = @htmlspecialchars($value);

		  	//reset default if second parameter is skipped
			$nullify = ($nullify === null) ? (false) : ($nullify);

		  	//check for null/unset/empty strings
			if ((!isset($value)) || (is_null($value)) || ($value === "")) {
				$value = ($nullify) ? ("NULL") : ("''");
			} else {
				if (is_string($value)) {
		      		//value is a string and should be quoted; determine best method based on available extensions
					if (function_exists('mysqli_real_escape_string')) {
				  		$value = "'" . mysqli_real_escape_string($this->db, $value) . "'";
					} else {
					  $value = "'" . mysqli_escape_string($this->db, $value) . "'";
					}
				} else if (!is_numeric($value)) {
					//value is not a string nor numeric
					display_error("ERROR: incorrect data type send to sql query");
					echo '<br><br>';
					exit();
				}
			}
			return $value;
		}
				
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library	
	$api = new API;
	$api->processApi();
?>
