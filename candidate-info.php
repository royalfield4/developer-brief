<!DOCTYPE HTML>
<!--
	Fabio Camporeale's DEVELOPER BRIEF for H-Farm
	Design used and edited under the CCA 3.0 license
-->
<?php
	// Controllo paramentro passato via metodo  GET
	if (!isset($_GET['id_candidates'])) {
		header("Location: calls.php");
	} elseif($_GET['id_candidates'] == "") {
		header("Location: calls.php");
	} else {
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

		$query = "SELECT * FROM candidates WHERE id_candidates = ".$_GET['id_candidates'].";";
		$statement = $pdo -> prepare($query);
		$statement -> execute();
		$dim = $statement -> rowCount();

		if($dim==0)
			echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
		else
			$row = $statement -> fetch();
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
							<h1><?php echo mb_strtolower($row['name']." ".$row['surname']); ?>.</h1>

							<!-- Text -->
							<section>
								<h2><?php echo mb_strtolower($row['competence']); ?></h2>
								<p>
									<b>Birthday</b> <?php echo mb_strtolower($row['birthday']); ?><br/>
									<b>Email</b> <?php echo mb_strtolower($row['email']); ?><br/>
									<b>Address</b> <?php echo $row['address']; ?><br/>
									<b>Seniority level</b> <?php echo mb_strtoupper($row['seniority']); ?><br/>
									<b>Call they applied to</b>
									<?php
										$value_id = $row['fk_idjobcall'];
										$query = "SELECT type, title, client FROM jobcall WHERE id_jobcall = '".$value_id."';";
										$statement = $pdo -> prepare($query);
										$statement -> execute();
										$row = $statement -> fetch(PDO::FETCH_ASSOC);
										$hasRes = $statement -> rowCount();
										if ($hasRes == 1){
											if (!strcmp($row['type'], "ex")) {
												$clientTable = mb_strtoupper($row['client']);
											} else {
												$clientTable = "INTERNAL";
											}
											echo $clientTable." - ".$row['title'];

										} else {
											echo "None"; // NON CANCELLARE IL PUNTO E VIRGOLA
										}
									?>
								</p>
								<p>
								<ul class="actions">
									<li><a href="candidate-mod.php?id_candidates=<?php echo $_GET['id_candidates']; ?>" class="button primary">Do changes</a></li>
									<li><a href="#" id= "delete" class="button" onclick="askConferm()">Delete</a></li>
								</ul></p>
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
			<script>
			function askConferm() {
				var r = confirm("Do you want to proceed with the elimination?");
				if (r == true) {
					<?php
					if (isset($_GET['from_jcall'])) {
						$from_jcall="&from_jcall=".$_GET['from_jcall'];
					} else {
						$from_jcall="";
					}
					?>
					location.replace("del-candidate.php?id_candidates=<?php echo $_GET['id_candidates'].$from_jcall;?>");
				}
			}
			</script>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
