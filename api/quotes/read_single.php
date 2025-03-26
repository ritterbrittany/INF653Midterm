<?php 
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quotes($db);

    //GetID
    $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

    //GET quote
    $quote->read_single();

    //Create array
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author,
        'category' => $quote->category

    );

    //Make JSON
    print_r(json_encode($quote_arr));
    ?>