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
require_once '../../models/Quote.php';

// Instantiate database and Quote object
$database = new Database();
$db = $database->connect();

$quote = new Quotes($db);

// Get HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all quotes
        if (isset($_GET['id'])) {
            $quote->id = $_GET['id'];
            $quote->read_single();
            if ($quote->quote) {
                echo json_encode([
                    "id" => $quote->id,
                    "quote" => $quote->quote,
                    "author" => $quote->author,
                    "category" => $quote->category
                ]);
            } else {
                echo json_encode(["message" => "No Quotes Found"]);
            }
        } else {
            // Get all quotes
            $stmt = $quote->read();
            $quotes_arr = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quotes_arr[] = $row;
            }
            echo json_encode($quotes_arr);
        }
        break;

    case 'POST':
        // Create a new quote
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->quote) && isset($data->author_id) && isset($data->category_id)) {
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;
            if ($quote->create()) {
                echo json_encode(["message" => "Quote created."]);
            } else {
                echo json_encode(["message" => "Failed to create quote."]);
            }
        } else {
            echo json_encode(["message" => 'Missing Required Parameters']);
        }
        break;

		case 'PUT':
            // Update an existing quote
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
                $quote->id = $data->id;
                $quote->quote = $data->quote;
                $quote->author_id = $data->author_id;
                $quote->category_id = $data->category_id;

                if ($quote->update()) {
                    echo json_encode(array('message' => 'No Quotes Found'));
                } else {
                    echo json_encode(array('message' => 'Unable to update quote.'));
                }
            } else {
                echo json_encode(array('message' => 'Missing Required Parameters'));
            }
            break;

        case 'DELETE':
            // Delete a quote
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id)) {
                $quote->id = $data->id;

                if ($quote->delete()) {
                    echo json_encode(array('message' => 'No Quotes Found'));
                } else {
                    echo json_encode(array('message' => 'Unable to delete quote.'));
                }
            } else {
                echo json_encode(array('message' => 'No quote ID provided.'));
            }
            break;

        default:
            echo json_encode(array('message' => 'Invalid request method.'));
            break;
    }
?>