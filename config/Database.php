<?php
class Database {
    private $conn;

    // Constructor to retrieve connection details from the DATABASE_URL
    public function __construct() {
        // Retrieve the DATABASE_URL from the environment variable
        $url = getenv('DATABASE_URL');
        
        // If DATABASE_URL is not set, use the provided URL as fallback
        if (!$url) {
            $url = "postgresql://quotesdb_rr23_user:LTbRr4MOv7Hv63kyUOiFyRSgIzf3FrV0@dpg-cvi06052ng1s73a03scg-a/quotesdb_rr23";
        }

        // Parse the DATABASE_URL
        $parsed_url = parse_url($url);

        // Extract connection details from the parsed URL
        $this->host = $parsed_url['host'];
        $this->username = $parsed_url['user'];
        $this->password = $parsed_url['pass'];
        $this->db_name = ltrim($parsed_url['path'], '/');  // Remove the leading '/' from the path
        $this->port = 5432; // Default port for PostgreSQL (we can explicitly set this)

        // Optional: debugging line to see the values being used
        echo "Database connection info - Username: {$this->username}, Host: {$this->host}, Port: {$this->port}, Database: {$this->db_name}\n";  // Optional debugging line
    }

    // Method to connect to the database
    public function connect() {
        $this->conn = null;

        // Construct the DSN string for PostgreSQL
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";

        try {
            // Connect to PostgreSQL using PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>

