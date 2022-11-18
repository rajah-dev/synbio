<?php
$activePage = basename($_SERVER['SCRIPT_NAME']); 
$currentPage = 'is-active';
?>

<!DOCTYPE html>
<html>
	<header>
		<nav class="navbar is-dark" role="navigation" aria-label="main navigation">
			<div class="navbar-brand is-size-3 has-text-weight-bold">
				<a class="navbar-item <?php echo ($activePage=='index.php')?$currentPage:'';?>" href="../index.php">synb.io</a>
			</div>
			<div id="basicMenu" class="navbar-menu">
				<div class="navbar-start">
					<a class="navbar-item <?php echo ($activePage=='project-search.php')?$currentPage:'';?>" href="../tools/project-search.php">project search</a>
					<a class="navbar-item <?php echo ($activePage=='part-search.php')?$currentPage:'';?>" href="../tools/part-search.php">part search</a>
					<a class="navbar-item <?php echo ($activePage=='twin-search.php')?$currentPage:'';?>" href="../tools/twin-search.php">twin search</a>
					<a class="navbar-item <?php echo ($activePage=='dashboard.php')?$currentPage:'';?>" href="../user/dashboard.php">dashboard</a>
    			</div>
    			<div class="navbar-end">
      				<div class="navbar-item">
        				<div class="buttons">
        					<?php if (!isset($_SESSION['loggedin']) || !$_SESSION["loggedin"]) : ?>
          					<a class="button is-primary" href="../user/register.php"><strong>Sign up</strong></a>
          					<a class="button is-light" href="../user/login.php">Log in</a>
          					<?php else: ?>
          					<a class="button is-light" href="../user/logout.php">Log out</a>
							<?php endif; ?>
						</div>
      				</div>
				</div>
			</div>
		</nav>
	</header>
</html>

