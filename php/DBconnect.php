<?php
class DBconnect {
    private $servername;
    private $username;
    private $password;
    private $conn = null;
    private $database = null;

    // Class constructor
    public function __construct() {
        try {

            // Assign environment variables to properties
            $this->servername = getenv('PG_SERVERNAME');
            $this->username = getenv('PG_USERNAME');
            $this->password = getenv('PG_PASSWORD');

            // Create new PDO object
            $this->conn = new PDO("mysql:host={$this->servername}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            die("[SQL ERROR] Connection failed: {$e->getMessage()}");
        }
    }

    // Function to choose type of database
    private function chooseDatabase(bool $ADM) {
        try {
            switch (true) {
                case $ADM:
                    $databaseType = 'adm';
                    break;
                case getUserIP() == "UNKNOWN":
                    $databaseType = 'prd';
                    break;
                case isset($_SERVER['INSTANCE_NAME']) && $_SERVER['INSTANCE_NAME'] === 'ADNET_PRD':
                    $databaseType = 'prd';
                    break;
                case isset($_SERVER['INSTANCE_NAME']) && $_SERVER['INSTANCE_NAME'] === 'ADNET_UAT':
                    $databaseType = 'uat';
                    break;
                default:
                    throw new Exception('[SQL Error] Unknown INSTANCE_NAME');
            }
    
            $database = "db_$databaseType";
    
            $this->database = $database;

            // Switch database
            $this->conn->exec("USE $database");
        }
        catch (PDOException $e) {
            die("[SQL Error] Failed to select database: {$e->getMessage()}");
        }
    }

    // Function to get data (DQL)
    public function getData(string $query, array $params = [], bool $ADM = false, bool $assoc = true) {
        $this->chooseDatabase($ADM);

        try {
            // Prepare a statement for execution and returns a statement object
            $stmt = $this->conn->prepare($query);

            // Execute a prepared statement
            $stmt->execute($params);
            
            // Set the fetch mode
            $fetchMode = $assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;

            // Return an array containing all of the result set rows
            return $stmt->fetchAll($fetchMode);
        }
        catch (PDOException $e) {
            return "Query failed: {$e->getMessage()}";
        }
    }

    // Function to execute query (DML)
    public function executeQuery(string $query, array $params = [], bool $ADM = false) {
        $this->chooseDatabase($ADM);

        // Check if connection exists
        if ($this->conn === null) {
            throw new Exception('No active database connection');
        }

        try {
            // Prepare a statement for execution and returns a statement object
            $stmt = $this->conn->prepare($query);

            // Execute a prepared statement
            $stmt->execute($params);

            return true;
        }
        catch (PDOException $e) {
            return "[SQL ERROR] Query failed: {$e->getMessage()}";
        }
    }

    // Class destructor
    public function __destruct() {
        if ($this->conn !== null) {
            $this->conn = null;
        }
    }
}

// Funtion to connect and execute query
function query(string $query, array $params = [], bool $ADM = false, bool $assoc = true) {
    $db = new DBconnect();
    
    // Arrays with DML & DQL commands
    $dmlCommands = ['INSERT', 'UPDATE', 'DELETE'];
    $dqlCommands = ['SELECT', 'EXECUTE', 'CALL'];

    // Uppercase and extract the first query word
    $queryType = strtoupper(strtok($query, ' '));

    // Check if first query word is DML Command 
    if (in_array($queryType, $dmlCommands)) {

        // Execute query
        $result = $db->executeQuery($query, $params, $ADM);

        return $result;
    }
    
    // Check if first query word is DQL Command
    elseif (in_array($queryType, $dqlCommands)) {

        // Get data
        $data = $db->getData($query, $params, $ADM, $assoc);

        return $data;
    }
    else {
        throw new Exception("Unauthorized SQL command: {$queryType}");
    }
}

// Function to create new log
function newLog($query, $database) {

    // Retrieve user's IP
    $ip = getUserIp();

    // Get the hostname corresponding to a given IP address
    $host = gethostbyaddr($ip);
    
    // Create insert query
    $params = [':ip' => $ip, ':host' => $host, ':query' => $query, 'database' => $database];
    $query = 'INSERT INTO logs VALUES (NULL, CURRENT_TIMESTAMP, :ip, :host, :database, :query)';

    $db = new DBconnect();

    // Execute query
    $db->executeQuery($query, $params, true);
}

// Function to get User's IP
function getUserIp() {
    $ip = 'UNKNOWN';
    $headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($headers as $header) {
        // $_SERVER is an array containing information such as headers, paths, and script locations.
        // Check if $_SERVER includes specific header
        if (!empty($_SERVER[$header])) {
            // Separate values into array elements
            $ips = explode(',', $_SERVER[$header]);

            foreach ($ips as $ip) {
                // Strip whitespace from the beginning and end of a string
                $ip = trim($ip);

                // Check if IP is correct
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }

    return $ip;
}