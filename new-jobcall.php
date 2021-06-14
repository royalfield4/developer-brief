<!DOCTYPE HTML>
<!--
	Fabio Camporeale's DEVELOPER BRIEF for H-Farm
	Design used and edited under the CCA 3.0 license
-->
<?php
	if (isset($_POST['submit'])) {
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

		// Query di inserimento dati
		$title = $_POST['title'];
		$type = $_POST['type'];
		$date = $_POST['date'];
		$client = $_POST['client'];
		$query = "INSERT INTO `jobcall` (`id_jobcall`, `title`, `type`, `date`, `creationtime`, `client`)
							VALUES (NULL, '".$title."', '".$type."', '".$date."', current_timestamp(), '".$client."');
						 ;";

		$statement = $pdo -> prepare($query);
		$statement -> execute();

		echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
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
					<h1>create a new jobcall.</h1>

					<!-- Form -->
					<section>
						<h2>INSERT THE JOB CALL INFORMATIONS</h2>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
							<div class="row gtr-uniform">



								<div class="col-6 col-12-xsmall">
									<input type="text" name="title" id="title" value="<?php echo $_POST['job-name'];?>" placeholder="Title" required/>
								</div>

								<div class="col-6 col-12-xsmall">
									<input type="text" name="client" id="client" value="" placeholder="Insert client *" />
								</div>

								<div class="col-12">
									<select name="type" id="demo-category" required>
										<option value="">- Type -</option>
										<option value="in">Internal</option>
										<option value="ex">External</option>
									</select>
								</div>

								<div class="col-6 col-12-xsmall">
									Insert date <input type="date" name="date" id="date" value="" placeholder="Date" required/>
								</div>

								<div class="col-12">
									<ul class="actions">
										<li><input type="submit" name="submit" value="Add job call" class="primary" /></li>
									</ul>
								</div>
							</div>
						</form>
						<br />
						<p>* Not required</p>
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
