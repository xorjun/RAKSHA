<?php
require "core.php";
head();

if (isset($_GET['delete-id'])) {
    $id    = (int) $_GET["delete-id"];

    $query = $mysqli->query("DELETE FROM `psec_bans-other` WHERE id='$id'");
}
?>
<div class="content-wrapper">

			<!--CONTENT CONTAINER-->
			<!--===================================================-->
			<div class="content-header">
				
				<div class="container-fluid">
				  <div class="row mb-2">
        		    <div class="col-sm-6">
        		      <h1 class="m-0 "><i class="fas fa-desktop"></i> Other Bans</h1>
        		    </div>
        		    <div class="col-sm-6">
        		      <ol class="breadcrumb float-sm-right">
        		        <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i> Admin Panel</a></li>
        		        <li class="breadcrumb-item active">Other Bans</li>
        		      </ol>
        		    </div>
        		  </div>
    			</div>
            </div>

				<!--Page content-->
				<!--===================================================-->
				<div class="content">
				<div class="container-fluid">
                    
<?php
if (isset($_POST['block'])) {

    $value = addslashes($_POST['value']);
    $type  = $_POST['type'];
    
    $queryvalid = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE value='$value' and type='$type' LIMIT 1");
    $validator  = mysqli_num_rows($queryvalid);
    if ($validator > "0") {
        echo '<br />
		<div class="callout callout-info">
                <p><i class="fas fa-info-circle"></i> There is already such record in the database.</p>
        </div>';
    } else {
        $query = $mysqli->query("INSERT INTO `psec_bans-other` (value, type) VALUES('$value', '$type')");
    }
}
?>
                    
                <div class="row">
                   
				<div class="col-md-6">
				     <div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title">Ban Browser, OS, ISP or Referrer</h3>
						</div>
				        <div class="card-body">
						<form class="form-horizontal" action="" method="post">
										<div class="form-group">
											<label class="control-label">Browser, OS, ISP or Referrer: </label>
											<input name="value" class="form-control" type="text" required>
										</div>
                                        <div class="form-group">
											<label class="control-label">Type: </label>
	<select name="type" class="form-control" required>
        <option value="browser" selected>Browser</option>
        <option value="os">Operating System</option>
        <option value="isp">Internet Service Provider</option>
		<option value="referrer">Referrer</option>
    </select>
										</div>
                        </div>
                        <div class="card-footer">
							<button class="btn btn-flat btn-block btn-danger" name="block" type="submit">Ban</button>
				        </div>
				     </div>
				</div>
</form>
                    
                    <div class="col-md-6">
				     <div class="card">
						<div class="card-header">
							<h3 class="card-title">Blocked <strong>Internet Service Providers</strong></h3>
						</div>
				        <div class="card-body">
<table id="dt-basic3" class="table table-bordered table-hover table-sm">
									<thead class="<?php echo $thead; ?>">
										<tr>
						                  <th><i class="fas fa-cloud"></i> ISP</th>
										  <th><i class="fas fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='isp'");
while ($row = $query->fetch_assoc()) {
    echo '
										<tr>
						                    <td>' . $row['value'] . '</td>
											<td>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-success btn-block btn-sm"><i class="fas fa-unlock"></i> Unblock</a>
											</td>
										</tr>
';
}
?>
									</tbody>
								</table>	
                        </div>
				     </div>
				</div>
                </div>
                    
                <div class="row">
				<div class="col-md-6">
				    <div class="card">
						<div class="card-header">
							<h3 class="card-title">Blocked <strong>Browsers</strong></h3>
						</div>
						<div class="card-body">
<table id="dt-basicphpconf" class="table table-bordered table-hover table-sm">
									<thead class="<?php echo $thead; ?>">
										<tr>
						                  <th><i class="fas fa-globe"></i> Browser</th>
										  <th><i class="fas fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='browser'");
while ($row = $query->fetch_assoc()) {
    echo '
										<tr>
						                    <td>' . $row['value'] . '</td>
											<td>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-success btn-block btn-sm"><i class="fas fa-unlock"></i> Unblock</a>
											</td>
										</tr>
';
}
?>
									</tbody>
								</table>
                        </div>
                     </div>
                </div>
                    
				<div class="col-md-6">
				     <div class="card">
						<div class="card-header">
							<h3 class="card-title">Blocked <strong>Operating Systems</strong></h3>
						</div>
				        <div class="card-body">
<table id="dt-basic2" class="table table-bordered table-hover table-sm">
									<thead class="<?php echo $thead; ?>">
										<tr>
						                  <th><i class="fas fa-desktop"></i> Operating System</th>
										  <th><i class="fas fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='os'");
while ($row = $query->fetch_assoc()) {
    echo '
										<tr>
						                    <td>' . $row['value'] . '</td>
											<td>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-success btn-block btn-sm"><i class="fas fa-unlock"></i> Unblock</a>
											</td>
										</tr>
';
}
?>
									</tbody>
								</table>	
                        </div>
				     </div>
				</div>
				
				<div class="col-md-6">
				     <div class="card">
						<div class="card-header">
							<h3 class="card-title">Blocked <strong>Referrers</strong></h3>
						</div>
				        <div class="card-body">
<table id="dt-basic4" class="table table-bordered table-hover table-sm">
									<thead class="<?php echo $thead; ?>">
										<tr>
						                  <th><i class="fas fa-link"></i> Referrer</th>
										  <th><i class="fas fa-cog"></i> Actions</th>
										</tr>
									</thead>
									<tbody>
<?php
$query = $mysqli->query("SELECT * FROM `psec_bans-other` WHERE type='referrer'");
while ($row = $query->fetch_assoc()) {
    echo '
										<tr>
						                    <td>' . $row['value'] . '</td>
											<td>
                                            <a href="?delete-id=' . $row['id'] . '" class="btn btn-flat btn-success btn-block btn-sm"><i class="fas fa-unlock"></i> Unblock</a>
											</td>
										</tr>
';
}
?>
									</tbody>
								</table>	
                        </div>
				     </div>
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


This script is a PHP script that is intended to be used as part of an administrative interface for 
managing a website's bans. It appears that the script is responsible for banning browsers, operating systems, 
internet service providers, and referrers. It uses the $_GET superglobal to check if the user is trying to 
delete a specific ban by ID. If so, it retrieves the ID from the GET request, and then uses a MySQLi query to 
delete the corresponding ban from the 'psec_bans-other' table.

The script also uses the $_POST superglobal to handle form submissions for adding new bans. 
When a user submits the form, the script will check if the value and type provided already exists in the database. 
If it does, it will show a message saying that there is already a record in the database. 
If the value and type do not already exist in the database, it will insert a new ban into the 'psec_bans-other' 
table with the value and type provided by the user.

It also appears that the script makes use of a 'core.php' file, which is not provided in the code snippet, 
but that it may contain the code that sets up the connection to the MySQL server and the database.

It has few additional checks which are good to have like addingslashes, htmlspecialchars, (int) casting, 
empty checks, num_rows checks which are good to have while taking input and performing database operations