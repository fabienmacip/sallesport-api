<?php
	// Include config.php file
	include_once 'config.php';

	class UserCRUD extends Config {
	  
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
	    $sql = 'INSERT INTO users (name, password, email) 
											VALUES (:name, :password, :email)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['name' => $name, 'password' => $password, 'email' => $email]);
	    return true;
	  }

	  // Update a user in the database
	  public function update($name, $password, $email, $id) {
	    $sql = 'UPDATE users SET name = :name, password = :password, email = :email WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['name' => $name, 'password' => $password, 'email' => $email, 'id' => $id]);
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