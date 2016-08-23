<?php
@session_start();
$_SESSION['user'] = 'rigo';
$data = json_decode( file_get_contents("php://input") );

// SE VALIDA SI SE ESTA RECIBIENDO UNA ACCION VALIDA
if ( !$data OR !isset( $data->accion ) ) {
	echo json_encode( array( "response" => 0, "msg" => "Parametros invalidos" ) );
	exit();
}

require 'class/conexion.php';
require 'class/session.php';
require 'class/catalogo.php';
require 'class/tema.php';

$conexion = Conexion();
switch ( $data->accion ) {
	case 'iniCat':
		$catalogo = new Catalogo();

		$datos['catArea']             = $catalogo->catArea();
		$datos['catImportancia']      = $catalogo->catImportancia();
		$datos['catTipoBibliografia'] = $catalogo->catTipoBibliografia();
		$datos['catBibliografia']     = $catalogo->catBibliografia();

		echo json_encode( $datos );
	break;

	case 'nuevoTema':
		$temaC = new Tema();
		$datos = $temaC->nuevoTema();

		echo json_encode( $datos );
	break;

	case 'modificarTema':
		$tema  = new Tema();
		$datos = $tema->modificarTema( $data->idTema, $data->descripcion );

		echo json_encode( $datos );
	break;

	case 'getTemas':
		$tema = new Tema();
		$datos['lstTemas'] = $tema->lstTemas();

		echo json_encode( $datos );
	break;

	case 'verTema':
		$tema = new Tema();
		$datos['tema'] = $tema->verTema( $data->idTema );

		echo json_encode( $datos );
	break;

	case 'agregarComentario':
		$tema = new Tema();
		$datos = $tema->agregarComentario( $data->idTema, $data->comentario );

		echo json_encode( $datos );
	break;

	case 'votarTema':
		$tema = new Tema();
		$respuesta = $tema->votarTema( $data->idComentario, $data->idTema, $data->voto );

		echo json_encode( $respuesta );
	break;

	default:
		echo json_encode( array("response" => 0, "msg" => "Accion inválida") );
	break;
}

$conexion->close();
?>






