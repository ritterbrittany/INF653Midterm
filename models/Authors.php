<?php 
    class Authors {
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db) {
            $this->conn = $db;
        }
        public function read() {
            //Create query
            $query = 'SELECT id, author FROM ' . $this->table;
            
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }
        public function read_single() {
            $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) { 
            $this->id = $row['id'];
            $this->author = $row['author'];
        }
    }
    // Create Author
    public function create() {
        //Create query
        $query = 'INSERT INTO ' . $this->table . ' (id,author) VALUES (:id, :author)';

         //Prepare Statement
         $stmt = $this->conn->prepare($query);
         
         //Clean data
         $this->id = htmlspecialchars(strip_tags($this->id));
         $this->author = htmlspecialchars(strip_tags($this->author));

         //Bind data
         $stmt->bindParam(':id', $this->id);
         $stmt->bindParam(':author', $this->author);


         //Execute query
         if($stmt->execute()) {
            return true;
         }

         // Print error if something goes wrong
         printf("Error: %s.\n", $stmt->error);

         return false;
    }
//Update Author 

public function update(){

//Create query
$query = 'UPDATE ' . $this->table . ' 
    SET 
	author = :author
    WHERE id = :id';

//Prepare Statement
$stmt = $this->conn->prepare($query);

//Clean data
$this->id = htmlspecialchars(strip_tags($this->id));
$this->author = htmlspecialchars(strip_tags($this->author));

//Bind data
$stmt->bindParam(':id', $this->id);
$stmt->bindParam(':author', $this->author);

//Execute query
if($stmt->execute()) {
return true;
}

//Print error if soemthing goes wrong 
printf("Error: %s.\n", $stmt->error);

return false; 
}


// Delete Author 
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