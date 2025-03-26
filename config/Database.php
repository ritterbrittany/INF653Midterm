<?php
class Database { 
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    // Constructor to retrieve values from the environment variables
    public function __construct() {
        // Retrieve environment variables set in Render's environment
        $this->username = getenv('USERNAME');  // Database username
        $this->password = getenv('PASSWORD');  // Database password
        $this->dbname = getenv('DBNAME');        // Database name
        $this->host = getenv('HOST');          // Database hostname (from the internal URL)
        $this->port = 5432;  // Ensure default port 5432


    }

    // Method to connect to the database
    public function connect() {
        if ($this->conn) {
            return $this->conn;
        }else {
        // Construct the DSN string for PostgreSQL
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

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
} 

