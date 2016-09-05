miApp.controller('ctrlIngresar', function($scope, $http, $routeParams, $timeout){

	$scope.subirArchivo        = true;
	$scope.catArea             = [];
	$scope.catImportancia      = [];
	$scope.catTipoBibliografia = [];
	$scope.catBibliografia     = [];
	$scope.tipoTema            = ""
	$scope.tag                 = "";
	$scope.tema                = {
		idArea        : 0,
		idImportancia : 0,
		tema          : "",
		descripcion   : "",
		tags 		  : [],
		bibliografias : []
	};

	if ( $routeParams.tipoTema ) {
		$scope.tipoTema = $routeParams.tipoTema;
	}

	$scope.idTipoBibliografia = "";
	$scope.idBibliografia     = "";
	$scope.paginaLibro        = "";
	$scope.enlace             = "";

	$scope.$watch('idTipoBibliografia', function (_new) {
		if ( _new == 1 ) {
			$scope.idBibliografia = $scope.catBibliografia[ 0 ].idBibliografia;
			$scope.enlace         = "";
		}else{
			$scope.paginaLibro    = "";
			$scope.idBibliografia = "";
		}
	});

	$("#cargando").show();
	$http.post('response.php', {accion: 'iniCat'})
	.success(function (data) {
		$("#cargando").hide();
		$scope.catArea             = data.catArea;
		$scope.catImportancia      = data.catImportancia;
		$scope.catTipoBibliografia = data.catTipoBibliografia;
		$scope.catBibliografia     = data.catBibliografia;

		$timeout(function () {
			if ( localStorage.getItem("idAreaTemaIngreso") )
				$scope.tema.idArea = localStorage.getItem("idAreaTemaIngreso");
			
			$scope.tema.idImportancia = $scope.catImportancia[ 0 ].idImportancia;
		});
	});

	$scope.addTag = function () {
		if ( $scope.tag.length > 1 ) {
			if ( !$scope.tema.tags.includes( $scope.tag.toUpperCase() ) )
				$scope.tema.tags.push( $scope.tag.toUpperCase() );

			$scope.tag = "";
		}
	};

	$scope.addBiblio = function () {
		if ( $scope.idTipoBibliografia == 1 && $scope.idBibliografia > 0 ) {
			$scope.tema.bibliografias.push({
				idTipoBibliografia : $scope.idTipoBibliografia,
				idBibliografia     : $scope.idBibliografia,
				paginaLibro        : ( $scope.paginaLibro > 0 ? $scope.paginaLibro : null ),
				url                : null,
				tipo               : $scope.getIx( 'catTipoBibliografia', 'idTipoBibliografia', $scope.idTipoBibliografia, 'tipoBibliografia' ),
				cont               : $scope.getIx( 'catBibliografia', 'idBibliografia', $scope.idBibliografia, 'bibliografia' ) + 
										" ( " + ($scope.paginaLibro) + " )"
			});
		} else if ( $scope.idTipoBibliografia == 2 && $scope.enlace.length > 5 ) {
			$scope.tema.bibliografias.push({
				idTipoBibliografia : $scope.idTipoBibliografia,
				idBibliografia     : null,
				paginaLibro        : null,
				url                : $scope.enlace,
				tipo               : $scope.getIx( 'catTipoBibliografia', 'idTipoBibliografia', $scope.idTipoBibliografia, 'tipoBibliografia' ),
				cont               : $scope.enlace
			});
			
			$scope.enlace = "";
		}
	};

	$scope.getIx = function ( _arr, _id, _val, _get ) {
		var index = -1;

		for (var i = 0; i < $scope[ _arr ].length; i++) {
			if ( $scope[ _arr ][ i ][ _id ] == _val ) {
				index = i;
				break;
			}
		}

		return $scope[ _arr ][ index ][ _get ];
	};

	$scope.guardarTema = function () {
		$scope.tema.descripcion = $("#divEdit").html();

		if ( !( $scope.tema.idArea > 0 ) )
			alert( "Area no def" );

		else if ( !( $scope.tema.idImportancia > 0 ) )
			alert( "Importancia no def" );

		else if ( !( $scope.tema.tema.length > 3 ) )
			alert( "Título no def" );

		else if ( !( $scope.tema.descripcion.length > 5 ) )
			alert( "Descripcion no def" );

		else{
			$("#cargando").show();
			
			$http.post('response.php', {
				accion   : 'nuevoTema',
				tema     : $scope.tema,
				tipoTema : $scope.tipoTema
			})
			.success(function (data) {
				$("#cargando").hide();

				if ( data.response ) {
					alert( data.msg );
					$scope.reset();
					// SI ESTA SELECCIONADO SUBIR UN ARCHIVO
					if( $scope.subirArchivo ){
						$scope.$parent.asignarIdTema( data.idTema );
						$timeout(function(){
							$('#modalArchivos').modal('show');
						});
						
					}
				}else{
					var msg = data.msg ? data.msg : data;
					alert( msg );
				}
			});
		}
	};

	$scope.reset = function () {
		$scope.tema.tema          = "";
		$scope.tema.descripcion   = "";
		$scope.tema.tags          = [];
		$scope.tema.bibliografias = [];
		$("#divEdit").html("");
		$("#divEdit").html("");
	};

	$timeout(function () {
		$("#tituloTema").focus();
	});

	$scope.$watch('tema.idArea', function (_new) {
		if ( _new > 0 ) {
			localStorage.setItem("idAreaTemaIngreso", _new);
		}
	});
	
	$('#divEdit').trumbowyg();
});



