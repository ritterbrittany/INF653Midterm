<?php
class Database { 
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    // Constructor to retrieve values from the environment variables
    public function __construct() {
        // Retrieve environment variables set in Render's environment
        $this->username = getenv('USERNAME');  // Database username
        $this->password = getenv('PASSWORD');  // Database password
        $this->db_name = getenv('DBNAME');        // Database name
        $this->host = getenv('HOST');          // Database hostname (from the internal URL)
        $this->port = getenv('PORT');  // Ensure default port 5432

        echo "Database connection info - Username: {$this->username}, Host: {$this->host}, Port: {$this->port}\n";  // Optional debugging line
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
