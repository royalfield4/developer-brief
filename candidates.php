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
  // Query di caricamento dati dei candidati
	$query = "SELECT id_candidates, name, surname, seniority, competence, fk_idjobcall FROM candidates ORDER BY id_candidates DESC;";
	$statement = $pdo -> prepare($query);
	$statement -> execute();
	$dim = $statement -> rowCount();

	$res = NULL;
	if ($dim != 0) {
		$i = 0;
		while ($row = $statement -> fetch(PDO::FETCH_ASSOC)) {
			$res[$i]['id_candidates'] = $row['id_candidates'];
			$res[$i]['name'] = $row['name'];
			$res[$i]['surname'] = $row['surname'];
			$res[$i]['seniority'] = $row['seniority'];
			$res[$i]['fk_idjobcall'] = $row['fk_idjobcall'];
			$res[$i]['competence'] = $row['competence'];
			$i = $i + 1;
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
					<h1>candidates.</h1>
					<h2><a href="new-candidate.php">+ New Candidate</a></h2>

					<!-- Table -->
					<section>
            <?php if ($dim > 0) { ?>
						<div class="table-wrapper">
							<table>
								<thead>
									<tr>
										<th>Candidate</th>
										<th>Seniority level</th>
										<th>Call they applied to</th>
										<th>Notes</th>
									</tr>
								</thead>
								<tbody>
									<?php
									for ($i = 0; $i < $dim; $i++) {
									?>
									<tr>
										<td><a href="candidate-info.php?id_candidates=<?php echo $res[$i]['id_candidates']; ?>"><b><?php echo $res[$i]['name']." ".$res[$i]['surname']; ?></b></a><br> <?php echo $res[$i]['competence']; ?></td>
										<td><?php echo mb_strtoupper($res[$i]['seniority']); ?></td>
										<td>
										<?php
											$value_id = $res[$i]['fk_idjobcall'];
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
												echo $clientTable."<br /><b>".$row['title']."</b>";

											} else {
												echo "None"; // NON CANCELLARE IL PUNTO E VIRGOLA
											}
										?>
										</td>
										<td><a>Download resume</a></td>
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
            <?php } else { ?>
            <h3>No candidates avaible at the moment.<br /> Press "+ new candidate" for adding one.</h3>
            <?php } ?>
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
