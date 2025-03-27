<?php
class Category {
    private $conn;
    private $table = 'category';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all categories
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);

        try {
            // Execute the query
            $stmt->execute();
        } catch (PDOException $e) {
            // Catch any errors with the query execution
            echo json_encode(['message' => 'Error executing query: ' . $e->getMessage()]);
            return [];
        }

        // Create an array to store categories
        $categories_arr = [];

        // Fetch the results as an associative array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories_arr[] = $row;  // Add each row to the categories array
        }

        // Return the categories array
        return $categories_arr;
    }

    // Read a single category by id
    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id';

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
            $this->category = $row['category'];
        }
    }

    // Create a new category
    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' (id, category) VALUES (:id, :category)';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = trim(htmlspecialchars(strip_tags($this->id)));
        $this->category = trim(htmlspecialchars(strip_tags($this->category)));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':category', $this->category);

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

    // Update an existing category
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':category', $this->category);

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

    // Delete a category
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
                array('message' => 'Error: Cannot delete category due to foreign key constraints. ' . $e->getMessage())
            );
            return false;
        }

        // If deletion was not successful, return an error message
        echo json_encode(['message' => 'Failed to delete category.']);
        return false;
    }
}
?>
