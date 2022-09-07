<?php
	// Include config.php file
	include_once 'config.php';

	class StructureCRUD extends Config {
	  
    // Fetch all or a single structure from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM structure';
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

	  // Insert a structure in the database
	  public function insert($adr1, $adr2, $cp, $ville, $mail, $password, $sexegerant, $nomgerant, $actif, $partenaireid ,$grantsid) {
	    $sql = 'INSERT INTO structure (adr1, adr2, cp, ville, mail, password, sexegerant, nomgerant, actif, partenaireid, grantsid) 
											VALUES (:adr1, :adr2, :cp, :ville, :mail, :password, :sexegerant, :nomgerant, :actif, :partenaireid, :grantsid)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['adr1' => $adr1, 'adr2' => $adr2, 'cp' => $cp, 'ville' => $ville,  
											'mail' => $mail,  'password' => $password, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'actif' => $actif, 'partenaireid' => $partenaireid, 
											'grantsid' => $grantsid]);
	    return true;
	  }

	  // Update a structure in the database
	  public function update($adr1, $adr2, $cp, $ville, $mail, $password, $sexegerant, $nomgerant, $actif, $partenaireid ,$grantsid, $id) {
	    $sql = 'UPDATE structure SET adr1 = :adr1, adr2 = :adr2, cp = :cp, ville = :ville, mail = :mail, 
																	 password = :password, sexegerant = :sexegerant, nomgerant = :nomgerant, 
																	 actif = :actif, partenaireid = :partenaireid, grantsid = :grantsid WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['adr1' => $adr1, 'adr2' => $adr2, 'cp' => $cp, 'ville' => $ville,  
											'mail' => $mail,  'password' => $password, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'actif' => $actif, 'partenaireid' => $partenaireid, 
											'grantsid' => $grantsid, 'id' => $id]);
	    return true;
	  }

	  // Delete a structure from database
	  public function delete($id) {
	    $sql = 'DELETE FROM structure WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>