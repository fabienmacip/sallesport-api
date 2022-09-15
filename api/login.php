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
	  if ($_GET['mail'] != '' && $_GET['password'] != '') {
	    $data = $tuple->fetchLogin($_GET['mail'], $_GET['password']);
	  } else {
	    http_response_code(404);
	  }
	  echo json_encode($data);
	}

	// Get all or a single user from database
	if ($api == 'POST') {

		if(!$_POST){

			$dataArray = json_decode(file_get_contents('php://input'),true);

			$mail = $tuple->test_input($dataArray['mail']);
			$pwd = $tuple->test_input($dataArray['password']);
		} else {
			$mail = $tuple->test_input($_POST['mail']);
			$pwd = $tuple->test_input($_POST['password']);
		}

	  if ($mail != '' && $pwd != '') {
	    $data = $tuple->fetchLogin($mail, $pwd);
	  } else {
	    http_response_code(404);
	  }
	  echo json_encode($data);
	}


















// Include CORS headers
/* include_once '../headers.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

if(isset($postdata) && !empty($postdata)){
  $pwd = trim($request->password);
  $email = trim($request->mail);

  $sql = "SELECT * FROM user WHERE email=:mail AND password=:password";
  $stmt = $this->conn->prepare($sql);
  $stmt->execute(['email' => $email, 'password' => $pwd]);

  $rows = $stmt->fetchAll();

  if(!empty($rows)){
    echo json_encode($rows);
  }
  else {
    http_response_code(404);
  }
} */

