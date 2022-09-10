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
	  public function insert($nomfranchise, $sexegerant, $nomgerant, $mail, $password, $actif, $grants = 1) {

			$sql = 'INSERT INTO `grants` (`membersread`, `memberswrite`, `membersadd`, `membersupdate`, 
							`membersproductsadd`, `memberspaymentscheduleread`, `membersstatsread`, 
							`memberssubscriptionread`, `paymentschedulesread`, `paymentscheduleswrite`, 
							`paymentdayread`, `drinksell`, `foodsell`, `sendnewsletter`) 
							SELECT `membersread`, `memberswrite`, `membersadd`, `membersupdate`, 
							`membersproductsadd`, `memberspaymentscheduleread`, `membersstatsread`, 
							`memberssubscriptionread`, `paymentschedulesread`, `paymentscheduleswrite`, 
							`paymentdayread`, `drinksell`, `foodsell`, `sendnewsletter` 
							FROM `grants` WHERE `id` = 1';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();

			/* $sql2 = 'SELECT `id` FROM `grants` ORDER BY `id` DESC LIMIT 1';
			$stmt2 = $this->conn->prepare($sql2);
			$stmt2->execute();
 */

	    $sql3 = 'INSERT INTO partenaire (nomfranchise, sexegerant, nomgerant, mail, password, actif, grants) 
											VALUES (:nomfranchise, :sexegerant, :nomgerant, :mail, :password, :actif, (SELECT `id` FROM `grants` ORDER BY `id` DESC LIMIT 1))';
	    $stmt3 = $this->conn->prepare($sql3);
	    $stmt3->execute(['nomfranchise' => $nomfranchise, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'mail' => $mail,  'password' => $password, 
											'actif' => $actif]);

			return true;
	  }

	  // Update a partenaire ACTIF column in the database
	  public function updateActif($id, $actif) {

			$id = (int)$id;
			$actif = (int)$actif;

	    $sql = 'UPDATE partenaire SET actif = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['actif' => $actif, 'id' => $id]);
	    return true;
	  }

	  // Update a partenaire in the database
	  public function update($nomfranchise, $sexegerant, $nomgerant, $mail, $password, $actif, $grants, $id) {

			$id = (int)$id;
			$actif = (int)$actif;
			$grants = (int)$grants;

	    $sql = 'UPDATE partenaire SET nomfranchise = :nomfranchise, sexegerant = :sexegerant, 
																nomgerant = :nomgerant, mail = :mail,  password = :password, 
																actif = :actif, grants = :grants WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['nomfranchise' => $nomfranchise, 'sexegerant' => $sexegerant, 
											'nomgerant' => $nomgerant, 'mail' => $mail,  'password' => $password, 
											'actif' => $actif, 'grants' => $grants, 'id' => $id]);
	    return true;
	  }

	  // Delete a partenaire from database
	  public function delete($id) {

			$sql = 'DELETE FROM grants WHERE id = (SELECT grants FROM partenaire WHERE id = :id)';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['id' => $id]);

	    $sql2 = 'DELETE FROM partenaire WHERE id = :id';
	    $stmt2 = $this->conn->prepare($sql2);
	    $stmt2->execute(['id' => $id]);
	    return true;
	  }
	}

?>