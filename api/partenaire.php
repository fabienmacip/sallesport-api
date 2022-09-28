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
		
		
		// TEST TOKEN
		
		/* 
		$headers = apache_request_headers();
		foreach ($headers as $header => $value) {
			echo "$header: $value <br />\n";
		}  */
		
		/* if(isset($headers['token'])){
			echo $headers['token'];
		}
		
		die(); */
		
		if ($id != 0) {
			$data = $tuple->fetch($id);
	  } else {
			$data = $tuple->fetch();
	  }
		echo json_encode($data);
	}

	
	// CHECK TOKEN
	$headers = apache_request_headers();
	if(isset($headers['X-API-KEY']) && ($headers['X-API-KEY'] == 'ok-token-admin' || $headers['X-API-KEY'] == 'ok-token-partenaire')){
	// END CHECK TOKEN		
	

		// Add a new partenaire into database
		if ($api == 'POST') {
			
			if(!$_POST){ 
				// Adaptation pour fonctionner avec ANGULAR
				/* 			parse_str(file_get_contents('php://input'), $post_input);
				$dataString = implode("",array_keys($post_input));
				$dataArray = json_decode($dataString, true);
				*/
				$dataArray = json_decode(file_get_contents('php://input'),true);
				
				
				$g01 = $tuple->test_input($dataArray['nomfranchise']);
				$g02 = $tuple->test_input($dataArray['sexegerant']);
				$g03 = $tuple->test_input($dataArray['nomgerant']);
				$g04 = $tuple->test_input($dataArray['mail']);
				$g05 = $tuple->test_input($dataArray['password']);
				$g06 = $tuple->test_input($dataArray['actif']);
				//$g07 = $tuple->test_input($dataArray['grants']);
			} else {
				$g01 = $tuple->test_input($_POST['nomfranchise']);
				$g02 = $tuple->test_input($_POST['sexegerant']);
				$g03 = $tuple->test_input($_POST['nomgerant']);
				$g04 = $tuple->test_input($_POST['mail']);
				$g05 = $tuple->test_input($_POST['password']);
				$g06 = $tuple->test_input($_POST['actif']);
				//$g07 = $tuple->test_input($_POST['grants']);
			}


			if ($g01 !== '' && $g04 !== '' && $g05 !== '' && 
					$tuple->insert($g01, $g02, $g03, $g04, $g05, $g06, 1)) {
				echo $tuple->message('Partenaire ajouté avec succès !',false);
			} else {
				echo $tuple->message('Erreur lors de l\'ajout du partenaire !',true);
			}
		}

		// Update a partenaire in database
		if ($api == 'PUT') {
			$onlyone = false;
			$pwd = '';

			parse_str(file_get_contents('php://input'), $post_input);
			
			$onlyone = is_array($post_input) && array_key_exists('onlyone', $post_input);
			$pwd = (is_array($post_input) && array_key_exists('pwd', $post_input)) ? $tuple->test_input($post_input['pwd']) : '';

			if(array_key_exists('nomfranchise', $post_input) && (!array_key_exists('onlyone', $post_input))){
					// Si on souhaite modifier l'ensemble du partenaire
					$g01 = $tuple->test_input($post_input['nomfranchise']);
					$g02 = $tuple->test_input($post_input['sexegerant']);
					$g03 = $tuple->test_input($post_input['nomgerant']);
					$g04 = $tuple->test_input($post_input['mail']);
					$g05 = $tuple->test_input($post_input['password']);
					$g06 = $tuple->test_input($post_input['actif']);
					$g07 = $tuple->test_input($post_input['grants']);
					//$id = $tuple->test_input($post_input['id']);
				} elseif(array_key_exists('actif', $post_input) && array_key_exists('onlyone', $post_input))  {
					// Si on ne souhaite modifier que son champ ACTIF
					$actif = $tuple->test_input($post_input['actif']);
					//$id = $tuple->test_input($post_input['id']);
					$onlyone = true;
				} elseif(is_array($post_input) && array_key_exists('pwd', $post_input)){
					//$id = $tuple->test_input($post_input['id']);
					$onlyone = true;
				}
			else {
				// Adaptation pour fonctionner avec ANGULAR
	/* 			$dataString = implode("",array_keys($post_input));
				$dataArray = json_decode($dataString, true);
	*/		
				$dataArray = json_decode(file_get_contents('php://input'),true);

				$onlyone = is_array($dataArray) && array_key_exists('onlyone', $dataArray);
				$pwd = (is_array($dataArray) && array_key_exists('pwd', $dataArray)) ? $tuple->test_input($dataArray['pwd']) : '';

				if(is_array($dataArray) && array_key_exists('nomfranchise', $dataArray) && !array_key_exists('onlyone', $dataArray)){
					$g01 = $tuple->test_input($dataArray['nomfranchise']);
					$g02 = $tuple->test_input($dataArray['sexegerant']);
					$g03 = $tuple->test_input($dataArray['nomgerant']);
					$g04 = $tuple->test_input($dataArray['mail']);
					$g05 = $tuple->test_input($dataArray['password']);
					$g06 = $tuple->test_input($dataArray['actif']);
					$g07 = $tuple->test_input($dataArray['grants']);
					$id = $tuple->test_input($dataArray['id']);			
				} elseif(is_array($dataArray) && array_key_exists('actif', $dataArray) && array_key_exists('onlyone', $dataArray)) {
					// Si on ne souhaite modifier que son champ ACTIF
					$actif = $tuple->test_input($dataArray['actif']);
					$id = $tuple->test_input($dataArray['id']);
					$onlyone = true;
				} elseif(is_array($dataArray) && array_key_exists('pwd', $dataArray)){
					$id = $tuple->test_input($dataArray['id']);
					$onlyone = true;
				}

			}
			



			if ($id != null) {
				if($onlyone == false){
					if ($tuple->update($g01, $g02, $g03, $g04, $g05, $g06, $g07, $id)) {
						echo $tuple->message('Partenaire modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification du partenaire !',true);
					}
				} else {
					if($pwd == ''){
						if ($tuple->updateActif($id, $actif)) {
							echo $tuple->message('Partenaire (champ ACTIF) modifié avec succès !',false);
						} else {
							echo $tuple->message('Erreur lors de la modification du partenaire (champ ACTIF) !',true);
						}
					} else {
						if ($tuple->updatePassword($id, $pwd)) {
							echo $tuple->message('Partenaire (champ MOT DE PASSE) modifié avec succès !',false);
						} else {
							echo $tuple->message('Erreur lors de la modification du partenaire (champ MOT DE PASSE) !',true);
						}

					}
					

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
	}// FIN du IF token OK

?>