<?php

// Begins session for user
    session_start();

// Checks if the user is logged in
    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/database.php";

        $sql = "SELECT * FROM user_tbl
                WHERE uname_id_fld = {$_SESSION["user_id"]};";

        $result = $mysqli->query($sql);
        
        $user = $result->fetch_assoc();
    }

    if (isset($user) && ($user["role_fld"]) == "Administrator") {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uname = $_POST['uname_fld'];
            $fname = $_POST['fname_fld'];
            $lname = $_POST['lname_fld'];
            $email = $_POST['email_fld'];
            // $password_hash = password_hash($_POST["password_lbl"], PASSWORD_DEFAULT);

            // $stmt = $mysqli->prepare("UPDATE user_tbl SET 
            //                                     fname_fld = ?, 
            //                                     lname_fld = ?, 
            //                                     email_fld = ?,
            //                                     password_hash_fld = ? 
            //                             WHERE uname_fld = ?"
            //                         );
            // $stmt->bind_param("ssss", $fname, $lname, $email, $uname, $password_hash);
            
            $stmt = $mysqli->prepare("UPDATE user_tbl SET 
                                                fname_fld = ?, 
                                                lname_fld = ?, 
                                                email_fld = ?
                                        WHERE uname_fld = ?"
                                    );
            $stmt->bind_param("ssss", $fname, $lname, $email, $uname);
            $stmt->execute();

            echo "User updated successfully!";
        }

    }else{
    
        header("Location: index.php");
        exit;
                
            } 
?>
