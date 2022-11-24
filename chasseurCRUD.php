<?php
	// Include config.php file
	include_once 'config.php';

	class ChasseurCRUD extends Config {
	  
    // Fetch all or a single chasseur from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM chasseurs';
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

	  // Insert a chasseur in the database
	  public function insert($nom, $prenom) {
	    $sql = 'INSERT INTO chasseurs (nom, prenom) 
											VALUES (:nom, :prenom)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom, 'prenom' => $prenom]);
	    return true;
	  }

	  // Update a chasseur in the database
	  public function update($nom, $prenom, $actif, $id) {
	    $sql = 'UPDATE chasseurs SET nom = :nom, prenom = :prenom, 
																actif = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 
											'actif' => $actif, 'id' => $id]);
	    return true;
	  }

	  // Delete a chasseur from database
	  public function delete($id) {
	    $sql = 'DELETE FROM chasseurs WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>