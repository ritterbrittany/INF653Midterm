<?php 
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method === 'OPTIONS') {
	   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	   header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
	   exit();
	}
    // Include necessary files
require_once '../../config/Database.php';
require_once '../../models/Authors.php';

// Instantiate database and Authors object
$database = new Database();
$db = $database->connect();

$author = new Authors($db);

// Get HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all authors
        if (isset($_GET['id'])) {
            $author->id = $_GET['id'];
            $author->read_single();
            if ($author->author) {
                echo json_encode([
                    "id" => $author->id,
                    "author" => $author->author
                ]);
            } else {
                echo json_encode(["message" => "author_id Not Found"]);
            }
        } else {
            // Get all authors
            $stmt = $author->read();
            $authors_arr = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $authors_arr[] = $row;
            }
            echo json_encode($authors_arr);
        }
        break;

    case 'POST':
        // Create a new author
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->author)) {
            $author->author = $data->author;
            if ($author->create()) {
                echo json_encode(["message" => "Author created."]);
            } else {
                echo json_encode(["message" => "Failed to create author."]);
            }
        } else {
            echo json_encode(["message" => "Missing Required Parameters"]);
        }
        break;

    case 'PUT':
        // Update author
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id) && isset($data->author)) {
            $author->id = $data->id;
            $author->author = $data->author;
            if ($author->update()) {
                echo json_encode(["message" => "Author updated."]);
            } else {
                echo json_encode(["message" => "Failed to update author."]);
            }
        } else {
            echo json_encode(["message" => "Missing fields."]);
        }
        break;

    case 'DELETE':
        // Delete author
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id)) {
            $author->id = $data->id;
            if ($author->delete()) {
                echo json_encode(["message" => "Author deleted."]);
            } else {
                echo json_encode(["message" => "Failed to delete author."]);
            }
        } else {
            echo json_encode(["message" => "Missing ID."]);
        }
        break;

    default:
        // Method not allowed
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
?>
