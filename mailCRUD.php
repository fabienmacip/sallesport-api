<?php
	// Include config.php file
	include_once 'config.php';

	class MailCRUD extends Config {
	  
    // Fetch all or a single mail from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM mail';
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
		
    // Fetch all mails linked to a partenaire from database
		//readStructuresOfPartenaire
	  public function fetchMailsFromPartenaire($id) {
	    $sql = 'SELECT * FROM mail WHERE partenaire = :id ORDER BY id DESC';
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(['id' => $id]);
	    $rows = $stmt->fetchAll();
	    return $rows;
	  }


	  // Insert a mail in the database
	  public function insert($titre, $corps, $lien, $partenaire) {

			$partenaire = (int)$partenaire;

	    $sql = 'INSERT INTO mail (titre, corps, lien, lu, partenaire) 
											VALUES (:titre, :corps, :lien, 0, :partenaire)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['titre' => $titre, 'corps' => $corps, 'lien' => $lien, 
											'partenaire' => $partenaire]);
	    return true;
	  }

	  // Update one mail to put "lu" on 1
	  public function updateMailLu($id, $lu = 1) {
			$id = (int)$id;
			$lu = (int)$lu;
	    $sql = 'UPDATE mail SET `lu` = :lu WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
			$stmt->execute(['id' => $id, 'lu' => $lu]);
	    return true;
	  }


	  // Update a mail in the database
	  public function update($id, $titre, $corps, $lien, $lu) {

			$id = (int)$id;
			$lu = (int)$lu;

	    $sql = 'UPDATE mail SET titre = :titre, corps = :corps, lien = :lien, lu = :lu WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['titre' => $titre, 'corps' => $corps, 'lien' => $lien, 
											'lu' => $lu, 'id' => $id]);
	    return true;
	  }

	  // Delete a mail from database
	  public function delete($id) {
			$id = (int)$id;
	    $sql = 'DELETE FROM mail WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>