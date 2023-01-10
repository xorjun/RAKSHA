<?php
require "core.php";
head();

$sec_username = $_SESSION['sec-username'];
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div class="content-header">
				
				<div class="container-fluid">
				  <div class="row mb-2">
        		    <div class="col-sm-6">
        		      <h1 class="m-0 "><i class="fas fa-user"></i> ACCOUNT</h1>
        		    </div>
        		    <div class="col-sm-6">
        		      <ol class="breadcrumb float-sm-right">
        		        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i> Admin Panel</a></li>
        		        <li class="breadcrumb-item active">ACCOUNT</li>
        		      </ol>
        		    </div>
        		  </div>
    			</div>
            </div>

				<!--Page content-->
				<!--===================================================-->
				<div class="content">
				<div class="container-fluid">
                    
                <div class="row">                  
                
				<div class="col-md-12">

<form class="form-horizontal" action="" method="post">
                    <div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title">Account</h3>
						</div>
				        <div class="card-body">
                               <div class="form-group">
											<label class="control-label"><i class="fas fa-user"></i> Admin Username: </label>
											<input type="text" name="username" class="form-control" value="<?php
echo $settings['username'];
?>" required>
										</div>
                                        <hr />
                                        <div class="form-group">
											<label class="control-label"><i class="fas fa-key"></i> New Password: </label>
											<input type="text" name="password" class="form-control">
										</div>
                                        <i>Type a New Password to Change the Old One.</i>
                        </div>
                        <div class="card-footer row">
							<div class="col-md-8">
								<button class="btn btn-block btn-flat btn-success" name="edit" type="submit"><i class="fas fa-save"></i> Save</button>
							</div>
							<div class="col-md-4">
								<button type="reset" class="btn btn-block btn-flat btn-default"><i class="fas fa-undo"></i> Reset</button>
							</div>
						</div>
				     </div>
</form>
<?php
if (isset($_POST['edit'])) {
    $username = addslashes($_POST['username']);
    $password = $_POST['password'];

	$settings['username'] = $username;
    $_SESSION['sec-username'] = $username;
	
    if ($password != null) {
        $password             = hash('sha256', $_POST['password']);
		
        $settings['password'] = $password;
    }
	
    file_put_contents('config_settings.php', '<?php $settings = ' . var_export($settings, true) . '; ?>');
	echo '<meta http-equiv="refresh" content="0;url=account.php">';
}
?>
                </div>

				</div>
                    
				</div>
				</div>
				<!--===================================================-->
				<!--End page content-->

			</div>
			<!--===================================================-->
			<!--END CONTENT CONTAINER-->
</div>
<?php
footer();
?>


This script is for a PHP web application that allows a user to edit their account information, 
specifically their username and password. It starts by requiring a file named "core.php" 
which I assume contains some necessary functions and configurations for the rest of the script to work.
 The script then calls the "head()" function which is also likely defined in the included file.

It then retrieves the current username from the session variable 'sec-username' and stores it in a 
variable called $sec_username. Next, it has some HTML code that creates a form for editing the user's account 
information. The form has two input fields, one for the username and one for the password, and both are 
pre-populated with the user's current information.

When the form is submitted, the script checks for the presence of the "edit" variable in the $_POST array, 
which is only set when the form is submitted. If the variable exists, it retrieves the new username and password 
from the $_POST array and uses them to update the user's information. It also uses hash function to hash the 
password for security purposes.

The script uses various functions like addslashes() and hash() to secure the input data, 
although it's not enough alone. You also have to use more secure functions such as password_hash() 
and check with password_verify() while checking login. Moreover, it also updates the session variable 
'sec-username' with the new username.

Finally, the script writes the updated user information to a file named "config_settings.php". 
It's using the file_put_contents() function to write the updated information back to the file.