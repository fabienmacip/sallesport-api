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

