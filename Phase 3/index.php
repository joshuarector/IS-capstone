<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect user to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CD Investment System</title>  
    <!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Domine|Satisfy" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.2.0-dist/css/bootstrap.css" rel="stylesheet">
    <!--
    	<link href="login.css" rel="stylesheet">-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
  </head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="page-header">
                <h3>Hi, <b><?php echo htmlspecialchars($_SESSION["fullname"]); ?></b>. Welcome to the CD Investments Portfolio Management System.</h3>
            </div> 
                <div class="container">
        
                </div>
                <p> 
                <!-- Interest Calculator Button --> 
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"> Interest Calculator </button>
                <!-- Chat Session Pop Up     
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2"> Open A Chat Session
                    </button> -->
                <!-- Lines 0-50 written by Josh Rector -->    
                <!-- Create New User Button -->    
                    <a href="register.php" class="btn btn-primary">Create New User</a> 
                <!-- Password Reset Button -->
                    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                <!-- Log out Button -->    
                    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
                </p>
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left"><b>Transactions</b></h2>
                        <a href="create.php" class="btn btn-primary pull-right">Add New Transaction</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM transactions";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped table-hover'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Bank Name</th>";
                                        echo "<th>Amount</th>";
                                        echo "<th>Rate</th>";
                                        echo "<th>Start Date</th>";
                                        echo "<th>Maturity Date</th>";
                                        echo "<th>Term</th>";
                                        echo "<th>Client Name</th>";
                                        echo "<th>Broker Name</th>";
                                        echo "<th>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                // Lines written by Josh Rector
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['bank_name'] . "</td>";
                                        echo "<td>" . $row['amount'] . "</td>";
                                        echo "<td>" . $row['rate'] . "</td>";
                                        echo "<td>" . $row['startDate'] . "</td>";
                                        echo "<td>" . $row['maturityDate'] . "</td>";
                                        echo "<td>" . $row['term'] . "</td>";
                                        echo "<td>" . $row['client_name'] . "</td>";
                                        echo "<td>" . $row['broker_name'] . "</td>";     
                                        echo "<td>";
                                            echo "<a href='read.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><i class='glyphicon glyphicon-eye-open'></i></a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><i class='glyphicon glyphicon-pencil'></i></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><i class='glyphicon glyphicon-trash'></i></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <!--Lines written by Josh Rector -->
        <div class="modal-header">
          <h2 class="modal-title">Interest Calculator</h2>
          
        </div>
        
        <!-- Modal body -->
	
		<div id=cover class="col-xs-3">
			<form name=intt>
				<table class=main>
					<tr>
						
						<td>Principal Amount</td>
						<td><input id=principal></td>
					</tr>
					<tr>
					<!-- Lines written by Josh Rector -->	
						<td>Interest Rate</td>
						<td><input id=rate></td>
					</tr>
					<tr>
						
						<td>Interest Type</td>
						<td><select id=itype>
							<option value=s>Simple</option>
							<option value=c selected>Compound</option>
						</select>
						</td>
					</tr>
					<tr>
					
						<td>Compounding Frequency</td>
						<td><select id=crate>
							<option value=12>Monthly</option>
							<option value=4 selected>Quarterly</option>
							<option value=2>Half-yearly</option>
							<option value=1>Yearly</option>
						</select>
						</td>
					</tr>
					<tr>
						
						<td>Period (months)</td>
						<td><input id=time></td>
					</tr>
					<tr>
						
						<td><button type=reset>Reset</button></td>
						<td><button type=button onclick=interest()>Submit</button></td>
					</tr>
                    <!-- Lines written by Josh Rector -->
					<tr>
						
						<td>Interest Amount</td>
						<td><input id=inttamt></td>
					</tr>
				</table>
			</form>
		</div>
	
        <!-- Interest Calculator js Calculator-->
	<script>
		function interest(){
			var principal=document.getElementById('principal').value;
			var rate=document.getElementById('rate').value;
			var time=document.getElementById('time').value;
			var itype=document.getElementById('itype').value;
			var crate=document.getElementById('crate').value;
			var time=document.getElementById('time').value;
			var irate=rate/crate;
			var inttamt=document.getElementById('inttamt').value;
			var decimal=2;
			if( itype=='c' ){
				document.getElementById('inttamt').value=Math.round(
					(document.getElementById('principal').value * Math.pow(
						(1+irate/100),(time/12*crate))*1-principal*1 ) *100)/100;
			}
			if(itype=='s' ){
				document.getElementById('inttamt').value=Math.round
				(((document.getElementById('principal').value * rate * time/12)/100)*100)/100;
			}
		}
	</script>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
</div>


        <div class="container"><br>
        <!-- Button to Open the Modal -->    
        
        </div>
        <!-- Lines written by Josh Rector -->
  <!-- The Modal -->
  <div class="modal fade" id="myModal2">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h2 class="modal-title">Chat Session</h2>
          
        </div>
        
        <!-- Modal body -->
    
        <div id=cover class="col-xs-3">
            
        </div>
    
        <!-- Javascript-->

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        
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
<!-- Footer -->
</html>
<!-- Lines Written by Josh Rector -->
