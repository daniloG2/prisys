miApp.controller('ctrlTema', function($scope, $http, $routeParams, $timeout, $sce){
	$scope.tema     = {}; 
	$scope.pregunta = "";

	$scope.$parent.asignarIdTema( $routeParams.idTema );
	$scope.verTema = function ( idTema ) {
		$("#cargando").show();
		$http.post('response.php', {
			accion : 'verTema',
			idTema : idTema
		})
		.success(function (data) {
			$("#cargando").hide();

			$scope.tema = data.tema;
			$timeout(function () {
				$('#verTema').trumbowyg("html", data.tema.descripcion);
			});
		});
	};

	if ( $routeParams.idTema.length > 0 ) {
		$scope.verTema( $routeParams.idTema );
	}

	$scope.agregarComentario = function () {
		var comentario = $("#divEdit").html();

		if ( !( comentario.length > 5 ) )
			return false;

		$("#cargando").show();
		$http.post('response.php', {
			accion     : 'agregarComentario',
			idTema     : $routeParams.idTema,
			comentario : comentario
		})
		.success(function (data) {
			$("#cargando").hide();

			if ( data.response ) {
				$scope.tema.comentarios = data.lstComentarios;
				$scope.ventana1 = false;

				$("#divEdit").html("");

				alert( data.msg );
			}else{
				var msg = data.msg ? data.msg : data;
				alert( msg );
			}
		});
	};

	$scope.agregarPregunta = function () {
		var descripcion = $("#divEditPregunta").html();

		if ( !( descripcion.length > 5 ) || !( $scope.pregunta.length > 3 ) )
			return false;

		$("#cargando").show();
		$http.post('response.php', {
			accion      : 'agregarPregunta',
			idTema      : $routeParams.idTema,
			pregunta    : $scope.pregunta,
			descripcion : descripcion
		})
		.success(function (data) {
			$("#cargando").hide();

			if ( data.response ) {
				$scope.tema.preguntas = data.lstPreguntas;
				$scope.ventana2 = false;

				$("#divEditPregunta").html("");

				alert( data.msg );
			}else{
				var msg = data.msg ? data.msg : data;
				alert( msg );
			}
		});
	};

	$scope.modificarTema = function () {
		var descripcion = $("#verTema").html();
		$("#cargando").show();

		$http.post('response.php', {
			accion      : 'modificarTema',
			idTema      : $scope.tema.idTema,
			descripcion : descripcion
		})
		.success(function (data) {
			$("#cargando").hide();
			
			if ( data.response ) {
				alert( data.msg );
			}
			else {
				var msg = data.msg ? data.msg : data;
				alert( msg );
			}
		});
	};

	$scope.votar = function ( idComentario, voto ) {
		$("#cargando").show();
		$http.post('response.php', {
			accion       : 'votarTema',
			idComentario : idComentario,
			idTema       : $scope.tema.idTema,
			voto         : voto
		})
		.success(function (data) {
			$("#cargando").hide();

			if ( data.response ) {
				$scope.tema.comentarios = data.lstComentarios
			}
			else {
				var msg = data.msg ? data.msg : data;
				alert( msg );
			}
		});
	};

	$scope.Html = function ( html ) {
		return $sce.trustAsHtml( html );
	};

	$('#divEdit,#verTema,#divEditPregunta').trumbowyg();

});