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

            $this->conn->exec("SET search_path TO truckstop");
        }
        catch (PDOException $e) {
            die("[SQL ERROR] Connection failed: {$e->getMessage()}");
        }
    }

    // Function to get data (DQL)
    public function getData(string $query, array $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Function to execute query (DML)
    public function executeQuery(string $query, array $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return true;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Class destructor
    public function __destruct() {
        if ($this->conn !== null) {
            $this->conn = null;
        }
    }
}

function query(string $query, string $className, array $params = []) {
    $db = new DBconnect();

    // Arrays with DML & DQL commands
    $dmlCommands = ['INSERT', 'UPDATE', 'DELETE'];
    $dqlCommands = ['SELECT', 'EXECUTE', 'CALL'];

    // Uppercase and extract the first query word
    $queryType = strtoupper(strtok($query, ' '));

    // Check if first query word is DML Command 
    try {
        if (in_array($queryType, $dmlCommands)) {

            // Execute query
            $result = $db->executeQuery($query, $params);
    
            return $result;
        }
        
        // Check if first query word is DQL Command
        elseif (in_array($queryType, $dqlCommands)) {
    
            // Get data
            $data = $db->getData($query, $params);

            // Map data to objects
            // if ($className == 'Place') {
            //     $data = array_map(
            //         fn($row) => new Place($row['ID'], $row['name'], $row["type"], $row["company"], $row['description'], $row["address"], $row['latitude'], $row['longitude'], $row['rating'], $row['image']),
            //         $data);
            // }
            // elseif ($className == 'Pin') {
            //     $data = array_map(
            //         fn($row) => new Pin($row['ID'], $row['name'], $row['location']),
            //         $data);
            // }
    
            return json_encode($data);
        }
        else {
            throw new Exception($queryType);
        }
    }
    catch (Exception $e) {
        return "[SQL Error] {$e->getMessage()}";
    }
}