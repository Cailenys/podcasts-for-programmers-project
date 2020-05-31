<?php

	class Account {
        private $conn; // Passing in the connection variable and assign it to the class
		private $errorArray;

		public function __construct($conn) {
			$this->conn = $conn;
			$this->errorArray = array();
		}

		public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateUsername($un);
			$this->validateFirstName($fn);
			$this->validateLastName($ln);
			$this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			if(empty($this->errorArray) == true) {
				//Insert into the database
				return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
			}
			else {
				return false;
			}
		}

		private function insertUserDetails($un, $fn, $ln, $em, $pw){
		  $encryptedPW = md5($pw);  // md5 is a encryption method. So password will be a gui of letters and numbers like this: f8f4fjdhs78s1afgfs
		  $profilePic = "/assets/images/profile-pics/Duck-in-the-pool.png";
		  $date = date("Y-m-d");
	     
		  $result = mysqli_query($this->conn, "INSERT INTO users VALUES (NULL, '$un', '$fn', '$ln', '$em', '$encryptedPW', '$date', '$profilePic')");
		  
		  return $result;
		}
		
		
		public function getError($error) {
			if(!in_array($error, $this->errorArray)) {
				$error = "";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constants::$userNameCharacters );
				return;
			}

			//TODO: check if username exists

		}

		private function validateFirstName($fn) {
			if(strlen($fn) > 25 || strlen($fn) < 2) {
				array_push($this->errorArray, Constants::$firtNameCharacters );
				return;
			}
		}

		private function validateLastName($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {
				array_push($this->errorArray, Constants::$lastNameCharacters);
				return;
			}
		}

		private function validateEmails($em, $em2) {
			if($em != $em2) {
				array_push($this->errorArray, Constants::$emailInvalid );
				return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, Constants::$emailsDoNotMatch);
				return;
			}

			//TODO: Check that username hasn't already been used

		}

		private function validatePasswords($pw, $pw2) {
			
			if($pw != $pw2) {
				array_push($this->errorArray, Constants::$passwordsDoNotMatch);
				return;
			}

			if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
				return;
			}

			if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constants::$passwordCharacters);
				return;
			}

		}


	}
?>