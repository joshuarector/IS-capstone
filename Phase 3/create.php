<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect user to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$id = $bankName = $amount = $rate = $startDate = $maturityDate = $term = $clientName = $brokerName = "";
$bankName_err = $amount_err = $rate_err = $startDate_err = $maturityDate_err = $term_err = $clientName_err = $brokerName_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate Bank name
    $input_bankName = trim($_POST["bankName"]);
    if(empty($input_bankName)){
        $bankName_err = "Please enter a Bank name.";
    } elseif(!filter_var($input_bankName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $bankName_err = "Please enter a valid Bank name.";
    } else{
        $bankName = $input_bankName;
    }
    
    // Validate amount
    $input_amount = trim($_POST["amount"]);
    if(empty($input_amount)){
        $amount_err = "Please enter an amount.";     
    } else{
        $amount = $input_amount;
    }
    
    // Validate rate
    $input_rate = trim($_POST["rate"]);
    if(empty($input_rate)){
        $rate_err = "Please enter the rate.";     
    } else{
        $rate = $input_rate;
    }
    
    // Validate start date
    // Lines 0-49 written by Josh Rector
    $input_startDate = trim($_POST["startDate"]);
    if(empty($input_startDate)){
        $startDate_err = "Please enter the start date.";     
    } else{
        $startDate = $input_startDate;
    }

    // Validate maturity date
    $input_maturityDate = trim($_POST["maturityDate"]);
    if(empty($input_maturityDate)){
        $maturityDate_err = "Please enter the maturity date.";     
    } else{
        $maturityDate = $input_maturityDate;
    }

    // Validate term
    $input_term = trim($_POST["term"]);
    if(empty($input_term)){
        $term_err = "Please enter the term in days.";     
    } elseif(!ctype_digit($input_term)){
        $term_err = "Please enter a positive integer value.";
    }else{
        $term = $input_term;
    }

    // Validate Client name
    $input_clientName = trim($_POST["clientName"]);
    if(empty($input_clientName)){
        $clientName_err = "Please enter a client name.";
    } elseif(!filter_var($input_clientName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $clientName_err = "Please enter a valid Client name.";
    } else{
        $clientName = $input_clientName;
    }

    // Validate Broker name
    $input_brokerName = trim($_POST["brokerName"]);
    if(empty($input_brokerName)){
        $brokerName_err = "Please enter a Broker name.";
    } elseif(!filter_var($input_brokerName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $brokerName_err = "Please enter a valid Broker name.";
    } else{
        $brokerName = $input_brokerName;
    }

    // Check input errors before inserting in database
    if(empty($bankName_err) && empty($amount_err) && empty($rate_err) && empty($startDate_err) && empty($maturityDate_err) && empty($term_err) && empty($clientName_err) && empty($brokerName_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO transactions (bank_name, amount, rate, startDate, maturityDate, term, client_name, broker_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // Lines 50-99 written by Josh Rector 
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_bankname, $param_amount, $param_rate, $param_startdate, $param_maturitydate, $param_term, $param_clientname, $param_brokername);
            
            // Set parameters
            
            $param_bankname = $bankName;
            $param_amount = $amount;
            $param_rate = $rate;
            $param_startdate = $startDate;
            $param_maturitydate = $maturityDate;
            $param_term = $term;
            $param_clientname = $clientName;
            $param_brokername = $brokerName;
            
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
    <title>Create Transaction</title>
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
                        <h2>Create Transaction</h2>
                    </div>
                    <p>Please fill this form and submit to add a transaction to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Bank Name</label>
                            <input type="text" name="bankName" class="form-control" value="<?php echo $bankName; ?>">
                            <span class="help-block"><?php echo $bankName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($amount_err)) ? 'has-error' : ''; ?>">
                            <label>Amount</label>
                            <input type="text" name="amount" class="form-control" value="<?php echo $amount; ?>">
                            <span class="help-block"><?php echo $amount_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($rate_err)) ? 'has-error' : ''; ?>">
                            <label>Rate</label>
                            <input type="text" name="rate" class="form-control" value="<?php echo $rate; ?>">
                            <span class="help-block"><?php echo $rate_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($startDate_err)) ? 'has-error' : ''; ?>">
                            <label>Start Date</label>
                            <input type="text" name="startDate" class="form-control" value="<?php echo $startDate; ?>">
                            <span class="help-block"><?php echo $startDate_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($maturityDate_err)) ? 'has-error' : ''; ?>">
                            <label>Maturity Date</label>
                            <input type="text" name="maturityDate" class="form-control" value="<?php echo $maturityDate; ?>">
                            <span class="help-block"><?php echo $maturityDate_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($term_err)) ? 'has-error' : ''; ?>">
                            <label>Term</label>
                            <input type="text" name="term" class="form-control" value="<?php echo $term; ?>">
                            <span class="help-block"><?php echo $term_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($clientName_err)) ? 'has-error' : ''; ?>">
                            <label>Client Name</label>
                            <input type="text" name="clientName" class="form-control" value="<?php echo $clientName; ?>">
                            <span class="help-block"><?php echo $clientName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($brokerName_err)) ? 'has-error' : ''; ?>">
                            <label>Broker Name</label>
                            <input type="text" name="brokerName" class="form-control" value="<?php echo $brokerName; ?>">
                            <span class="help-block"><?php echo $brokerName_err;?></span>
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
<!-- Lines 100-206 written by Josh Rector -->