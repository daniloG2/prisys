<?php 
/**
* SUBIR ARCHIVOS
*/

if( !isset($_POST['idTema']) || !isset($_FILES) ){
	$error = true;
	exit();
}

include 'conexion.php';

$conect         = Conexion();
$error          = false;
$mensaje        = "";
$sql            = "";
$idTema         = $_POST['idTema'];
$carpetaAdjunta = "../uploads/";

// INICILIZAR ARREGLO PARA DOCUMENTOS UNIDOS
$FilesUnicos = array(
				'name'     => array(),
				'type'     => array(),
				'tmp_name' => array(),
				'error'    => array(),
				'size'     => array()
			);

// DATOS QUE RECIBO
$totalArchivos = count( isset($_FILES) ? $_FILES['archivos']['name'] : 0 );
$data = $_FILES['archivos'];

// RECORRER ARREGLO RECIBIDO
for ($i = 0; $i < $totalArchivos; $i++) { 
	if( !in_array( str_replace(' ', '', $data['name'][$i]), $FilesUnicos['name'] ) ){	// VERIFICA ARCHIVO NO REPETIDO
		$FilesUnicos['name'][]     = str_replace(' ', '', $data['name'][$i]);
		$FilesUnicos['type'][]     = $data['type'][$i];
		$FilesUnicos['tmp_name'][] = $data['tmp_name'][$i];
		$FilesUnicos['error'][]    = $data['error'][$i];
		$FilesUnicos['size'][]     = $data['size'][$i];
	}
}


###### INICIALIZAR TRANSACCIÓN
$conect->query( "START TRANSACTION" );
$sql = "SELECT * FROM tema WHERE idTema = '{$idTema}'";

if( $result = $conect->query($sql) ){
	if( $result->num_rows > 0 ){				// INSERTAR ARCHIVOS SI EXISTE EL REGISTRO

		#################### SUBIR E INSERTAR ARCHIVOS ####################
		$totalArchivosUnidos = count( $FilesUnicos['name'] );
		// RECORRER EL ARREGLO DE ARCHIVOS RECIBIDO
		for ( $i = 0; $i < $totalArchivosUnidos; $i++ ) { 
			
			$nombreArchivo  = $idTema.'_'.$FilesUnicos['name'][$i];
			$nombreTemporal = $FilesUnicos['tmp_name'][$i];
			$info           = new SplFileInfo($FilesUnicos['name'][$i]);		// OBTENER EXTENSIÓN
			$rutaArchivo    = $carpetaAdjunta . $nombreArchivo; 		// DIRECTORIO + ARCHIVO

			if( move_uploaded_file( $nombreTemporal, $rutaArchivo ) ){	// VALIDAR QUE LOS ARCHIVOS SE CARGUEN

				$sql = "INSERT INTO adjunto (nombre, url) VALUES('{$nombreArchivo}', '$rutaArchivo')";

				if( $conect->query( $sql ) ){
					$idAdjunto = $conect->insert_id;
					$sql = "INSERT INTO temaAdjunto (idTema, idAdjunto) VALUES('{$idTema}', {$idAdjunto})";
					if( !$conect->query( $sql ) ){
						$error   = true;
						$mensaje = "Error al guardar el registro el adjunto al tema.";
						break;
					}
				}else{
					$error   = true;
					$mensaje = "Error al insertar un registro en el archivo adjunto.";
					break;
				}
			}else{
				$error   = true;
				$mensaje = "Error al Mover el archivo {$nombreArchivo}";
				break;
			}
		}

	}else{
		$error = true;
		$mensaje = "No se encontraron resultados con el Tema seleccionado.";
	}
}else{
	$error   = true;
	$mensaje = "No se pudo ejecutar la sentencia SQL.";
}

// FINALIZAR LA TRANSACCIÓN
if( $error ){
	$conect->query( "ROLLBACK" );
	$respuesta = 0;

	// ELIMINA LOS ARCHIVOS INGRESADO
	for( $i = 0; $i < $totalArchivosUnidos; $i++ ) {
		$nombreArchivo  = $idTema.'_'.$FilesUnicos['name'][$i];
		$nombreTemporal = $FilesUnicos['tmp_name'][$i];
		$rutaArchivo    = $carpetaAdjunta . $nombreArchivo;
	
		if ( file_exists( $rutaArchivo ) ) {
			unlink( $rutaArchivo );
		}
	}

}
else{
	$conect->query( "COMMIT" );
	$respuesta = 1;
	$mensaje = "Archivos registrados exitosamente.";
}

if( $error )
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje, "error" => $mensaje );
else
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje );


echo json_encode( $response ); 

?>