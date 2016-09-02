miApp.controller('ctrlTemas', function($scope, $http, $routeParams, $timeout){
	$scope.lstTemas    = [];
	$scope.tag         = null;
	$scope.importancia = false;
	$scope.tipoTema    = "";

	if ( $routeParams.tipoTema ) {
		$scope.tipoTema = $routeParams.tipoTema;
	}

	$scope.getTemas = function () {
		$scope.lstTemas = [];

		$http.post('response.php', {
			accion      : 'getTemas',
			idArea      : $scope.idArea,
			tag         : $scope.tag,
			importancia : $scope.importancia,
			tipoTema    : $scope.tipoTema
		})
		.success(function (data) {
			console.log( data.lstTemas );
			if ( data.lstTemas )
				$scope.lstTemas = data.lstTemas;
		});
	};

	if ( $routeParams.tag && $routeParams.tag.length > 1 ) {
		$scope.tag = $routeParams.tag;
		$scope.getTemas();
	}
	else if ( $routeParams.idArea ) {
		$scope.idArea = $routeParams.idArea;
		$scope.getTemas();
	}

		
	$scope.$watch('idArea', function (_new) {
		if ( _new > 0 ) {
			$("#filtro").focus();

			if ( $scope.tipoTema == 'tema' ) {
				localStorage.setItem( "idAreaTema", _new );
				$scope.$parent.idAreaTema = "/" + _new;
			}

			if ( $scope.tipoTema == 'pregunta' ) {
				localStorage.setItem( "idAreaPregunta", _new );
				$scope.$parent.idAreaPregunta = "/" + _new;
			}

			if ( $scope.tipoTema == 'tips' ) {
				localStorage.setItem( "idAreaTips", _new );
				$scope.$parent.idAreaTips = "/" + _new;
			}
		}


	});
});



