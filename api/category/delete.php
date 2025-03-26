<?php 
	//Headers
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Allow-Methods: DELETE');
	header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

	include_once '../../config/Database.php';
	include_once '../../models/Category.php';

	$database = new Database();
	$db = $database->connect();

	$category = new Category($db);

	//Get raw data
	$data = json_decode(file_get_contents("php://input"));

//Set ID to Update
$category ->id = $data->id;



//Delete category  
if($category ->delete()) {
	echo json_encode(
	array('message' => 'Category  Deleted')
);
} else {
	echo json_encode(
	array('message' => 'Category not Deleted')
);
}