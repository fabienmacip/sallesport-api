<?php
	// Include config.php file
	include_once 'config.php';

	class DatesCRUD extends Config {
	  
    // Fetch all or a single dates from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM dates';
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

	  // Insert a dates in the database
	  public function insert($nom) {

			echo($nom);

	    $sql = 'INSERT INTO dates (date) VALUES (:nom)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom]);
	    return true;
	  }

	  // Updates a dates in the database
	  public function update($nom, $actif, $id) {

	    $sql = 'UPDATE dates SET date = :nom, actif = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom, 'actif' => $actif, 'id' => $id]);
	    return true;
	  }

	  // Delete a dates from database
	  public function delete($id) {
	    $sql = 'DELETE FROM dates WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>