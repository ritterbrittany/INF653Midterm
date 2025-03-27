<?php 
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method === 'OPTIONS') {
	   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	   header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
	   exit();
	}
    
    class Quotes {
        private $conn;
        private $table = 'quotes';
        public $id;
        public $quote;
        public $author_id;
        public $category_id;

        public function __construct($db) {
            $this->conn = $db;
        }
        public function read() {
            $query = 'SELECT q.id, q.quote, a.author, c.category
            FROM ' . $this->table . ' q 
            LEFT JOIN authors a ON q.author_id = a.id
            LEFT JOIN category c ON q.category_id = c.id';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        public function read_single() {
            $query = 'SELECT q.id, q.quote, a.author, c.category
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN category c ON q.category_id = c.id
                  WHERE q.id = :id';
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            }
        }



          // Create Quote
    public function create() {
        // Check if the author_id exists in the authors table
        $authorExistsQuery = "SELECT COUNT(*) FROM authors WHERE id = :author_id";
        $stmt = $this->conn->prepare($authorExistsQuery);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->execute();
        $authorExists = $stmt->fetchColumn();

        if ($authorExists == 0) {
            // Log error and return message if author does not exist
            error_log("Error: author_id $this->author_id does not exist in authors table");
            echo json_encode(array('message' => 'Invalid author_id'));
            return false;
        }

        // Check if the category_id exists in the category table
        $categoryExistsQuery = "SELECT COUNT(*) FROM category WHERE id = :category_id";
        $stmt = $this->conn->prepare($categoryExistsQuery);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();
        $categoryExists = $stmt->fetchColumn();

        if ($categoryExists == 0) {
            // Log error and return message if category does not exist
            error_log("Error: category_id $this->category_id does not exist in category table");
            echo json_encode(array('message' => 'Invalid category_id'));
            return false;
        }

        //Create query
        $query = 'INSERT INTO ' . $this->table . ' (id, quote, author_id, category_id) 
              VALUES (:id, :quote, :author_id, :category_id)';

         //Prepare Statement
         $stmt = $this->conn->prepare($query);
         
         //Clean data
         $this->id = htmlspecialchars(strip_tags($this->id));
         $this->quote = htmlspecialchars(strip_tags($this->quote));
         $this->author_id = htmlspecialchars(strip_tags($this->author_id));
         $this->category_id = htmlspecialchars(strip_tags($this->category_id));
         
          // Check if IDs are valid
          if (empty($this->quote) || $this->author_id <= 0 || $this->category_id <= 0) {
            echo json_encode(array('message' => 'Invalid input values'));
            return false;
        }
        // Create query (remove id as it's auto-incremented)
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id)';
        // Prepare the statement
        $stmt = $this->conn->prepare($query);


         //Bind data
        // $stmt->bindParam(':id', $this->id);
         $stmt->bindParam(':quote', $this->quote);
         $stmt->bindParam(':author_id', $this->author_id);
         $stmt->bindParam(':category_id', $this->category_id);


         //Execute query
         if($stmt->execute()) {
            return true;
         }

         // Print error if something goes wrong
         printf("Error: %s.\n", $stmt->error);

         return false;
        }
//Update Quote

public function update(){

    //Create query
    $query = 'UPDATE ' . $this->table . ' 
        SET 
        quote = :quote,
        author_id = :author_id,
        category_id = :category_id
        WHERE id = :id';
    
    //Prepare Statement
    $stmt = $this->conn->prepare($query);
    
    //Clean data
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->quote= htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    
    //Bind data
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':quote', $this->quote);
    $stmt->bindParam(':author_id', $this->author_id);
    $stmt->bindParam(':category_id', $this->category_id);
    
    //Execute query
    if($stmt->execute()) {
    return true;
    }
    
    //Print error if soemthing goes wrong 
    printf("Error: %s.\n", $stmt->error);
    
    return false; 
    }
      // Delete Quote
public function delete() {
    try {
        // Create query 
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // If not successful, print error message
        printf("Error: %s.\n", $stmt->error);
        return false;
    } catch (PDOException $e) {
        // Catch any SQL errors, especially foreign key constraint violations
        echo json_encode(
            array('message' => 'Error: Cannot delete author due to foreign key constraints. ' . $e->getMessage())
        );
        return false;
    }
}
    }
?>   

