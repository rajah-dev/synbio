<?php
require_once '../includes/common.php';
session_start();

if ($_SESSION["loggedin"]) {
	header("location: dashboard.php");
	exit;
}

$tableName = "parts";

$username = $firstName = $lastName = $email = $password = $confirmPassword = "";
$username_err = $fullName_err = $email_err = $password_err = $confirmPassword_err = $dbRegistration_err = "";


if (isset($_POST["register"])) { // server side validation, but will want to add some client-side validation

	if(empty($_POST["fname"]) OR empty($_POST["lname"])) {
		$fullName_err = "Please enter your full name: first and last.";
	} else {
		$firstName = $_POST["fname"];
		$lastName =  $_POST["lname"];
	}

	if(empty($_POST["email"])) {
		$email_err = "Please enter your email.";
	} else { // may want to add additional checks: unique email / @ symbol / etc
		$email = $_POST["email"];
	}

	if(empty($_POST["username"])) {
		$username_err = "Please enter your username.";
	} else {
		$checkUserSQL = "SELECT id FROM users WHERE username = :username"; 
		if ($checkUserStatement = $dbconn->prepare($checkUserSQL)) {
			if ($checkUserStatement->execute(['username' => $_POST["username"]])) {
				if($checkUserStatement->rowCount() == 1) { // check if username already exists
					$username_err = "Sorry, this username is taken. Please try another.";
				} else { // if it doesn't already exist, it's fine
					$username = $_POST["username"];
				}
			} else { // if execute fails
				$dbRegistration_err = "Sorry, there's been an issue connecting to the database.";
			}
		} else { // if prepare fails
			$dbRegistration_err = "Sorry, there's been an issue connecting to the database.";
		}
	}

	if(strlen($_POST["password"]) < 6) {
		$password_err = "Please enter a password (6 characters or longer).";
	} else {
		$password = $_POST["password"];
	}

	if(strlen($_POST["confirm_password"]) < 6) {
		$confirmPassword_err = "Please confirm your password.";
	} elseif ($password != $_POST["confirm_password"]) {
		$confirmPassword_err = "Password did not match.";
	} 

	if (empty($username_err) && 
		empty($fullName_err) && 
		empty($email_err) && 
		empty($password_err) && 
		empty($confirmPassword_err) && 
		empty($dbRegistration_err)) {

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$insertUserSQL = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (:username, :email, :password, :firstName, :lastName)";

		if ($insertUserSTMT = $dbconn->prepare($insertUserSQL)) {
			$insertUserSTMT->execute(['username' => $username, 
											'email' => $email, 
											'password' => $hashedPassword, 
											'firstName' => $firstName,
											'lastName' => $lastName]);
			header("location: success.php");
		}

	}

}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>register</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
	    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	</head>
	<?php require_once '../includes/header.php'; ?>
	<body>
		<div class="section">
			<div class="container">
				<div class="columns">
					<div class="column is-one-third">
						<h1 class="title">Register</h1>
						<h2 class="subtitle">but why?</h2>
						<p>Registering doesn't really do anything, but it does allow access to the dashboard. 
							The dashboard does nothing though.</p> 
						<p>Currently for the purposes of testing a rudimentary login system.</p></br>
						<form method="POST">
							<div class="field">
								<label class="label">Name</label>
							</div>
							<div class="field">
								<div class="field is-grouped">
								<div class="control is-expanded">
									<input class="input" type="text" name="fname" placeholder="First Name" value="<?php echo $firstName; ?>">
								</div>
								<div class="control is-expanded">
									<input class="input" type="text" name="lname" placeholder="Last Name" value="<?php echo $lastName; ?>">
								</div>
							</div>
								<p class="help is-danger"><?php echo $fullName_err; ?></p>
							</div>
							<div class="field">
								<label class="label">Email</label>
								<div class="control has-icons-left">
									<input class="input" type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
									<span class="icon is-small is-left">
										<i class="fas fa-envelope"></i>
									</span>
								</div>
								<p class="help is-danger"><?php echo $email_err; ?></p>							
							</div>
							<div class="field">
								<label class="label">Username</label>
								<div class="control">
									<input class="input" type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
								</div>
								<p class="help is-danger"><?php echo $username_err; ?></p>								
							</div>
							<div class="field">
								<label class="label">Password</label>
								<div class="control has-icons-left">
									<input class="input" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
									<span class="icon is-small is-left">
										<i class="fas fa-lock"></i>
									</span>
								</div>
								<p class="help is-danger"><?php echo $password_err; ?></p>
							</div>
							<div class="field">
								<label class="label">Confirm Password</label>
								<div class="control has-icons-left">
									<input class="input" type="password" name="confirm_password" placeholder="Password" value="<?php echo $confirmPassword; ?>">
									<span class="icon is-small is-left">
										<i class="fas fa-lock"></i>
									</span>
								</div>
								<p class="help is-danger"><?php echo $confirmPassword_err; ?></p>
							</div>
							<div class="field">
								<div class="control">
								<button class="button is-info is-fullwidth" name="register" type="submit">
									Register
								</button>
								</div>
								<p class="help is-danger"><?php echo $dbRegistration_err; ?></p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>