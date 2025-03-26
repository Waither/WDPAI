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
    public function getData(string $query, array $params = []): array {
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
    public function executeQuery(string $query, array $params = []): bool {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return true;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getConn(): PDO {
        return $this->conn;
    }

    // Class destructor
    public function __destruct() {
        if ($this->conn !== null) {
            $this->conn = null;
        }
    }
}

function query(string $query, array $params = [], string $className = NULL): array|bool|string {
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
        
        // Check if first query word is DQL CommandX
        elseif (in_array($queryType, $dqlCommands)) {
    
            // Get data
            $data = $db->getData($query, $params);

            // Map data to objects
            if ($className == 'Place') {
                require_once 'Place.php';
                $data = array_map(
                    fn($row) => new Place(
                        $row['ID_place'], 
                        $row['name_place'], 
                        $row["types"] ?? "",
                        $row["placetags"] ?? "",
                        $row["name_company"], 
                        $row["address_place"], 
                        $row['latitude_place'], 
                        $row['longitude_place'], 
                        $row['fcn__avg_rating'], 
                        $row['image_place'] !== null ? 'data:image/jpeg;base64,'.base64_encode(stream_get_contents($row['image_place'])) : ""
                    ),
                    $data);
            }
            elseif ($className == 'Pin') {
                require_once 'Pin.php';
                $data = array_map(
                    fn($row) => new Pin($row['ID_place'], $row['name_place'], ["lng" => $row['longitude_place'], "lat" => $row['latitude_place']]),
                    $data);
            }
            elseif ($className == 'User') {
                require_once 'User.php';
                $data = array_map(
                    fn($row) => new User($row),
                    $data);
            }
    
            return $data;
        }
        else {
            throw new Exception($queryType);
        }
    }
    catch (Exception $e) {
        return "[SQL Error] {$e->getMessage()}";
    }
}