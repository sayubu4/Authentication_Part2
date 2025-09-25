// Settings/core.php
<?php
session_start();


//for header redirection
ob_start();

//funtion to check for login
//if (!isset($_SESSION['id'])) {
 //   header("Location: ../login/login.php");
 //   exit;
//}
//funtion to check for login

function isLoggedIn(){
    if (!isset($_SESSION['user_id'])){
        return false;
}  
else{
    return true;
} 

}

//function to check for role (admin, customer, etc)

function isAdmin(){
    if (isLoggedIn()) {
        return $_SESSION['user_role'] == 2;
    }
}

?>