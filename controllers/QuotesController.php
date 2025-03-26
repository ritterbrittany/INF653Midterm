<?php

include_once '../config/Database.php';
include_once '../models/Authors.php';
include_once '../models/Category.php';
include_once '../models/Quote.php';


$database = new Database();
$db = $database->connect();


$authors = new Authors($db);
$category = new Category($db);
$quote = new Quote($db);


$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method) {
    case 'GET':
        
        if (isset($_GET['quote_id'])) {
            $quote->id = $_GET['quote_id'];
            $stmt = $quote->read_single();
            if ($stmt->rowCount() > 0) {
                $quote_arr = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($quote_arr);
            } else {
                echo json_encode(array('message' => 'Quote not found.'));
            }
        } elseif (isset($_GET['author_id'])) {
            $authors->id = $_GET['author_id'];
            $stmt = $authors->read_single();
            if ($stmt->rowCount() > 0) {
                $author_arr = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($author_arr);
            } else {
                echo json_encode(array('message' => 'Author not found.'));
            }
        } elseif (isset($_GET['category_id'])) {
            $category->id = $_GET['category_id'];
            $stmt = $category->read_single();
            if ($stmt->rowCount() > 0) {
                $category_arr = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($category_arr);
            } else {
                echo json_encode(array('message' => 'Category not found.'));
            }
        } else {
            
            if (isset($_GET['quotes'])) {
                $stmt = $quote->read();
                $quotes_arr = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = $row;
                }
                echo json_encode($quotes_arr);
            } elseif (isset($_GET['authors'])) {
                $stmt = $authors->read();
                $authors_arr = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $authors_arr[] = $row;
                }
                echo json_encode($authors_arr);
            } elseif (isset($_GET['categories'])) {
                $stmt = $category->read();
                $categories_arr = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $categories_arr[] = $row;
                }
                echo json_encode($categories_arr);
            }
        }
        break;

    default:
        header("HTTP/1.1 405 Method Not Allowed");
        break;
}
?>