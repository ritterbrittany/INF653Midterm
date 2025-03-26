<?php 
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $quote = new Quotes($db);

    $result = $quote->read();
    //Get row count
    $num = $result->rowCount();

    //Check if any quote
    if($num > 0) {
        $quote_arr = array();
        $quote_arr['data'] = array();

       while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $quote_item = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category
        );

        //Push to "data"
        array_push($quote_arr['data'], $quote_item);
       } 

       //Turn to JSON & output
       echo json_encode($quote_arr);

    }  else {
        //No Posts
        echo json_encode(
            array('message' => 'no posts found')
        );

    }
    ?>