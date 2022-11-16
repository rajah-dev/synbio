<?php
require_once '../includes/common.php';
session_start();

$username = $password = "";
$username_err = $password_err = $dbLogin_err = "";

if (isset($_POST["login"])) {

	// check if username and password were empty
	if (empty($_POST["username"])) {
		$username_err = "Please enter your username.";
	} else {
		$username = $_POST["username"];		
	}

	if (empty($_POST["password"])) {
		$password_err = "Please enter your password.";
	} else {
		$password = $_POST["password"];
	}


	// if no empty errors
	if(empty($username_err) && empty($password_err)) {
	$loginUserSQL = "SELECT id, username, password FROM users WHERE username = :username";


		if ($loginUserSTMT = $dbconn->prepare($loginUserSQL)) {

			if ($loginUserSTMT->execute(['username' => $username])) {

				if ($loginUserSTMT->rowCount() == 1) { // if username found

					if ($row = $loginUserSTMT->fetch(PDO::FETCH_ASSOC)) {

						$id = $row['id'];
						$username = $row['username'];
						$hashedPassword = $row['password'];

						echo $id . $username . $hashedPassword;

						if (password_verify($password, $hashedPassword)) { // password is correct, start session

							session_start();
							$_SESSION["loggedin"] = TRUE;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;

							header("location: dashboard.php");

						} else { // if password is incorrect
							$password_err = "Whoops, seems like you've entered the wrong password.";
						}

					} else { // if row cannot be fetched

					}

				} else { // if username not found
					$username_err = "Sorry, we do not have a record of this username.";
				}

			} else { // if execute STMT fails
				$dbLogin_err = "Sorry, there's been an issue connecting to the database.";
			}
		
		} else { // if prepare STMT fails
			$dbLogin_err = "Sorry, there's been an issue connecting to the database.";
		}

	} //ends empty-check for username and password

}

?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>synb.io</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
	    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	</head>
	<?php require_once '../includes/header.php'; ?>
	<body>
		<div class="section">
			<div class="container">
				<div class="columns">
					<div class="column is-one-third">
						<h1 class="title">Login</h1>
						<h2 class="subtitle">but why?</h2>
						<p>This does nothing. Currently for the purposes of testing a rudimentary login system.</p></br>
						<form method="POST">
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
								<div class="control">
								<button class="button is-info is-fullwidth" name="login" type="submit">
									Login
								</button>
								</div>
								<p class="help is-danger"><?php echo $dbLogin_err; ?></p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>