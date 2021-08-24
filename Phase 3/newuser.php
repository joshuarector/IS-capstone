<?php
// Always start this first
session_start();

if ( isset( $_SESSION['user_id'] ) ) {
    // Grab user data from the database using the username
   
} else {
    // Redirect them to the login page
    header("login.php");
}
?>

<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$id = $username = $password = $fullname = $usertype = "";
$username_err = $password_err = $fullname_err = $usertype_err = "";
$min = 1; $max = 2;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate username
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Please enter a username.";
    } elseif(!filter_var($input_username, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $username_err = "Please enter a valid username. Must only contain letters";
    } else{
        $username = $input_username;
    }
    
    // Validate password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $password_err = "Please enter a password.";     
    } else{
        $password = $input_password;
    }
    
    // Validate fullname
    $input_fullname = trim($_POST["fullname"]);
    if(empty($input_fullname)){
        $fullname_err = "Please enter the full name.";     
    } else{
        $fullname = $input_fullname;
    }
    
    // Validate usertype
    $input_usertype = trim($_POST["usertype"]);
    if(empty($input_usertype)){
        $usertype_err = "Please enter the user type.";     
    } elseif(!filter_var($input_usertype, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) === false){
        $usertype_err = "Please enter a valid usertype. Must enter 1 for broker of 2 for client";
    }
    else{
        $usertype = $input_usertype;
    }


    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($fullname_err) && empty($usertype_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, fullname, usertype) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_fullname, $param_usertype);
            
            // Set parameters
            
            $param_username = $username;
            $param_password = $password;
            $param_fullname = $fullname;
            $param_usertype = $usertype;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create New User</h2>
                    </div>
                    <p>Please fill this form and submit to add a new user to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>User Name</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($fullname_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control" value="<?php echo $fullname; ?>">
                            <span class="help-block"><?php echo $fullname_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($usertype_err)) ? 'has-error' : ''; ?>">
                            <label>User Type</label><p>Enter 1 for Broker. Enter 2 for Client</p>
                            <input type="text" name="usertype" class="form-control" value="<?php echo $usertype; ?>">
                            <span class="help-block"><?php echo $usertype_err;?></span>
                        </div>                                                
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>