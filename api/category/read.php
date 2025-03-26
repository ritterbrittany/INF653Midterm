<?php 
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $result = $category->read();
    //Get row count
    $num = $result->rowCount();

    //Check if any category
    if($num > 0) {
        $category_arr = array();
        $category_arr['data'] = array();

       while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $category_item = array(
            'id' => $id,
            'category' => $category
        );

        //Push to "data"
        array_push($category_arr['data'], $category_item);
       } 

       //Turn to JSON & output
       echo json_encode($category_arr);

    }  else {
        //No Posts
        echo json_encode(
            array('message' => 'no posts found')
        );
    }
    ?>