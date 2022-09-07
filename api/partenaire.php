<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../partenaireCRUD.php';
	
	// Create object of PartenaireCRUD class
	$tuple = new PartenaireCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single partenaire from database
	if ($api == 'GET') {
	  if ($id != 0) {
	    $data = $tuple->fetch($id);
	  } else {
	    $data = $tuple->fetch();
	  }
	  echo json_encode($data);
	}

	// Add a new partenaire into database
	if ($api == 'POST') {
	  $g01 = $tuple->test_input($_POST['nomfranchise']);
	  $g02 = $tuple->test_input($_POST['sexegerant']);
	  $g03 = $tuple->test_input($_POST['nomgerant']);
		$g04 = $tuple->test_input($_POST['mail']);
		$g05 = $tuple->test_input($_POST['password']);
		$g06 = $tuple->test_input($_POST['actif']);
		$g07 = $tuple->test_input($_POST['grantsid']);

	  if ($g01 !== '' && $g04 !== '' && $g05 !== '' && 
				$tuple->insert($g01, $g02, $g03, $g04, $g05, $g06, $g07)) {
	    echo $tuple->message('Partenaire ajouté avec succès !',false);
	  } else {
	    echo $tuple->message('Erreur lors de l\'ajout du partenaire !',true);
	  }
	}

	// Update a partenaire in database
	if ($api == 'PUT') {
	  parse_str(file_get_contents('php://input'), $post_input);

	  $g01 = $tuple->test_input($post_input['nomfranchise']);
	  $g02 = $tuple->test_input($post_input['sexegerant']);
	  $g03 = $tuple->test_input($post_input['nomgerant']);
		$g04 = $tuple->test_input($post_input['mail']);
		$g05 = $tuple->test_input($post_input['password']);
		$g06 = $tuple->test_input($post_input['actif']);
		$g07 = $tuple->test_input($post_input['grantsid']);

	  if ($id != null) {
	    if ($tuple->update($g01, $g02, $g03, $g04, $g05, $g06, $g07, $id)) {
	      echo $tuple->message('Partenaire modifié avec succès !',false);
	    } else {
	      echo $tuple->message('Erreur lors de la modification du partenaire !',true);
	    }
	  } else {
	    echo $tuple->message('Partenaire non trouvé !',true);
	  }
	}

	// Delete a partenaire from database
	if ($api == 'DELETE') {
	  if ($id != null) {
	    if ($tuple->delete($id)) {
	      echo $tuple->message('Partenaire supprimé !', false);
	    } else {
	      echo $tuple->message('Impossible de supprimer le partenaire !', true);
	    }
	  } else {
	    echo $tuple->message('Partenaire non trouvé !', true);
	  }
	}

?>