<?php
class Database { 
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    // Constructor to retrieve values from the .htaccess environment variables
    public function __construct() {
        // Retrieve environment variables set in .htaccess
        $this->username = getenv('USERNAME');  // Your Render DB username
        $this->password = getenv('PASSWORD');  // Your Render DB password
        $this->db_name = getenv('DBNAME');     // Your Render DB name (quotesdb_rr23)
        $this->host = getenv('HOST');          // Your Render DB hostname
        $this->port = getenv('PORT');          // Default PostgreSQL port (5432)
        echo "Username: {$this->username}, Host: {$this->host}, Port: {$this->port}\n";
        echo "DBNAME: " . getenv('DBNAME') . "\n";
        echo "HOST: " . getenv('HOST') . "\n";
        echo "PORT: " . getenv('PORT') . "\n";
    }

    // Method to connect to the database
    public function connect() {
        $this->conn = null;
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
