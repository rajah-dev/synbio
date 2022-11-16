<?php
require_once '../includes/common.php';
session_start();

if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
	header("location: login.php");
	exit;
}
							


?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>user dashboard</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
	    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	</head>
	<?php require_once '../includes/header.php'; ?>
	<body>
		<div class="section">
			<div class="container">
				<div class="columns">
					<div class="column is-one-third">
						<h1 class="title">Dashboard</h1>
						<h2 class="subtitle">but why?</h2>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>