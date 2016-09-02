<?php 
session_start();

if ( IS_NULL( $_SESSION ) OR !$_SESSION['login'] )
	header("Location: login.php");

?>
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
				<a href="#/ingresar/tema">Ingresar</a>
			</li>
			<li>
				<a ng-href="#/temas/tema{{idAreaTema}}">Temas</a>
			</li>
			<li>
				<a ng-href="#/temas/pregunta{{idAreaPregunta}}">Preguntas</a>
			</li>
			<li>
				<a ng-href="#/temas/tips{{idAreaTips}}">Tips</a>
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

<!-- Modal -->
<div class="modal" id="modalArchivos" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-cloud-upload"></i> SUBIR ARCHIVOS</h4>
      		</div>
      		<div class="modal-body">
				<form enctype="multipart/form-data">
					<label>ID DEL TEMA</label>
                	<input type="text" class="form-control" ng-model="idTema" readonly />
                	<label>SELECCIONE LOS ARCHIVOS</label>
                	<input id="documentos" name="archivos[]" type="file" multiple>
            	</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      		</div>
    	</div>
  	</div>
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