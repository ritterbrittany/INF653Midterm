<?php
class Authors {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all authors
    public function read() {
        // Create query
        $query = 'SELECT id, author FROM ' . $this->table;

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        try {
            // Execute the query
            $stmt->execute();
        } catch (PDOException $e) {
            // Catch any errors with the query execution
            echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
            return [];
        }

        // Create an array to store authors
        $authors_arr = [];

        // Fetch the results as an associative array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $authors_arr[] = $row;  // Add each row to the authors array
        }

        // Return the authors array
        return $authors_arr;
    }

    // Read a single author by id
    public function read_single() {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the parameter
        $stmt->bindParam(':id', $this->id);

        try {
            // Execute the query
            $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
            return null;
        }

        // Fetch the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->author = $row['author'];
        }
    }

    // Create a new author
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' (id, author) VALUES (:id, :author)';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':author', $this->author);

        try {
            // Execute the query
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Print error if something goes wrong
            echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
            return false;
        }

        return false;
    }

    // Update an existing author
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':author', $this->author);

        try {
            // Execute the query
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Print error if something goes wrong
            echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
            return false;
        }

        return false;
    }

    // Delete an author
    public function delete() {
        try {
            // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute the query
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Catch SQL errors, especially foreign key constraint violations
            echo json_encode(
                array('message' => 'Error: Cannot delete author due to foreign key constraints. ' . $e->getMessage())
            );
            return false;
        }

        // If deletion was not successful, return an error message
        echo json_encode(['message' => 'Failed to delete author.']);
        return false;
    }
}
?>
