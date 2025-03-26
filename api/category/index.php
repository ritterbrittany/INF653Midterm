<?php 
	header('Access-Control-Allow=Origin: *');
	header('Content-Type: application/json');
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method === 'OPTIONS') {
	   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	   header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
	   exit();
	}
	// Include necessary files
require_once '../../config/database.php';
require_once '../../models/Category.php';

// Instantiate database and Category object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

// Get HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all categories
        if (isset($_GET['id'])) {
            $category->id = $_GET['id'];
            $category->read_single();
            if ($category->category) {
                echo json_encode([
                    "id" => $category->id,
                    "category" => $category->category
                ]);
            } else {
                echo json_encode(["message" => "Category not found."]);
            }
        } else {
            // Get all categories
            $stmt = $category->read();
            $categories_arr = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categories_arr[] = $row;
            }
            echo json_encode($categories_arr);
        }
        break;

    case 'POST':
        // Create a new category
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->category)) {
            $category->category = $data->category;
            if ($category->create()) {
                echo json_encode(["message" => "Category created."]);
            } else {
                echo json_encode(["message" => "Failed to create category."]);
            }
        } else {
            echo json_encode(["message" => "Missing category field."]);
        }
        break;

    case 'PUT':
        // Update category
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id) && isset($data->category)) {
            $category->id = $data->id;
            $category->category = $data->category;
            if ($category->update()) {
                echo json_encode(["message" => "Category updated."]);
            } else {
                echo json_encode(["message" => "Failed to update category."]);
            }
        } else {
            echo json_encode(["message" => "Missing fields."]);
        }
        break;

    case 'DELETE':
        // Delete category
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id)) {
            $category->id = $data->id;
            if ($category->delete()) {
                echo json_encode(["message" => "Category deleted."]);
            } else {
                echo json_encode(["message" => "Failed to delete category."]);
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
    ?>