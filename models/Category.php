<?php 
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method === 'OPTIONS') {
	   header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	   header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
	   exit();
	}
    
    class Category {
        private $conn;
        private $table = 'category';

        public $id;
        public $category;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = 'SELECT id, category FROM ' . $this->table;

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        public function read_single() {
            $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) { 
            $this->id = $row['id'];
            $this->category = $row['category'];
        }
     }
      // Create Category
    public function create() {
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' (id,category) VALUES (:id, :category)';

         //Prepare Statement
         $stmt = $this->conn->prepare($query);
         
         //Clean data
         $this->id = trim(htmlspecialchars(strip_tags($this->id)));
         $this->category = trim(htmlspecialchars(strip_tags($this->category)));

         //Bind data
         $stmt->bindParam(':id', $this->id);
         $stmt->bindParam(':category', $this->category);


         //Execute query
         if($stmt->execute()) {
            return true;
         }

         // Print error if something goes wrong
         printf("Error: %s.\n", $stmt->error);

         return false;
    }
    //Update Category 

public function update(){

    //Create query
    $query = 'UPDATE ' . $this->table . ' 
        SET 
        category = :category
        WHERE id = :id';
    
    //Prepare Statement
    $stmt = $this->conn->prepare($query);
    
    //Clean data
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->category = htmlspecialchars(strip_tags($this->category));
    
    //Bind data
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':category', $this->category);
    
    //Execute query
    if($stmt->execute()) {
    return true;
    }
    
    //Print error if soemthing goes wrong 
    printf("Error: %s.\n", $stmt->error);
    
    return false; 
    }
    // Delete Category
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
