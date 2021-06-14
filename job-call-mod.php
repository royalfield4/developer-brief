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
	// Controlli sul valore del parametro passato tramite metodo GET
	if (!isset($_POST['submit'])) {
		if (!isset($_GET['id_jobcall'])) {
			echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
		} elseif($_GET['id_jobcall'] == "") {
			echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
		} else {
			// Query di caricamento dai dati assocciati alla jobcall
			$query = "SELECT title, type, date, client FROM jobcall WHERE id_jobcall = ".$_GET['id_jobcall'].";";
			$statement = $pdo -> prepare($query);
			$statement -> execute();
			$dim = $statement -> rowCount();

			if($dim==0)
				echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
			else
				$row = $statement -> fetch();
		}
	} else {
		// Query di aggiornamento dati
		$title = $_POST['title'];
		$client = $_POST['client'];
		$type = $_POST['type'];
		$date = $_POST['date'];
		$query = "UPDATE jobcall SET title = '".$title."', type = '".$type."', date = '".$date."', client = '".$client."' WHERE jobcall.id_jobcall = ".$_GET['id_jobcall'].";";
		$statement = $pdo -> prepare($query);
		$statement -> execute();

		echo "<script type='text/javascript'> document.location = 'job-call.php?id_jobcall=".$_GET['id_jobcall']."'; </script>";
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
					<h1>do changes.</h1>


					<!-- Form -->
					<section>
						<h2>UPDATE THE JOB CALL INFORMATIONS</h2>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?id_jobcall=<?php echo $_GET['id_jobcall']; ?>">
							<div class="row gtr-uniform">
								<div class="col-6 col-12-xsmall">
									<input type="text" name="title" id="title" value="<?php echo $row['title'];?>" placeholder="Title" required/>
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="text" name="client" id="client" value="<?php echo $row['client'];?>" placeholder="Insert client *" />
								</div>
								<div class="col-12">
									<select name="type" id="demo-category" required>
										<option value=""  >- Type -</option>
										<option value="in" <?php if ($row['type'] == "in") echo "selected";?>>Internal</option>
										<option value="ex" <?php if ($row['type'] == "ex") echo "selected";?>>External</option>
									</select>
								</div>
								<div class="col-6 col-12-xsmall">
									Insert date <input type="date" name="date" id="date" value="<?php echo $row['date'];?>" placeholder="Date" required/>
								</div>
								<div class="col-12">
									<ul class="actions">
										<li><input type="submit" name="submit" value="Update job call" class="primary" /></li>
									</ul>
								</div>
							</div>
						</form>
					</section>

				</div>
			</div>

			<!-- Footer -->
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
