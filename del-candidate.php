<?php
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

	$query = "DELETE FROM `candidates` WHERE `candidates`.`id_candidates` = ".$_GET['id_candidates'].";";
	$statement = $pdo -> prepare($query);
	$statement -> execute();




	if (isset($_GET['from_jcall'])) {
		header("Location: job-call.php?id_jobcall=".$_GET['from_jcall']);
	} else {
		header("Location: candidates.php");
	}

?>
