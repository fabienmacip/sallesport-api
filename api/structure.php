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
			$id = (int)$_GET['id'];
		}
		
		if(isset($_GET['partenaireId'])) {
			$partenaireId = (int)$_GET['partenaireId'];
		}
		
		
	$last = '';
	if(isset($_GET['last'])){
		$last = $_GET['last'];
	}


	  if(isset($partenaireId) && $partenaireId != 0){
			$data = $tuple->fetchLinkedToPartenaire($partenaireId, $last);
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
	$tokenok = $headers['X-API-KEY'] ?? $_SERVER['HTTP_X_API_KEY'] ?? '';
	if($tokenok == 'ok-token-admin' || $tokenok == 'ok-token-partenaire'){
	// END CHECK TOKEN		


		// Add a new structure into database
		if ($api == 'POST') {
			
			if(!$_POST){

				$dataArray = json_decode(file_get_contents('php://input'),true);

				$g01 = $tuple->test_input($dataArray['adr1']);
				$g02 = $tuple->test_input($dataArray['adr2']);
				$g03 = $tuple->test_input($dataArray['cp']);
				$g04 = $tuple->test_input($dataArray['ville']);
				$g05 = $tuple->test_input($dataArray['mail']);
				$g06 = $tuple->test_input($dataArray['password']);
				$g07 = $tuple->test_input($dataArray['sexegerant']);
				$g08 = $tuple->test_input($dataArray['nomgerant']);
				$g09 = $tuple->test_input($dataArray['actif']);
				$g10 = $tuple->test_input($dataArray['partenaire']);
				$g11 = $tuple->test_input($dataArray['grants']);
			} else {
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
			}
			

			if ($g01 !== '' && $g04 !== '' && $g05 !== '' && 
					$tuple->insert($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11)) {
				echo $tuple->message('Structure ajoutée avec succès !',false);
			} else {
				echo $tuple->message('Erreur lors de l\'ajout de la structure !',true);
			}
		}

		// Update a structure in database
		if ($api == 'PUT') {

			$onlyone = false;
			$mailId = '';

			parse_str(file_get_contents('php://input'), $post_input);

			if(array_key_exists('ville', $post_input) && (!array_key_exists('onlyone', $post_input))){
			// Si on souhaite modifier l'ensemble de la structure

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

		} elseif(array_key_exists('actif', $post_input) && array_key_exists('onlyone', $post_input))  {
			// Si on ne souhaite modifier que son champ ACTIF
			$actif = $tuple->test_input($post_input['actif']);
			//$id = $tuple->test_input($post_input['id']);
			$onlyone = true;
			
			// Si on doit également modifier le mail (si l'activation de la structure est faite via le bouton
			// dans le mail reçu par le partenaire).
			if(array_key_exists('mailId', $post_input)){
				$mailId = $tuple->test_input($post_input['mailId']);
			}
		}
		else {
			// Adaptation pour fonctionner avec ANGULAR
			$dataArray = json_decode(file_get_contents('php://input'),true);

			$onlyone = is_array($dataArray) && array_key_exists('onlyone', $dataArray);

			if(is_array($dataArray) && array_key_exists('ville', $dataArray) && !array_key_exists('onlyone', $dataArray)){
				$g01 = $tuple->test_input($dataArray['adr1']);
				$g02 = $tuple->test_input($dataArray['adr2']);
				$g03 = $tuple->test_input($dataArray['cp']);
				$g04 = $tuple->test_input($dataArray['ville']);
				$g05 = $tuple->test_input($dataArray['mail']);
				$g06 = $tuple->test_input($dataArray['password']);
				$g07 = $tuple->test_input($dataArray['sexegerant']);
				$g08 = $tuple->test_input($dataArray['nomgerant']);
				$g09 = $tuple->test_input($dataArray['actif']);
				$g10 = $tuple->test_input($dataArray['partenaire']);
				$g11 = $tuple->test_input($dataArray['grants']);
				$id = $tuple->test_input($dataArray['id']);			
			} elseif(is_array($dataArray) && array_key_exists('actif', $dataArray) && array_key_exists('onlyone', $dataArray)) {
				// Si on ne souhaite modifier que son champ ACTIF
				$actif = $tuple->test_input($dataArray['actif']);
				$id = $tuple->test_input($dataArray['id']);
				$onlyone = true;

				// Si on doit également modifier le mail (si l'activation de la structure est faite via le bouton
				// dans le mail reçu par le partenaire).
				if(is_array($dataArray) && array_key_exists('mailId', $dataArray)){
					$mailId = $tuple->test_input($dataArray['mailId']);
				}			

			}
		}

		if ($id != null) {
			if($onlyone == false){
					if ($tuple->update($g01, $g02, $g03, $g04, $g05, $g06, $g07, $g08, $g09, $g10, $g11, $id)) {
						echo $tuple->message('Structure modifiée avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification de la structure !',true);
					}
			} else {
				if($mailId == ''){
					if ($tuple->updateActif($id, $actif)) {
						echo $tuple->message('Structure (champ ACTIF) modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification de la structure (champ ACTIF) !',true);
					}
				} else {
							if ($tuple->updateActifAndMail($id, $actif, $mailId)) {
								echo $tuple->message('Structure (champ ACTIF) et Mail modifiés avec succès !',false);
							} else {
								echo $tuple->message('Erreur lors de la modification de la structure (champ ACTIF) et du mail !',true);
							}	
				}
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

	}// FIN CHECK TOKEN

?>