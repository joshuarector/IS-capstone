<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect user to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $fullname = "";
$username_err = $password_err = $fullname_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, fullname FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                // Lines 0-50 written by Josh Rector
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $fullname);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fullname"] = $fullname;                           
                            
                            // Redirect user to index page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?><!DOCTYPE html>
<html lang="en">	
<head>
  <title>Login</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Domine|Satisfy" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.css" rel="stylesheet">
    <!-- Lines 50-100 writtten by Josh Rector -->
    <link href="login.css" rel="stylesheet">
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>

<body>

  <!-- Login form -->
    <div class="imgcontainer">
    <img src="images/jumbo_logo.jpg" alt="Logo" class="Logo">
    <h3>Jumbo CD Investments</h2>
  </div>
  <div class="container">
            <h1 style="text-align:center;">Login</h1>
        <p style="text-align:center;">Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter Username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter Password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
              <!-- Login Button -->
                <input type="submit" class="btn btn-primary"  value="Login"> 
            </div>        
    </div>
       </form>
  </div>    
  <!--Contact Us Button Popup Window-->
        <div class="container">
        <!-- Button to Open the Modal -->    
        <button type="btn btn-primary" data-toggle="modal" data-target="#myModal3">
            Contact Us
            </button>  
        </div>
  <!-- The Modal -->
  <div class="modal fade" id="myModal3">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <!--Lines written by Josh Rector -->
        <div class="modal-header">
          <h3 class="modal-title">Contact Information</h3>
        </div> 
        <!-- Modal body -->  
        <div id=cover class="col-xs-3">
          <table class="main">
            <tr>
              <td><b>Phone: </b></td>
              <td>530-304-5990</td>
            </tr>
            <tr>
              <td><b>Email: </b></td>
              <td>jrector1@liberty.edu</td>
            </tr>    
          </table>
    </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
      <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
</body>	
<!-- Footer -->
<footer class="page-footer font-small blue">
  <!-- Copyright -->
  <div class="footer-copyright text-center py-3">© 2019 Copyright: Joshua Rector - Liberty University
  </div>
  <!-- Copyright -->
</footer>
<!--End of Footer -->
</html>
<!-- Lines 152-191 written by Josh Rector -->