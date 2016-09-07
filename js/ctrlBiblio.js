miApp.controller('ctrlBiblio', function($scope, $http, $routeParams, $timeout){
	$scope.idArea          = "";
	$scope.catBibliografia = [];
	$scope.catArea         = [];

	$http.post('response.php', {accion: 'catArea'})
	.success(function (data) {
		if ( data.catArea ) 
			$scope.catArea = data.catArea;
	});

	($scope.getBibliografia = function () {
		$scope.catBibliografia = [];
		$http.post('response.php', {accion: 'catBibliografia'})
		.success(function (data) {

			if ( data.catBibliografia ) {
				$scope.catBibliografia = data.catBibliografia;
				$timeout(function () {
					$scope.idArea = $scope.catBibliografia[ 0 ].idArea;
				});
			}
		});
	})();

	$("#documentos").fileinput({
		language: 'es',
	    uploadUrl: "class/archivos.php",
        showRemove : false,
	    showUpload : true,
	    autoReplace: true,
        minFileCount: 1,
		uploadAsync: false,
		uploadExtraData: function() {
			return {
				typeUpload : 'book',
				idArea     : $scope.idArea,
			};
		}
	})
	.on('filebatchpreupload', function(event, data, previewId, index) {
    	var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
	})
	.on('fileuploaded', function(event, data, previewId, index) {
    	alert( data.response.mensaje );
   		if( data.response.respuesta ){
   			$(".close.fileinput-remove").click();
   			$('#modalArchivos').modal('hide');
   			$scope.idTema       = undefined;
   			$scope.subirArchivo = false;
   		}
	})
	.on('filebatchuploadsuccess', function(event, data, previewId, index) {
   		alert( data.response.mensaje );
   		if( data.response.respuesta ){
   			$(".close.fileinput-remove").click();
   			$scope.subirArchivo = false;
   			$('#modalBiblio').modal('hide');
   			$scope.getBibliografia();
   		}
	})
	.on('filebatchuploaderror', function(event, data, msg) {
    	alert( "No se logro recibir información para guardar." );
	})
	.on('fileuploaderror', function(event, data, msg) {
    	var form = data.form, files = data.files, extra = data.extra, response = data.response, reader = data.reader;
    	alert( msg );
	});
});



