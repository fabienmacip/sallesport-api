<?php
	// Include config.php file
	include_once 'config.php';

	class AnimalCRUD extends Config {
	  
    // Fetch all or a single animal from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM animaux';
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

	  // Insert a animal in the database
	  public function insert($nom) {
	    $sql = 'INSERT INTO animaux (nom) VALUES (:nom)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom]);
	    return true;
	  }

	  // Update a animal in the database
	  public function update($nom, $actif, $id) {

	    $sql = 'UPDATE animaux SET nom = :nom, actif = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom, 'actif' => $actif, 'id' => $id]);
	    return true;
	  }

	  // Delete a animal from database
	  public function delete($id) {
	    $sql = 'DELETE FROM animaux WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>