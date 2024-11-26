<?php 

// ini_set('display_errors', 0);

// Begins session for user
session_start();

// Initializes credentials of a logged-in user
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user_tbl
            WHERE uname_id_fld = {$_SESSION["user_id"]};";

    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

// Contents down below will only function if the user is set (Administrator)
if (isset($user) && ($user["role_fld"]) == "Administrator") {

    // Database connection
    $mysqli = require __DIR__ . "/database.php";

    // Check if the request is via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieves the posted username from the AJAX request
        $searchTerm = isset($_POST['q']) ? trim($_POST['q']) : '';

        // Prepares the SQL query
        $sql = "SELECT uname_id_fld, 
                        uname_fld, 
                        CONCAT(fname_fld, ' ', lname_fld) AS fullname
                FROM user_tbl
                WHERE role_fld = 'Campus Supervisor'
                    AND (
                        fname_fld LIKE ? 
                        OR lname_fld LIKE ?
                    )
                ORDER BY fullname ASC";
        
        $stmt = $mysqli->prepare($sql);
        $searchTerm = '%' . $searchTerm . '%';
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'id' => $row['uname_id_fld'],
                'text' => $row['fullname']
            ];
        }

        echo json_encode($data);

        $stmt->close();
        $mysqli->close();
    }

}else{
    
    header("Location: index.php");
    exit;
            
        } 

?>
