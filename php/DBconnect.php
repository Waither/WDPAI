<?php
class DBconnect {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn = null;

    // Class constructor
    public function __construct() {
        try {
            $this->servername = getenv('DB_HOST');
            $this->dbname = getenv('DB_NAME');
            $this->username = getenv('DB_USERNAME');
            $this->password = getenv('DB_PASSWORD');

            // Create new PDO object
            $this->conn = new PDO("pgsql:host={$this->servername};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            die("[SQL ERROR] Connection failed: {$e->getMessage()}");
        }
    }

    // Function to get data (DQL)
    private function getData(string $query, array $params = [], bool $assoc = true) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $fetchMode = $assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;

            return $stmt->fetchAll($fetchMode);
        }
        catch (PDOException $e) {
            return "Query failed: {$e->getMessage()}";
        }
    }

    // Function to execute query (DML)
    private function executeQuery(string $query, array $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return true;
        }
        catch (PDOException $e) {
            return "[SQL ERROR] Query failed: {$e->getMessage()}";
        }
    }

    // Funtion to connect and execute query
    public function query(string $query, array $params = [], bool $assoc = true) {
        
        
        // Arrays with DML & DQL commands
        $dmlCommands = ['INSERT', 'UPDATE', 'DELETE'];
        $dqlCommands = ['SELECT', 'EXECUTE', 'CALL'];

        // Uppercase and extract the first query word
        $queryType = strtoupper(strtok($query, ' '));

        // Check if first query word is DML Command 
        try {
            if (in_array($queryType, $dmlCommands)) {

                // Execute query
                $result = $this->executeQuery($query, $params);
        
                return $result;
            }
            
            // Check if first query word is DQL Command
            elseif (in_array($queryType, $dqlCommands)) {
        
                // Get data
                $data = $this->getData($query, $params, $assoc);
        
                return $data;
            }
            else {
                throw new Exception($queryType);
            }
        }
        catch (Exception $e) {
            return "[SQL Error] Unauthorized SQL command: {$e->getMessage()}";
        }
    }

    // Class destructor
    public function __destruct() {
        if ($this->conn !== null) {
            $this->conn = null;
        }
    }
}

$db = new DBconnect();