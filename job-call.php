<!DOCTYPE HTML>
<!--
	Fabio Camporeale's DEVELOPER BRIEF for H-Farm
	Design used and edited under the CCA 3.0 license
-->
<?php
	// Controlli sul valore del parametro passato tramite metodo GET
	if (!isset($_GET['id_jobcall'])) {
		echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
	} elseif($_GET['id_jobcall'] == "") {
		echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
	} else {
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

		// Query di acquisizione dati
		$query = "SELECT title, type, date, client FROM jobcall WHERE id_jobcall = ".$_GET['id_jobcall'].";";
		$statement = $pdo -> prepare($query);
		$statement -> execute();
		$dim = $statement -> rowCount();

		if($dim==0)
			echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
		else
			$row = $statement -> fetch();

		// Query per trovare l'elenco dei candidati per la singola jobcall
		$query_can = "SELECT id_candidates, name, surname, seniority, competence, fk_idjobcall FROM candidates WHERE fk_idjobcall = ".$_GET['id_jobcall']." ORDER BY id_candidates DESC;";
		$statement_can = $pdo -> prepare($query_can);
		$statement_can -> execute();
		$dim_can = $statement_can -> rowCount();

		$res_can = NULL;
		if ($dim_can != 0) {
			$i = 0;
			while ($row_can = $statement_can -> fetch(PDO::FETCH_ASSOC)) {
				$res_can[$i]['id_candidates'] = $row_can['id_candidates'];
				$res_can[$i]['name'] = $row_can['name'];
				$res_can[$i]['surname'] = $row_can['surname'];
				$res_can[$i]['seniority'] = $row_can['seniority'];
				$res_can[$i]['fk_idjobcall'] = $row_can['fk_idjobcall'];
				$res_can[$i]['competence'] = $row_can['competence'];
				$i = $i + 1;
			}
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
					<h1><?php echo mb_strtolower($row['title']).".";?></h1>
					<h2><?php if (!strcmp($row['client'], "")) { echo "Internal";} else {echo mb_strtoupper($row['client']);} echo " | ".$row['date'];?></h2>
					<p>
					<ul class="actions">
						<li><a href="job-call-mod.php?id_jobcall=<?php echo $_GET['id_jobcall']; ?>" class="button primary">Do changes</a></li>
						<li><a href="del-jobcall.php?id_jobcall=<?php echo $_GET['id_jobcall']; ?>" class="button">Delete</a></li>
					</ul>
					<?php if(isset($_GET['err_delete'])) {?>
					<h4>CAUTION: You cannot delete this job call because there are candidates in it.</h4>
					<?php } ?>
					</p>

					<hr />

					<!-- Candidates table list -->
					<section>
						<?php
						if ($dim_can > 0) {
						?>
						<h2>Candidates list</h2>
						<div class="table-wrapper">
							<table>
								<thead>
									<tr>
										<th>Candidate</th>
										<th>Seniority level</th>
										<th>Notes</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Stampa della tabelli dai candidati alla jobcall
									for ($i = 0; $i < $dim_can; $i++) {
									?>
									<tr>
										<td>
											<a href="candidate-info.php?id_candidates=<?php echo $res_can[$i]['id_candidates']; ?>&from_jcall=<?php echo $_GET['id_jobcall']; ?>">
												<b><?php echo $res_can[$i]['name']." ".$res_can[$i]['surname']; ?></b>
											</a> <br />
											<?php echo $res_can[$i]['competence']; ?>
										</td>
										<td><?php echo mb_strtoupper($res_can[$i]['seniority']); ?></td>
										<td><a>Download resume</a></td>
									</tr>

									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<?php
						} else {
						?>
						<h2>No candidates avaible at the moment.</h2>
						<?php
						}
						?>
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
