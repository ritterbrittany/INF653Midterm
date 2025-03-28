<?php 
	//Headers
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: PUT');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

	include_once '../../config/Database.php';
	include_once '../../models/Quote.php';

	$database = new Database();
	$db = $database->connect();

	$quote = new Quotes($db);

	//Get raw data
	$data = json_decode(file_get_contents("php://input"));

//Set ID to Update
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

//Update quote 
if($quote->update()) {
	echo json_encode(
	array('message' => 'Quote Updated')
);
} else {
	echo json_encode(
	array('message' => 'Quote not Updated')
);
}