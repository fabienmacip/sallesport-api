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

    // Fetch all structures linked to a partenaire from database
		//readStructuresOfPartenaire
	  public function fetchLinkedToPartenaire($id, $last = '') {
	    $sql = 'SELECT * FROM structure WHERE partenaire = :id';
			if($last == 'yes'){
				$sql .= ' ORDER BY id DESC LIMIT 1';
			}
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['id' => $id]);
	    $rows = $stmt->fetchAll();
	    return $rows;
	  }



	  // Insert a structure in the database
	  public function insert($adr1, $adr2, $cp, $ville, $mail, $password, $sexegerant, $nomgerant, $actif, $partenaire ,$grants) {

			$partenaire = (int)$partenaire;

			$sql = 'INSERT INTO `grants` (`membersread`, `memberswrite`, `membersadd`, `membersupdate`, 
							`membersproductsadd`, `memberspaymentscheduleread`, `membersstatsread`, 
							`memberssubscriptionread`, `paymentschedulesread`, `paymentscheduleswrite`, 
							`paymentdayread`, `drinksell`, `foodsell`, `sendnewsletter`) 
							SELECT `membersread`, `memberswrite`, `membersadd`, `membersupdate`, 
							`membersproductsadd`, `memberspaymentscheduleread`, `membersstatsread`, 
							`memberssubscriptionread`, `paymentschedulesread`, `paymentscheduleswrite`, 
							`paymentdayread`, `drinksell`, `foodsell`, `sendnewsletter` 
							FROM `grants` WHERE `id` = (SELECT grants FROM partenaire WHERE id = :partenaire)';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['partenaire' => $partenaire]);


			$sql2 = 'INSERT INTO structure (adr1, adr2, cp, ville, mail, password, sexegerant, nomgerant, actif, partenaire, grants) 
											VALUES (:adr1, :adr2, :cp, :ville, :mail, :password, :sexegerant, :nomgerant, :actif, :partenaire, 
											(SELECT `id` FROM `grants` ORDER BY `id` DESC LIMIT 1))';
	    $stmt2 = $this->conn->prepare($sql2);
	    $stmt2->execute(['adr1' => $adr1, 'adr2' => $adr2, 'cp' => $cp, 'ville' => $ville,  
											'mail' => $mail,  'password' => $password, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'actif' => $actif, 'partenaire' => $partenaire]);
	    return true;
	  }

	  // Update a structure ACTIF column in the database
	  public function updateActif($id, $actif) {

			$id = (int)$id;
			$actif = (int)$actif;

	    $sql = 'UPDATE structure SET actif = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['actif' => $actif, 'id' => $id]);
	    return true;
	  }

	  // Update a structure in the database
	  public function update($adr1, $adr2, $cp, $ville, $mail, $password, $sexegerant, $nomgerant, $actif, $partenaire ,$grants, $id) {
	    $sql = 'UPDATE structure SET adr1 = :adr1, adr2 = :adr2, cp = :cp, ville = :ville, mail = :mail, 
																	 password = :password, sexegerant = :sexegerant, nomgerant = :nomgerant, 
																	 actif = :actif, partenaire = :partenaire, grants = :grants WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['adr1' => $adr1, 'adr2' => $adr2, 'cp' => $cp, 'ville' => $ville,  
											'mail' => $mail,  'password' => $password, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'actif' => $actif, 'partenaire' => $partenaire, 
											'grants' => $grants, 'id' => $id]);
	    return true;
	  }

	  // Delete a structure from database
	  public function delete($id) {

			$sql = 'DELETE FROM grants WHERE id = (SELECT grants FROM structure WHERE id = :id)';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['id' => $id]);

	    $sql2 = 'DELETE FROM structure WHERE id = :id';
	    $stmt2 = $this->conn->prepare($sql2);
	    $stmt2->execute(['id' => $id]);
	    return true;
	  }
	}

?>