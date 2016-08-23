<?php

/**
* Catalogo
*/
class Catalogo
{
	public function catArea()
	{
		global $conexion;
		$respuesta = array();

		$sql = "SELECT idArea, area FROM area WHERE idArea <= 3 ";
		$result = $conexion->query( $sql );

		while ( $row = $result->fetch_object() ) {
			$respuesta[] = $row;
		}

		return $respuesta;
	}

	public function catImportancia()
	{
		global $conexion;
		$respuesta = array();

		$sql = "SELECT idImportancia, importancia FROM importancia";
		$result = $conexion->query( $sql );

		while ( $row = $result->fetch_object() ) {
			$respuesta[] = $row;
		}

		return $respuesta;
	}

	public function catTipoBibliografia()
	{
		global $conexion;
		$respuesta = array();

		$sql = "SELECT idTipoBibliografia, tipoBibliografia FROM tipoBibliografia";
		$result = $conexion->query( $sql );

		while ( $row = $result->fetch_object() ) {
			$respuesta[] = $row;
		}

		return $respuesta;
	}

	public function catBibliografia()
	{
		global $conexion;
		$respuesta = array();

		$sql = "SELECT idBibliografia, bibliografia, url FROM bibliografia WHERE idTipoBibliografia = 1";
		$result = $conexion->query( $sql );

		while ( $row = $result->fetch_object() ) {
			$respuesta[] = $row;
		}

		return $respuesta;
	}
}
?>


