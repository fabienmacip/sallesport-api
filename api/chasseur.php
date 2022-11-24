<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../chasseurCRUD.php';
	
	// Create object of UsersTable class
	$tuple = new ChasseurCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single chasseur from database
	if ($api == 'GET') {
	  if ($id != 0) {
	    $data = $tuple->fetch($id);
	  } else {
	    $data = $tuple->fetch();
	  }
	  echo json_encode($data);
	}

	// CHECK TOKEN
	$headers = apache_request_headers();
	$tokenok = $headers['X-API-KEY'] ?? $_SERVER['HTTP_X_API_KEY'] ?? '';
	if($tokenok == 'ok-token-admin' || $tokenok == 'ok-token-partenaire'){
	// END CHECK TOKEN		

		// Add a new chasseur into database
		if ($api == 'POST') {
			$g01 = $tuple->test_input($_POST['nom']);
			$g02 = $tuple->test_input($_POST['prenom']);
						
			if ($g01 !== '' && $g02 !== '' && 
					$tuple->insert($g01, $g02)) {
				echo $tuple->message('Chasseur ajouté avec succès !',false);
			} else {
				echo $tuple->message('Erreur lors de l\'ajout du chasseur !',true);
			}
		}

		// Update one chasseur in database
		if ($api == 'PUT') {
			parse_str(file_get_contents('php://input'), $post_input);

			// Sanitize et affecte les données à des variables locales
			//if(is_array($post_input) && array_key_exists('chasseur', $post_input)){
				if(is_array($post_input)){

				$nom = $tuple->test_input($post_input['nom']);
				$prenom = $tuple->test_input($post_input['prenom']);
				$actif = $tuple->test_input($post_input['actif']);
			} else {
				// Adaptation pour ANGULAR... :-(
					$dataArray = json_decode(file_get_contents('php://input'),true);
					if(is_array($dataArray)){
						$nom = $tuple->test_input($dataArray['nom']);
						$prenom = $tuple->test_input($dataArray['prenom']);
						$actif = $tuple->test_input($dataArray['actif']);
						$id = $tuple->test_input($dataArray['id']);
					}
			}

			// Exécution requête. Impossible de supprimer le chasseur n°1. C'est l'admin.
			if ($id != null && $id != 1) {
				if ($tuple->update($nom, $prenom, $actif, $id)) {
					echo $tuple->message('Chasseur modifié avec succès !',false);
				} else {
					echo $tuple->message('Erreur lors de la modification du chasseur !',true);
				}
			} else {
				echo $tuple->message('Chasseur non trouvé !',true);
			}

		}
		// Delete a chasseur from database
		if ($api == 'DELETE') {
			if ($id != null && $id != 1) {
				if ($tuple->delete($id)) {
					echo $tuple->message('Chasseur supprimé !', false);
				} else {
					echo $tuple->message('Impossible de supprimer chasseur !', true);
				}
			} else {
				echo $tuple->message('Chasseur non trouvé !', true);
			}
		}
	
	}// FIN CHECK TOKEN
	?>