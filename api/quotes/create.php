<?php 
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quotes($db);

    //Get raw posted data 
    $data = json_decode(file_get_contents("php://input"));
    error_log(print_r($data, true));  // Log data to PHP error log
    //Ensuring required fields are present in request body
    //if (isset($data->id) && isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
   if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
    
    //$quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;
    }
    else{
        echo json_encode(
            array('mesage' => 'Missing required fields')
        );
    }
    //Create quote
    if($quote->create()) {
        echo json_encode(
            array('message' => 'Quote Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote Not Created')
        );
    }
    ?>