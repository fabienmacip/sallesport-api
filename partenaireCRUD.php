<?php
	// Include config.php file
	include_once 'config.php';

	class PartenaireCRUD extends Config {
	  
    // Fetch all or a single partenaire from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM partenaire';
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

	  // Insert a partenaire in the database
	  public function insert($nomfranchise, $sexegerant, $nomgerant, $mail, $password, $actif, $grantsid) {
	    $sql = 'INSERT INTO partenaire (nomfranchise, sexegerant, nomgerant, mail, password, actif, grantsid) 
											VALUES (:nomfranchise, :sexegerant, :nomgerant, :mail, :password, :actif, :grantsid)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nomfranchise' => $nomfranchise, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'mail' => $mail,  'password' => $password, 
											'actif' => $actif, 'grantsid' => $grantsid]);
	    return true;
	  }

	  // Update a partenaire in the database
	  public function update($nomfranchise, $sexegerant, $nomgerant, $mail, $password, $actif, $grantsid, $id) {
	    $sql = 'UPDATE partenaire SET nomfranchise = :nomfranchise, sexegerant = :sexegerant, 
																nomgerant = :nomgerant, mail = :mail,  password = :password, 
																actif = :actif, grantsid = :grantsid WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nomfranchise' => $nomfranchise, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'mail' => $mail,  'password' => $password, 
											'actif' => $actif, 'grantsid' => $grantsid, 'id' => $id]);
	    return true;
	  }

	  // Delete a partenaire from database
	  public function delete($id) {
	    $sql = 'DELETE FROM partenaire WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>