<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../datesCRUD.php';
	
	// Create object of UsersTable class
	$tuple = new DatesCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single dates from database
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

		// Add a new dates into database
		if ($api == 'POST') {
			$g01 = $tuple->test_input($_POST['nom']);
									
			if ($g01 !== '' && $tuple->insert($g01)) {
				echo $tuple->message('date ajoutée avec succès !',false);
			} else {
				echo $tuple->message('Erreur lors de l\'ajout de la date !',true);
			}
		}

		// Update one dates in database
		if ($api == 'PUT') {
			parse_str(file_get_contents('php://input'), $post_input);

			// Sanitize et affecte les données à des variables locales
			//if(is_array($post_input) && array_key_exists('dates', $post_input)){
				if(is_array($post_input)){

				$nom = $tuple->test_input($post_input['nom']);
				$actif = $tuple->test_input($post_input['actif']);
				$id = $tuple->test_input($post_input['id']);
			} else {
				// Adaptation pour ANGULAR... :-(
					$dataArray = json_decode(file_get_contents('php://input'),true);
					if(is_array($dataArray)){
						$nom = $tuple->test_input($dataArray['nom']);
						$actif = $tuple->test_input($dataArray['actif']);
						$id = $tuple->test_input($dataArray['id']);
					}
			}

			// Exécution requête.
			if ($id != null && $id >= 1) {
				if ($tuple->update($nom, $actif, $id)) {
					echo $tuple->message('date modifiée avec succès !',false);
				} else {
					echo $tuple->message('Erreur lors de la modification de la date !',true);
				}
			} else {
				echo $tuple->message('date non trouvée !',true);
			}

		}
		// Delete a dates from database
		if ($api == 'DELETE') {
			if ($id != null && $id != 1) {
				if ($tuple->delete($id)) {
					echo $tuple->message('date supprimée !', false);
				} else {
					echo $tuple->message('Impossible de supprimer cette date !', true);
				}
			} else {
				echo $tuple->message('date non trouvée !', true);
			}
		}
	
	}// FIN CHECK TOKEN
	?>