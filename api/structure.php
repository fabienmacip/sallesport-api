<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../structureCRUD.php';
	
	// Create object of StructureCRUD class
	$tuple = new StructureCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single structure from database
	if ($api == 'GET') {

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	}

	if(isset($_GET['partenaireId'])) {
		$partenaireId = $_GET['partenaireId'];
	}


	  if(isset($partenaireId) && $partenaireId != 0){
			$data = $tuple->fetchLinkedToPartenaire($partenaireId);
		} else {
			if (isset($id) && $id != 0) {
				$data = $tuple->fetch($id);
			} else {
				$data = $tuple->fetch();
			}
		}
		
	  echo json_encode($data);
	}

	// Add a new structure into database
	if ($api == 'POST') {
	  $g01 = $tuple->test_input($_POST['adr1']);
		$g02 = $tuple->test_input($_POST['adr2']);
		$g03 = $tuple->test_input($_POST['cp']);
		$g04 = $tuple->test_input($_POST['ville']);
		$g05 = $tuple->test_input($_POST['mail']);
		$g06 = $tuple->test_input($_POST['password']);
	  $g07 = $tuple->test_input($_POST['sexegerant']);
	  $g08 = $tuple->test_input($_POST['nomgerant']);
		$g09 = $tuple->test_input($_POST['actif']);
		$g10 = $tuple->test_input($_POST['partenaire']);
		$g11 = $tuple->test_input($_POST['grants']);

	  if ($g01 !== '' && $g04 !== '' && $g05 !== '' && 
				$tuple->insert($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11)) {
	    echo $tuple->message('Structure ajoutée avec succès !',false);
	  } else {
	    echo $tuple->message('Erreur lors de l\'ajout de la structure !',true);
	  }
	}

	// Update a structure in database
	if ($api == 'PUT') {
	  parse_str(file_get_contents('php://input'), $post_input);

	  $g01 = $tuple->test_input($post_input['adr1']);
		$g02 = $tuple->test_input($post_input['adr2']);
		$g03 = $tuple->test_input($post_input['cp']);
		$g04 = $tuple->test_input($post_input['ville']);
		$g05 = $tuple->test_input($post_input['mail']);
		$g06 = $tuple->test_input($post_input['password']);
	  $g07 = $tuple->test_input($post_input['sexegerant']);
	  $g08 = $tuple->test_input($post_input['nomgerant']);
		$g09 = $tuple->test_input($post_input['actif']);
		$g10 = $tuple->test_input($post_input['partenaire']);
		$g11 = $tuple->test_input($post_input['grants']);

	  if ($id != null) {
	    if ($tuple->update($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11, $id)) {
	      echo $tuple->message('Structure modifiée avec succès !',false);
	    } else {
	      echo $tuple->message('Erreur lors de la modification de la structure !',true);
	    }
	  } else {
	    echo $tuple->message('Structure non trouvée !',true);
	  }
	}

	// Delete a structure from database
	if ($api == 'DELETE') {
	  if ($id != null) {
	    if ($tuple->delete($id)) {
	      echo $tuple->message('Structure supprimée !', false);
	    } else {
	      echo $tuple->message('Impossible de supprimer la structure !', true);
	    }
	  } else {
	    echo $tuple->message('Structure non trouvée !', true);
	  }
	}

?>