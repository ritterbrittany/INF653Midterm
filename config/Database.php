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
        $this->username = getenv('DB_USERNAME');  // Database username
        $this->password = getenv('DB_PASSWORD');  // Database password
        $this->db_name = getenv('DBNAME');        // Database name
        $this->host = getenv('DB_HOST');          // Database hostname (from the internal URL)
        $this->port = getenv('DB_PORT');          // PostgreSQL default port (5432)

        // Debugging: Check the values
        echo "Username: {$this->username}, Host: {$this->host}, Port: {$this->port}\n";
        echo "DBNAME: " . getenv('DBNAME') . "\n";
        echo "HOST: " . getenv('DB_HOST') . "\n";
        echo "PORT: " . getenv('DB_PORT') . "\n";
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

