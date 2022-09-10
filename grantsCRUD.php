<?php
	// Include config.php file
	include_once 'config.php';

	class GrantsCRUD extends Config {
	  
    // Fetch all or a single grant from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM grants';
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

	  // Insert a grant in the database
	  public function insert($membersread, $memberswrite, $membersadd, $membersupdate, $membersproductsadd, 
													$memberspaymentscheduleread, $membersstatsread, $memberssubscriptionread, 
													$paymentschedulesread, $paymentscheduleswrite, $paymentdayread, $drinksell, 
													$foodsell, $sendnewsletter) {
	    $sql = 'INSERT INTO grants (membersread, memberswrite, membersadd, membersupdate, membersproductsadd, 
																	memberspaymentscheduleread, membersstatsread, memberssubscriptionread, 
																	paymentschedulesread, paymentscheduleswrite, paymentdayread, drinksell, 
																	foodsell, sendnewsletter) 
											VALUES (:membersread, :memberswrite, :membersadd, :membersupdate, :membersproductsadd, 
															:memberspaymentscheduleread, :membersstatsread, :memberssubscriptionread, 
															:paymentschedulesread, :paymentscheduleswrite, :paymentdayread, :drinksell, 
															:foodsell, :sendnewsletter)';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['membersread' => $membersread, 'memberswrite' => $memberswrite, 
											'membersadd' => $membersadd, 'membersupdate' => $membersupdate, 
											'membersproductsadd' => $membersproductsadd, 
											'memberspaymentscheduleread' => $memberspaymentscheduleread, 
											'membersstatsread' => $membersstatsread, 
											'memberssubscriptionread' => $memberssubscriptionread, 
											'paymentschedulesread' => $paymentschedulesread, 
											'paymentscheduleswrite' => $paymentscheduleswrite, 
											'paymentdayread' => $paymentdayread, 'drinksell' => $drinksell, 
											'foodsell' => $foodsell, 'sendnewsletter' => $sendnewsletter]);
	    return true;
	  }

	  // Update one grant in the database
	  public function updateOne($id, $grant, $actif) {
	    $sql = 'UPDATE grants SET `'.$grant.'` = :actif WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
			$stmt->execute(['actif' => $actif, 'id' => $id]);
	    return true;
	  }


	  // Update a grant in the database
	  public function update($membersread, $memberswrite, $membersadd, $membersupdate, $membersproductsadd, 
														$memberspaymentscheduleread, $membersstatsread, $memberssubscriptionread, 
														$paymentschedulesread, $paymentscheduleswrite, $paymentdayread, $drinksell, 
														$foodsell, $sendnewsletter, $id) {
	    $sql = 'UPDATE grants SET membersread = :membersread, memberswrite = :memberswrite, 
																membersadd = :membersadd, membersupdate = :membersupdate, 
																membersproductsadd = :membersproductsadd, 
																memberspaymentscheduleread = :memberspaymentscheduleread, 
																membersstatsread = :membersstatsread, 
																memberssubscriptionread = :memberssubscriptionread, 
																paymentschedulesread = :paymentschedulesread, 
																paymentscheduleswrite = :paymentscheduleswrite, 
																paymentdayread = :paymentdayread, drinksell = :drinksell, 
																foodsell = :foodsell, sendnewsletter = :sendnewsletter WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['membersread' => $membersread, 'memberswrite' => $memberswrite, 
											'membersadd' => $membersadd, 'membersupdate' => $membersupdate, 
											'membersproductsadd' => $membersproductsadd, 
											'memberspaymentscheduleread' => $memberspaymentscheduleread, 
											'membersstatsread' => $membersstatsread, 
											'memberssubscriptionread' => $memberssubscriptionread, 
											'paymentschedulesread' => $paymentschedulesread, 
											'paymentscheduleswrite' => $paymentscheduleswrite, 
											'paymentdayread' => $paymentdayread, 'drinksell' => $drinksell, 
											'foodsell' => $foodsell, 'sendnewsletter' => $sendnewsletter, 'id' => $id]);
	    return true;
	  }

	  // Delete a grant from database
	  public function delete($id) {
	    $sql = 'DELETE FROM grants WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    return true;
	  }
	}

?>