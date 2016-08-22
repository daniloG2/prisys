miApp.controller('ctrlTema', function($scope, $http, $routeParams, $timeout, $sce){
	$scope.tema = {}; 

	$scope.verTema = function ( idTema ) {
		$http.post('response.php', {
			accion : 'verTema',
			idTema : idTema
		})
		.success(function (data) {
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

		$http.post('response.php', {
			accion : 'agregarComentario',
			idTema : $routeParams.idTema,
			comentario : comentario
		})
		.success(function (data) {
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
	}

	$scope.Html = function ( html ) {
		return $sce.trustAsHtml( html );
	};

	$('#divEdit').trumbowyg();
	$('#verTema').trumbowyg();

});