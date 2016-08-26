<!DOCTYPE html>
<html lang="es" ng-app="app">
<head>
	<meta charset="UTF-8">
	<title>PriSys</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/fileinput.css">
</head>
<body ng-controller="home">
<div class="navbar">
	<div class="container-fluid">
		<a class="navbar-brand" href="#/">PriSys</a>
		<ul class="nav navbar-nav">
			<li class="active">
				<a href="#/ingresar">Ingreso Tema</a>
			</li>
			<li>
				<a href="#/temas/1">Temas</a>
			</li>
			<li>
				<a href="logout.php">Salir</a>
			</li>
		</ul>
	</div>
</div>

<div class="container">
	<div ng-view style="position:relative; width:100%;"></div>
</div>

<script src="js/jquery-1.12.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-route.min.js"></script>
<script src="js/main.js"></script>
<script src="js/fileinput.min.js"></script>
<script src="js/locales/es.js"></script>
<script src="js/ctrlIngresar.js"></script>
<script src="js/ctrlTemas.js"></script>
<script src="js/ctrlTema.js"></script>
</body>
</html>