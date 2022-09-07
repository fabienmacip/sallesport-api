<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../grantsCRUD.php';
	
	// Create object of UsersTable class
	$tuple = new GrantsCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single grant from database
	if ($api == 'GET') {
	  if ($id != 0) {
	    $data = $tuple->fetch($id);
	  } else {
	    $data = $tuple->fetch();
	  }
	  echo json_encode($data);
	}

	// Add a new grants into database
	if ($api == 'POST') {
	  $g01 = $tuple->test_input($_POST['membersread']);
	  $g02 = $tuple->test_input($_POST['memberswrite']);
	  $g03 = $tuple->test_input($_POST['membersadd']);
		$g04 = $tuple->test_input($_POST['membersupdate']);
		$g05 = $tuple->test_input($_POST['membersproductsadd']);
		$g06 = $tuple->test_input($_POST['memberspaymentscheduleread']);
		$g07 = $tuple->test_input($_POST['membersstatsread']);
		$g08 = $tuple->test_input($_POST['memberssubscriptionread']);
		$g09 = $tuple->test_input($_POST['paymentschedulesread']);
		$g10 = $tuple->test_input($_POST['paymentscheduleswrite']);
		$g11 = $tuple->test_input($_POST['paymentdayread']);
		$g12 = $tuple->test_input($_POST['drinksell']);
		$g13 = $tuple->test_input($_POST['foodsell']);
		$g14 = $tuple->test_input($_POST['sendnewsletter']);

	  if ($g01 !== '' && $g02 !== '' && $g03 !== '' && $g04 !== '' && 
				$tuple->insert($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11, $g12, $g13, $g14)) {
	    echo $tuple->message('Droits ajoutés avec succès !',false);
	  } else {
	    echo $tuple->message('Erreur lors de l\'ajout des droits !',true);
	  }
	}

	// Update a grants in database
	if ($api == 'PUT') {
	  parse_str(file_get_contents('php://input'), $post_input);

	  $g01 = $tuple->test_input($_POST['membersread']);
	  $g02 = $tuple->test_input($_POST['memberswrite']);
	  $g03 = $tuple->test_input($_POST['membersadd']);
		$g04 = $tuple->test_input($_POST['membersupdate']);
		$g05 = $tuple->test_input($_POST['membersproductsadd']);
		$g06 = $tuple->test_input($_POST['memberspaymentscheduleread']);
		$g07 = $tuple->test_input($_POST['membersstatsread']);
		$g08 = $tuple->test_input($_POST['memberssubscriptionread']);
		$g09 = $tuple->test_input($_POST['paymentschedulesread']);
		$g10 = $tuple->test_input($_POST['paymentscheduleswrite']);
		$g11 = $tuple->test_input($_POST['paymentdayread']);
		$g12 = $tuple->test_input($_POST['drinksell']);
		$g13 = $tuple->test_input($_POST['foodsell']);
		$g14 = $tuple->test_input($_POST['sendnewsletter']);

	  if ($id != null) {
	    if ($tuple->update($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11, $g12, $g13, $g14, $id)) {
	      echo $tuple->message('Droits modifiés avec succès !',false);
	    } else {
	      echo $tuple->message('Erreur lors de la modification des droits !',true);
	    }
	  } else {
	    echo $tuple->message('Droits non trouvés !',true);
	  }
	}

	// Delete a grants from database
	if ($api == 'DELETE') {
	  if ($id != null) {
	    if ($tuple->delete($id)) {
	      echo $tuple->message('Droits supprimés !', false);
	    } else {
	      echo $tuple->message('Impossible de supprimer les droits !', true);
	    }
	  } else {
	    echo $tuple->message('Droits non trouvés !', true);
	  }
	}

?>