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
	// Query di caricamento dati
	$query = "SELECT id_jobcall, title, type, creationtime, client FROM jobcall ORDER BY creationtime DESC";
	$statement = $pdo -> prepare($query);
	$statement -> execute();
	$dim = $statement -> rowCount();

	$res = NULL;
	if ($dim != 0) {
		$i = 0;
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$res[$i]['id_jobcall'] = $row['id_jobcall'];
			$res[$i]['title'] = $row['title'];
			$res[$i]['type'] = $row['type'];
			$res[$i]['creationtime'] = $row['creationtime'];
			$res[$i]['client'] = $row['client'];
			$i = $i + 1;
		}
	}
	//echo "<script type='text/javascript'> document.location = 'page1.php'; </script>";
?>


<html>
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
							<header>
								<h1>calls.  <?php if($dim>0) {echo $dim." of them.";} else {echo "create one now, click on \"+ new call job\".";} ?></h1>
							</header>

							<section class="tiles">

								<!-- New job call -->
								<article class="style1">
									<span class="image">
										<img src="images/pic01.jpg" alt="" />
									</span>
									<a>
										<h2>+ New job call</h2>
										<div class="content">
											<form method="post" action="new-jobcall.php">
												<p><input type="text" name="job-name" id="job-name" value="" placeholder="Call title..." /></p>
												<ul class="actions"><li><input type="submit" value="Create" class="primary" /></li></ul>
											</form>
										</div>
									</a>
								</article>

								<!-- Print the varius job calls if the query return something -->
								<?php
								$i = 0;
								while ($i < $dim) {
									$client=NULL;
									$content=0;
									$id=$res[$i]['id_jobcall'];
									$title=$res[$i]['title'];
									if (!strcmp("in", $res[$i]['type'])) {
										$client = "Internal";
										$content = 3;
									} else {
										$client = $res[$i]['client'];
										$content = 2;
									}
								?>
								<article class="style<?php echo $content;?>'" id="<?php echo $content; ?>">
									<span class="image">
										<img src="images/pic0<?php echo $content;?>.jpg" alt="" />
									</span>
									<a href="job-call.php?id_jobcall=<?php echo $id;?>">
										<h2><?php echo $title;?></h2>
										<div class="content">
											<p><?php echo $client;?></p>
										</div>
									</a>
								</article>
								<?php
									$i++;
								}
								?>

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
