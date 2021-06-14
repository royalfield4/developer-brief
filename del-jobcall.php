<!DOCTYPE HTML>
<!--
	Fabio Camporeale's DEVELOPER BRIEF for H-Farm
	Design used and edited under the CCA 3.0 license
-->
<?php
	// Connessione al DB
	require_once "assets/varius/login_values.php";
	$dns = "mysql:host=$host;dbname=$db;charset=$charset";
	$opt = [
		PDO::ATTR_ERRMODE							=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES		=> false,
	];
	try {
		$pdo = new PDO($dns, $user, $pass, $opt);
	} catch (PDOException $e) {
		echo $e -> getMessage();
	}

	if (isset($_GET['delete'])) {
		if ($_GET['delete'] == 0) {
			echo "<script type='text/javascript'> document.location = 'job-call.php?id_jobcall=".$_GET['id_jobcall']."'; </script>";
		} else {
			// Gestione delle foreign key
			try	{
				$query = "DELETE FROM `jobcall` WHERE `jobcall`.`id_jobcall` = ".$_GET['id_jobcall'].";";
				$statement = $pdo -> prepare($query);
				$statement -> execute();
				echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
			} catch (PDOException $e) {
				echo "<script type='text/javascript'> document.location = 'job-call.php?id_jobcall=".$_GET['id_jobcall']."&err_delete=1'; </script>";
			}
		}
	} else {
		if (!isset($_GET['id_jobcall'])) {
			header("Location: calls.php");
		} elseif($_GET['id_jobcall'] == "") {
			header("Location: calls.php");
		} else {
			// Query di selezione dati
			$query = "SELECT title, type, date, client FROM jobcall WHERE id_jobcall = ".$_GET['id_jobcall'].";";
			$statement = $pdo -> prepare($query);
			$statement -> execute();
			$dim = $statement -> rowCount();

			if($dim==0)
				echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
			else
				$row = $statement -> fetch();
		}
	}
?>
<html lang="en">
	<head>
		<title>Mistral</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
		<div id="wrapper">

			<!-- Header -->
				<header id="header">
					<div class="inner">

						<!-- Logo -->
							<a href="calls.php" class="logo">
								<span class="symbol"><img src="images/logo.svg" alt="" /></span><span class="title">Mistral</span>
							</a>

						<!-- Nav -->
							<nav>
								<ul>
									<li><a href="#menu">Menu</a></li>
								</ul>
							</nav>

					</div>
				</header>

			<!-- Menu -->
				<nav id="menu">
					<h2>Menu</h2>
					<ul>
						<li><a href="calls.php">Calls</a></li>
						<li><a href="candidates.php">Candidates</a></li>
					</ul>
				</nav>

			<!-- Main -->
				<div id="main">
					<div class="inner">
						<h1>delete jobcall.</h1>

					<!-- Text -->
						<section>
							<h2>Job call informations</h2>
							<p>
								<b>Title</b> <?php echo $row['title']; ?><br/>
								<b>Type</b> <?php if (!strcmp($row['type'], "ex")) { echo "External"; } else { echo "Internal"; } ?><br/>
								<b>Client</b> <?php echo $row['client']; ?><br/>
								<b>Date</b> <?php echo $row['date']; ?><br/>
							</p>
							<h4>Do you what to proceed with the elimination of this job call?</h4>
							<ul class="actions">
								<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=1&id_jobcall=<?php echo $_GET['id_jobcall']; ?>" class="button primary">Yes, delete it</a></li>
								<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=0&id_jobcall=<?php echo $_GET['id_jobcall']; ?>" class="button primary">No, leave it</a></li>
							</ul>
						</section>

					</div>
				</div>

				<!-- Footer --><!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<ul class="copyright">
								<li>DEVELOPER BRIEF di Fabio Camporeale per <a href="https://www.h-farm.com/it">H-Farm</a></li>
								<li>Design used under the CCA 3.0 license</li>
							</ul>
						</div>
					</footer>

		</div>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/browser.min.js"></script>
		<script src="assets/js/breakpoints.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>

	</body>
</html>
