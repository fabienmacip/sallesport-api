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

	// CHECK TOKEN
	$headers = apache_request_headers();
	if(isset($headers['X-API-KEY']) && ($headers['X-API-KEY'] == 'ok-token-admin' || $headers['X-API-KEY'] == 'ok-token-partenaire')){
	// END CHECK TOKEN		

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

			$pwd = '';
			$g01 = '';
			$g03 = '';
			
			if(!array_key_exists('pwd', $post_input) && array_key_exists('name', $post_input)){
				$g01 = $tuple->test_input($post_input['name']);
				$g02 = $tuple->test_input($post_input['password']);
				$g03 = $tuple->test_input($post_input['email']);
			} elseif(array_key_exists('pwd', $post_input)){
				$pwd = (is_array($post_input) && array_key_exists('pwd', $post_input)) ? $tuple->test_input($post_input['pwd']) : '';
			} else {
				$dataArray = json_decode(file_get_contents('php://input'),true);
				$pwd = (is_array($dataArray) && array_key_exists('pwd', $dataArray)) ? $tuple->test_input($dataArray['pwd']) : '';
				if(is_array($dataArray) && !array_key_exists('pwd', $dataArray) && array_key_exists('name', $dataArray)){
					$g01 = $tuple->test_input($dataArray['name']);
					$g02 = $tuple->test_input($dataArray['password']);
					$g03 = $tuple->test_input($dataArray['email']);
					$id = $tuple->test_input($dataArray['id']);
				} elseif(is_array($dataArray) && array_key_exists('pwd', $dataArray)){
					$pwd = $tuple->test_input($dataArray['pwd']);
					$id = $tuple->test_input($dataArray['id']);
				}
			}
		
			if ($id != null) {
				if($pwd == '' && $g01 != '' && $g03 != ''){

					var_dump($id);
					var_dump($pwd);
					var_dump($g01);
					die();

					if ($g01 !== '' && $g02 !== '' && $g03 !== '' && $tuple->update($g01, $g02, $g03, $id)) {
						echo $tuple->message('Utilisateur modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification du Utilisateur !',true);
					}
				} else {
					if ($pwd != '' && $tuple->updatePassword($id, $pwd)) {
						echo $tuple->message('Utilisateur (mot de passe) modifié avec succès !',false);
					} else {
						echo $tuple->message('Erreur lors de la modification (mot de passe) de l\'Utilisateur !',true);
					}

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
	
	}// FIN CHECK TOKEN
?>