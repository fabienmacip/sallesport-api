<?php
	// Include CORS headers
	include_once '../headers.php';
	
	// Include action.php file
	include_once '../mailCRUD.php';
	
	// Create object of UsersTable class
	$tuple = new MailCRUD();

	// create a api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single mail from database
	if ($api == 'GET') {
	
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
	
		if(isset($_GET['partenaireId'])) {
			$partenaireId = $_GET['partenaireId'];
		}
	
	
		if(isset($partenaireId) && $partenaireId != 0){
			$data = $tuple->fetchMailsFromPartenaire($partenaireId);
		} else {
			if (isset($id) && $id != 0) {
				$data = $tuple->fetch($id);
			} else {
				$data = $tuple->fetch();
			}
		}

	  echo json_encode($data);
	}

	// CHECK TOKEN
	$headers = apache_request_headers();
	if(isset($headers['X-API-KEY']) && ($headers['X-API-KEY'] == 'ok-token-admin' || $headers['X-API-KEY'] == 'ok-token-partenaire')){
	// END CHECK TOKEN		

		// Add a new mail into database
		if ($api == 'POST') {
			
			
			if(!$_POST){

				$dataArray = json_decode(file_get_contents('php://input'),true);

				$g01 = $tuple->test_input($dataArray['titre']);
				$g02 = $tuple->test_input($dataArray['corps'], false);
				$g03 = $tuple->test_input($dataArray['lien']);
				$g04 = $tuple->test_input($dataArray['partenaire']);
			} else {
				$g01 = $tuple->test_input($_POST['titre']);
				$g02 = $tuple->test_input($_POST['corps'], false);
				$g03 = $tuple->test_input($_POST['lien']);
				$g04 = $tuple->test_input($_POST['partenaire']);
			}

			if ($g01 !== '' && $g02 !== '' && $g03 !== null && $g04 !== '' && 
					$tuple->insert($g01, $g02, $g03, $g04)) {
				echo $tuple->message('Mail ajouté avec succès !',false);
			} else {
				echo $tuple->message('Erreur lors de l\'ajout du mail !',true);
			}
		}

		// Update a mail in database
		if ($api == 'PUT') {

			$onlyLu = false;

			parse_str(file_get_contents('php://input'), $post_input);

			// Sanitize et affecte les données à des variables locales
			if(is_array($post_input) && array_key_exists('titre', $post_input) && (!array_key_exists('onlyLu', $post_input))){
				$titre = $tuple->test_input($post_input['titre']);
				$corps = $tuple->test_input($post_input['corps']);
				$lien = $tuple->test_input($post_input['lien']);
				$lu = $tuple->test_input($post_input['lu']);
			} elseif(array_key_exists('onlyLu', $post_input)){
				$lu = $tuple->test_input($post_input['lu']);
				$onlyLu = true;
			}
			else {
				// Adaptation pour ANGULAR... :-(
					$dataArray = json_decode(file_get_contents('php://input'),true);
					$onlyLu = is_array($dataArray) && array_key_exists('onlyLu', $dataArray);

					if(is_array($dataArray) && (!array_key_exists('onlyLu', $dataArray))){
						$titre = $tuple->test_input($dataArray['titre']);
						$corps = $tuple->test_input($dataArray['corps']);
						$lien = $tuple->test_input($dataArray['lien']);
						$lu = $tuple->test_input($dataArray['lu']);
						$id = $tuple->test_input($dataArray['id']);
					} elseif(is_array($dataArray) && (array_key_exists('onlyLu', $dataArray))){
						$lu = $tuple->test_input($dataArray['lu']);
						//$id = $tuple->test_input($dataArray['id']);
						$onlyLu = true;
					}
			}


			// Exécution requête.
			if ($id != null) {
				if($onlyLu == false){
					if ($tuple->update($id, $titre, $corps, $lien, $lu)) {
						echo $tuple->message('Mail modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification du mail !',true);
					}
				} else {
					if ($tuple->updateMailLu($id, $lu)) {
						echo $tuple->message('Champ LU du mail modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification du champ LU du mail !',true);
					}
				}
			} else {
				echo $tuple->message('Mail non trouvé !',true);
			}
		}

		// Delete a grants from database
		if ($api == 'DELETE') {
			if ($id != null && $id != 1) {
				if ($tuple->delete($id)) {
					echo $tuple->message('Mail supprimé !', false);
				} else {
					echo $tuple->message('Impossible de supprimer le mail !', true);
				}
			} else {
				echo $tuple->message('Mail non trouvé !', true);
			}
		}
	} // FIN CHECK TOKEN
?>