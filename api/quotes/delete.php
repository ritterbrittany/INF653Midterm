<?php 
	//Headers
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: DELETE');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

	include_once '../../config/Database.php';
	include_once '../../models/Quote.php';

	$database = new Database();
	$db = $database->connect();

	$quote = new Quotes($db);

	//Get raw data
	$data = json_decode(file_get_contents("php://input"));

//Set ID to Update
$quote ->id = $data->id;



//Delete Quote 
if($quote ->delete()) {
	echo json_encode(
	array('message' => 'Quote Deleted')
);
} else {
	echo json_encode(
	array('message' => 'Quote not Deleted')
);
}