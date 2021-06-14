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
  if (isset($_POST['submit'])) {
		// Query di inserimento dati + creazione della query
		$name = $_POST['name'];
    $surname = $_POST['surname'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $seniority = $_POST['seniority'];
    $competence = $_POST['competence'];
    $jobcall = NULL;

    if (!strcmp($_POST['jobcall'], "NULL")) {
      echo "è NULL ";
      $jobcall = "NULL";
    } else {
      $jobcall = "'".$_POST['jobcall']."'";
    }

    $query = "INSERT INTO `candidates` (`id_candidates`, `name`, `surname`, `birthday`, `address`, `email`, `seniority`, `competence`, `fk_idjobcall`)
              VALUES (NULL, '".$name."', '".$surname."', '".$birthday."', '".$address."', '".$email."', '".$seniority."', '".$competence."', ".$jobcall.");";

		$statement = $pdo -> prepare($query);
		$statement -> execute();

		echo "<script type='text/javascript'> document.location = 'candidates.php'; </script>";
  } else {
    // Query che prende i dati da stampare
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
					<h1>add a new candidate.</h1>

					<!-- Form -->
					<section>
						<h2>INSERT THE PERSONAL INFORMATIONS</h2>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div class="row gtr-uniform">
								<div class="col-6 col-12-xsmall">
									<input type="text" name="name" id="name" value="" placeholder="Name" />
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="text" name="surname" id="surname" value="" placeholder="Surname" />
								</div>
								<div class="col-6 col-12-xsmall">
									Birthday <input type="date" name="birthday" id="birthday" value="" />
								</div>
								<div class="col-12">
									<textarea name="address" id="address" placeholder="Enter your address" rows="6"></textarea>
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="text" name="competence" id="competence" value="" placeholder="Competence" />
								</div>
								<div class="col-6 col-12-xsmall">
									<input type="email" name="email" id="email" value="" placeholder="Email" />
								</div>
								<div class="col-12">
									<select name="seniority" id="seniority">
										<option value="">- Seniority -</option>
										<option value="junior">Junior</option>
										<option value="mid level">Mid level</option>
										<option value="executive">Executive</option>
										<option value="sr manager">Senior manager</option>
									</select>
								</div>
								<div class="col-12">
                  <select name="jobcall" id="jobcall">
                    <option value="NULL">- Job call -</option>
                    <?php
                      $i = $dim;
                      for ($i = 0; $i < $dim; $i++) {
                        if ($res[$i]['client'] == "") {
                          $client = "Internal";
                        } else {
                          $client = $res[$i]['client'];
                        }
                        echo "<option value=\"".$res[$i]['id_jobcall']."\">".mb_strtoupper("".$res[$i]['title']." - ".$client)."</option>\n";
                      }
                    ?>
									</select>
								</div>
								<div class="col-12">
									<ul class="actions">
										<li><input type="submit" name="submit" value="Add candidate" class="primary" /></li>
										<li><input type="reset" value="Reset" /></li>
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
