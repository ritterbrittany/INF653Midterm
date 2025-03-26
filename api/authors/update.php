<?php 
	//Headers
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: PUT');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

	include_once '../../config/Database.php';
	include_once '../../models/Authors.php';

	$database = new Database();
	$db = $database->connect();

	$author = new Authors($db);

	//Get raw data
	$data = json_decode(file_get_contents("php://input"));

//Set ID to Update
$author->id = $data->id;
$author->author = $data->author;

//Update author 
if($author->update()) {
	echo json_encode(
	array('message' => 'Author Updated')
);
} else {
	echo json_encode(
	array('message' => 'Author not Updated')
);
}
