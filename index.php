<?php
$configfile = 'config.php';
if (!file_exists($configfile)) {
    echo '<meta http-equiv="refresh" content="0; url=install" />';
    exit();
}

include "config.php";

if(!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['sec-username'])) {
    $uname = $_SESSION['sec-username'];
    if ($uname == $settings['username']) {
        echo '<meta http-equiv="refresh" content="0; url=dashboard.php" />';
        exit;
    }
}

$_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$error = 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="ARJUNA">
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<meta name="theme-color" content="#000000">
        <title>RAKSHA - Welcome Page</title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
		<link href="assets/css/admin.min.css" rel="stylesheet">

        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.png">
        <style>
            h1 { font-family: Arial; }
            p { font-family: Verdana; }
            
        </style>
    </head>

    <body class="login-page <?php
if ($settings['dark_mode'] == 1) {
    echo 'dark-mode';
}
?>">
        
        <img src="/s/assets/img/nobglogo.png"></img>
	<div class="login-box">
	    

		<div class="card card-outline card-primary">
		<div class="card-header text-center">
			<h3>Welcome!</h3>
			<p align="center">This Website Directory Contains the Final Year Project of VV College Student Arjun.k of BCA3. The Name of the Project is RAKSHA. It is a Web-suite of Security Tools Which can be used in PHP Powered Websites to Protect themselves from Malicious Traffic. RAKSHA is Lighweight, Modular and Portable in Nature, Thus an Optimal Solution for Intrusion Detection & Prevention for Small and Medium Websites.</p>
		</div>
		<a href="https://zenter.in/login.php"></a>
		<button type="submit" name="signin" onclick="window.location.href = 'https://zenter.in/s/login.php';" class="btn btn-md btn-primary btn-block btn-flat">
Continue&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button></a><br>
                    <center><p>
                        <a href="https://zenter.in/d/RAKSHA_Abstract.pdf">Download Abstract Paper</a>
                    </p>
                    </center>
			</div>
        </form> 
		
		</div>
    </body>
</html>