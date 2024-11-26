<?php 

session_start();

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

    // Checks if the request is via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieves the posted username from the AJAX request
        $username = trim($_POST['username']);

        // Prepares the SQL query
        $stmt = $mysqli->prepare("DELETE FROM queue_rules_tbl WHERE queue_id_fld = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        // Returns the result as a JSON response
        echo json_encode(['success' => true, 'message' => 'Deleted user successfully']);

        $stmt->close();
        $mysqli->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }



}else{
    
    header("Location: index.php");
    exit;
            
        } 
?>
