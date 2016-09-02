<?php
session_start();

require 'class/conexion.php';

if ( is_null( $_SESSION ) OR !$_SESSION['login'] ):
	if ( isset( $_POST['user'] ) AND isset( $_POST['pass'] ) ):
		$conexion = Conexion();

		$user = $_POST['user'];
		$pass = $_POST['pass'];

		$sql = "SELECT 1 AS 'sessionValid', usuario FROM usuario WHERE usuario='{$user}' AND clave=md5('{$pass}');"; 
		$rs = $conexion->query( $sql );

		if ( $row = $rs->fetch_object() ):
			$_SESSION['login'] = true;
			$_SESSION['user']  = $row->usuario;
			header("Location: ./");
		else:
			$message = "Error, usuario o clave no valido. <br>NO TE PASES DE LISTO WEY, SOLO PERSONAL AUTORIZADO MEN..!";
		endif;

		$conexion->close();
	endif;
endif;

?>
<!DOCTYPE html>
<html lang="es-GT">
<head>
	<meta charset="UTF-8">
	<title>PriSys</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body ng-controller="home">
<div class="navbar">
	<div class="container-fluid">
		<a class="navbar-brand" href="#/">PriSys</a>
	</div>
</div>

<div class="container">
	<div class="col-sm-6 col-sm-offset-3">
		<?php if ( isset( $message ) ) { ?>
		<div class="col-sm-12">
			<h4 class="alert alert-danger"><?= $message;?></h4>
		</div>
		<?php } ?>
		<form action="login.php" method="POST" class="form-horizontal" role="form">
			<div class="form-group">
				<legend>
					<span class="glyphicon glyphicon-lock"></span>
					Login
				</legend>
			</div>
			<div class="form-group">
				<label class="col-sm-3">User</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="user" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">Pass</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="pass" autofocus>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3">
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-log-in"></span>
						<b>Ingresar</b>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
</body>
</html>