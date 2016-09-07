<?php 
/**
* SUBIR ARCHIVOS
*/
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

if( ( !isset( $_POST['idTema'] ) OR !isset( $_FILES ) ) AND !isset( $_POST['typeUpload'] ) ) {
	$error = true;
	echo "Aqui llego no mas...";
	exit();
}

include 'conexion.php';

$conect  = Conexion();
$error   = false;
$mensaje = "";
$tipo    = "adjunto";

if ( isset( $_POST['typeUpload'] ) ) {
	$carpetaAdjunta = "../book/";
	$tipo           = "book";
	$idArea         = $_POST['idArea'];
}
else{
	$idTema         = $_POST['idTema'];
	$carpetaAdjunta = "../uploads/";
}

// INICILIZAR ARREGLO PARA DOCUMENTOS UNIDOS
$FilesUnicos = array(
				'name'     => array(),
				'type'     => array(),
				'tmp_name' => array(),
				'error'    => array(),
				'size'     => array()
			);

function cleanName( $name )
{
	if ( strlen( $name ) ) {
		$name = str_replace( 
			array("ñ","á","é","í","ó","ú","Ñ","Á","É","Í","Ó","Ú"," "),
			array("n","a","e","i","o","u","N","A","E","I","O","U","_"),
			$name
		);
	}

	return $name;
}

// DATOS QUE RECIBO
$totalArchivos = count( isset($_FILES) ? $_FILES['archivos']['name'] : 0 );
$data = $_FILES['archivos'];

// RECORRER ARREGLO RECIBIDO
for ($i = 0; $i < $totalArchivos; $i++) { 
	if ( $tipo == "adjunto" ) {
		if( !in_array( cleanName( $data['name'][$i] ), $FilesUnicos['name'] ) ){	// VERIFICA ARCHIVO NO REPETIDO
			$FilesUnicos['name'][]     = cleanName( $data['name'][$i] );
			$FilesUnicos['type'][]     = $data['type'][$i];
			$FilesUnicos['tmp_name'][] = $data['tmp_name'][$i];
			$FilesUnicos['error'][]    = $data['error'][$i];
			$FilesUnicos['size'][]     = $data['size'][$i];
		}
	}else{
		$FilesUnicos['name'][]     = cleanName( $data['name'] );
		$FilesUnicos['type'][]     = $data['type'];
		$FilesUnicos['tmp_name'][] = $data['tmp_name'];
		$FilesUnicos['error'][]    = $data['error'];
		$FilesUnicos['size'][]     = $data['size'];
	}
}


###### INICIALIZAR TRANSACCIÓN
$conect->query( "START TRANSACTION" );
if ( $tipo == "adjunto" ) {
	$sql    = "SELECT * FROM tema WHERE idTema = '{$idTema}'";
	$result = $conect->query( $sql );
	if ( !( $result AND $result->num_rows > 0 ) ) {
		$error   = true;
		$mensaje = "No se encontraron resultados con el Tema seleccionado.";
	}
}

if( !$error ){
	#################### SUBIR E INSERTAR ARCHIVOS ####################
	$totalArchivosUnidos = count( $FilesUnicos['name'] );
	// RECORRER EL ARREGLO DE ARCHIVOS RECIBIDO
	for ( $i = 0; $i < $totalArchivosUnidos; $i++ ) { 
		
		if ( $tipo == "adjunto" )
			$nombreArchivo  = $idTema.'_'.$FilesUnicos['name'][$i];

		else
			$nombreArchivo  = $FilesUnicos['name'][$i];

		$nombreTemporal = $FilesUnicos['tmp_name'][$i];
		$info           = new SplFileInfo($FilesUnicos['name'][$i]);		// OBTENER EXTENSIÓN
		$rutaArchivo    = $carpetaAdjunta . $nombreArchivo; 		// DIRECTORIO + ARCHIVO

		if( move_uploaded_file( $nombreTemporal, $rutaArchivo ) ){	// VALIDAR QUE LOS ARCHIVOS SE CARGUEN
			$rutaArchivo = substr( $rutaArchivo, 3 ); // ELIMINA ../
			// SI ES DE TIPO ADJUNTO
			if ( $tipo == "adjunto" ) {
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
			}
			// SI ES DE TIPO ADJUNTO
			else {
				$sql = "INSERT INTO bibliografia 
						(idTipoBibliografia, bibliografia, url, idArea) VALUES
						(1, '{$nombreArchivo}', '$rutaArchivo', {$idArea})";
				if( !$conect->query( $sql ) ){
					$error   = true;
					$mensaje = "Error al guardar en la BD la Bibliografia";		
				}
			}
		}else{
			$error   = true;
			$mensaje = "Error al Mover el archivo {$nombreArchivo}";
			break;
		}
	}
}

// FINALIZAR LA TRANSACCIÓN
if( $error ){
	$conect->query( "ROLLBACK" );
	$respuesta = 0;

	// ELIMINA LOS ARCHIVOS INGRESADO
	for( $i = 0; $i < $totalArchivosUnidos; $i++ ) {
		
		if ( $tipo == "adjunto" )
			$nombreArchivo  = $idTema.'_'.$FilesUnicos['name'][$i];

		else
			$nombreArchivo  = $FilesUnicos['name'][$i];

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

	if ( $tipo == "adjunto" )
		$mensaje = "Archivos registrados exitosamente.";

	else
		$mensaje = "Libro subido correctamente.";
}

if( $error )
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje, "error" => $mensaje );
else
	$response = array( "respuesta" => $respuesta, "mensaje" => $mensaje );


echo json_encode( $response ); 

?>