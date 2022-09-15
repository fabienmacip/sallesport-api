<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../userCRUD.php';
	
	// Create object of UserCRUD class
	$tuple = new UserCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single user from database
	if ($api == 'GET') {
	  if ($mail != '' && $password != '') {
	    $data = $tuple->fetch($mail, $password);
	  } else {
	    http_response_code(404);
	  }
	  echo json_encode($data);
	}

	// Add a new user into database
	if ($api == 'POST') {
	  $g01 = $tuple->test_input($_POST['name']);
		$g02 = $tuple->test_input($_POST['password']);
		$g03 = $tuple->test_input($_POST['email']);
		
	  if ($g01 !== '' && $g02 !== '' && $g03 !== '' && 
				$tuple->insert($g01, $g02, $g03)) {
	    echo $tuple->message('Utilisateur ajouté avec succès !',false);
	  } else {
	    echo $tuple->message('Erreur lors de l\'ajout du Utilisateur !',true);
	  }
	}

	// Update a user in database
	if ($api == 'PUT') {
	  parse_str(file_get_contents('php://input'), $post_input);

	  $g01 = $tuple->test_input($post_input['name']);
		$g02 = $tuple->test_input($post_input['password']);
		$g03 = $tuple->test_input($post_input['email']);

	  if ($id != null) {
	    if ($g01 !== '' && $g02 !== '' && $g03 !== '' && $tuple->update($g01, $g02, $g03, $id)) {
	      echo $tuple->message('Utilisateur modifié avec succès !',false);
	    } else {
	      echo $tuple->message('Erreur lors de la modification du Utilisateur !',true);
	    }
	  } else {
	    echo $tuple->message('Utilisateur non trouvé !',true);
	  }
	}

	// Delete a user from database
	if ($api == 'DELETE') {
	  if ($id != null) {
	    if ($tuple->delete($id)) {
	      echo $tuple->message('Utilisateur supprimé !', false);
	    } else {
	      echo $tuple->message('Impossible de supprimer le Utilisateur !', true);
	    }
	  } else {
	    echo $tuple->message('Utilisateur non trouvé !', true);
	  }
	}

?>