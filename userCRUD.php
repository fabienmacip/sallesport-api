<?php
	// Include config.php file
	include_once 'config.php';

	class UserCRUD extends Config {

		// Find user with mail & password
		public function fetchLogin($mail, $pwd) {

			$pwd = trim($pwd);
			$mail = trim($mail);

			$sql = 'SELECT id, name, email, password FROM user WHERE email = :mail ORDER BY id DESC LIMIT 1';

			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['mail' => $mail]);
			$rows = $stmt->fetchAll();

/* 			var_dump($rows);
			die();
 */
			// Si on n'a pas trouvé de USER, c'est-à-dire d'ADMINISTRATEUR, alors on cherche un PARTENAIRE
 			if(empty($rows)){
				$sql2 = 'SELECT id, nomfranchise, mail, password FROM partenaire WHERE mail = :mail ORDER BY id DESC LIMIT 1';
				
				$stmt2 = $this->conn->prepare($sql2);
				$stmt2->execute(['mail' => $mail]);
				$rows2 = $stmt2->fetchAll();
				
/* 				var_dump($rows2);
				die();
 */

				if($rows2 && password_verify($pwd, $rows2[0]['password'])){
					unset($rows2[0]['password']);
					$rows2[0]['token'] = 'ok-token-partenaire';
					$rows2[0]['role'] = 'partenaire';
					return $rows2;
				}
				
				die();
			} else {
				if($rows && password_verify($pwd, $rows[0]['password'])){
					unset($rows[0]['password']);
					$rows[0]['token'] = 'ok-token-admin';
					$rows[0]['role'] = 'admin';
					return $rows;
				}
			}

		}
		

    // Fetch all or a single user from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM users';
	    if ($id != 0) {
	      $sql .= ' WHERE id = :id';
				$stmt = $this->conn->prepare($sql);
				$stmt->execute(['id' => $id]);
	    }
			else {
				$stmt = $this->conn->prepare($sql);
				$stmt->execute();
			}
	    $rows = $stmt->fetchAll();
	    return $rows;
	  }

	  // Insert a user in the database
	  public function insert($name, $password, $email) {

			$password = password_hash($password, PASSWORD_BCRYPT);

	    $sql = 'INSERT INTO users (name, password, email) 
											VALUES (:name, :password, :email)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['name' => $name, 'password' => $password, 'email' => $email]);
	    return true;
	  }

	  // Update a user in the database
	  public function update($name, $password, $email, $id) {
	    $sql = 'UPDATE user SET name = :name, email = :email WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
	    return true;
	  }

	  // Update a user PASSWORD column in the database
	  public function updatePassword($id, $pwd) {

			$id = (int)$id;
			$pwd = password_hash($pwd, PASSWORD_BCRYPT);
			
	    $sql = 'UPDATE user SET password = :password WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['password' => $pwd, 'id' => $id]);
	    return true;
	  }


	  // Delete a user from database
	  public function delete($id) {
	    $sql = 'DELETE FROM users WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>