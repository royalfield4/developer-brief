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
	// Controllo del del reindirizzamento
	if (!isset($_POST['applychanges'])) {
		if (!isset($_GET['id_candidates'])) {
			header("Location: calls.php");
		} elseif($_GET['id_candidates'] == "") {
			header("Location: calls.php");
		} else {
			// Query per caricare i dati dell'utente
			$query = "SELECT * FROM candidates WHERE id_candidates = ".$_GET['id_candidates'].";";
			$statement = $pdo -> prepare($query);
			$statement -> execute();
			$dim = $statement -> rowCount();

			if($dim==0)
				echo "<script type='text/javascript'> document.location = 'calls.php'; </script>";
			else
				$row = $statement -> fetch();

			// Query per caricare le JOB CALL disponibili
	  	$query_jb = "SELECT id_jobcall, title, type, creationtime, client FROM jobcall ORDER BY creationtime DESC";
	  	$statement_jb = $pdo -> prepare($query_jb);
	  	$statement_jb -> execute();
	  	$dim_jb = $statement_jb -> rowCount();

	  	$res_jb = NULL;
	  	if ($dim_jb != 0) {
	  		$i = 0;
	  		while ($row_jb = $statement_jb -> fetch(PDO::FETCH_ASSOC)) {
	  			$res_jb[$i]['id_jobcall'] = $row_jb['id_jobcall'];
	  			$res_jb[$i]['title'] = $row_jb['title'];
	  			$res_jb[$i]['type'] = $row_jb['type'];
	  			$res_jb[$i]['creationtime'] = $row_jb['creationtime'];
	  			$res_jb[$i]['client'] = $row_jb['client'];
	  			$i = $i + 1;
	  		}
	  	}
		}
	} else {
		// Query di aggiornamento/modifica dati
		if ($_POST['jobcall'] == "NULL") {
			$jobcall = "NULL";
		} else {
			$jobcall = "'".$_POST['jobcall']."'";
		}

		$query = "UPDATE candidates
							SET name = '".$_POST['name']."', surname = '".$_POST['surname']."', birthday = '".$_POST['birthday']."', address = '".$_POST['address']."',
							email = '".$_POST['email']."', seniority = '".$_POST['seniority']."', competence = '".$_POST['competence']."', fk_idjobcall = ".$jobcall."
							WHERE candidates.id_candidates = ".$_GET['id_candidates'].";
						 ";

		$statement = $pdo -> prepare($query);
		$statement -> execute();
		echo "<script type='text/javascript'> document.location = 'candidate-info.php?id_candidates=".$_GET['id_candidates']."'; </script>";
	}
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
					<h1>do changes.</h1>


					<!-- Form -->
					<section>
						<h2>UPDATE THE PERSONAL INFORMATIONS</h2>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?id_candidates=<?php echo $_GET['id_candidates']; ?>">
							<div class="row gtr-uniform">
								<div class="col-6 col-12-xsmall">
									<input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" placeholder="Name" />
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="text" name="surname" id="name" value="<?php echo $row['surname']; ?>" placeholder="Surname" />
								</div>
								<div class="col-6 col-12-xsmall">
									Birthday <input type="date" name="birthday" id="birthday" value="<?php echo $row['birthday']; ?>" placeholder="Surname" />
								</div>
								<div class="col-12">
									<textarea name="address" id="address" placeholder="Enter your address" rows="6"><?php echo $row['address']; ?></textarea>
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="text" name="competence" id="competence" value="<?php echo $row['competence']; ?>" placeholder="Competence" />
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="email" name="email" id="demo-email" value="<?php echo $row['email']; ?>" placeholder="Email" />
								</div>
								<div class="col-12">
									<select name="seniority" id="seniority">
										<option value="">- Seniority -</option>
										<option value="junior" <?php if ($row['seniority'] == "junior") echo "selected"; ?>>Junior</option>
										<option value="mid level" <?php if ($row['seniority'] == "mid level") echo "selected"; ?>>Mid level</option>
										<option value="executive" <?php if ($row['seniority'] == "executive") echo "selected"; ?>>Executive</option>
										<option value="sr manager" <?php if ($row['seniority'] == "sr manager") echo "selected"; ?>>Senior manager</option>
									</select>
								</div>
								<div class="col-12">
                  <select name="jobcall" id="jobcall">
                    <option value="NULL">- Job call -</option>
                    <?php
                      $i = $dim_jb;
                      for ($i = 0; $i < $dim_jb; $i++) {
                        if ($res_jb[$i]['client'] == "") {
                          $client = "Internal";
                        } else {
                          $client = $res_jb[$i]['client'];
                        }
												if ($res_jb[$i]['id_jobcall'] == $row['fk_idjobcall'])
													$selected = "selected";
												else
													$selected = "";
                        echo "<option value=\"".$res_jb[$i]['id_jobcall']."\" ".$selected.">".mb_strtoupper("".$res_jb[$i]['title']." - ".$client)."</option>\n";
                      }
                    ?>
									</select>
								</div>
								<div class="col-12">
									<ul class="actions">
										<li><input type="submit" name="applychanges" value="Apply changes" class="primary" /></li>
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
