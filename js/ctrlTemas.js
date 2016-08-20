miApp.controller('ctrlTemas', function($scope, $http, $routeParams, $timeout){
	$scope.lstTemas    = [];
	$scope.tag         = null;
	$scope.importancia = false;
	$scope.getTemas = function () {
		$scope.lstTemas = [];

		$http.post('response.php', {
			accion      : 'getTemas',
			idArea      : $scope.idArea,
			tag         : $scope.tag,
			importancia : $scope.importancia
		})
		.success(function (data) {

			if ( data.lstTemas )
				$scope.lstTemas = data.lstTemas;
		});
	};

	if ( $routeParams.tag && $routeParams.tag.length > 1 ) {
		$scope.tag = $routeParams.tag;
		$scope.getTemas();
	}
	else{
		$scope.idArea = $routeParams.idArea;
		$scope.getTemas();
	}

	$scope.$watch('idArea', function (_new) {
		if ( _new > 0 )
			$("#filtro").focus();
	});
});