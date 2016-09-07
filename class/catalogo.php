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

	public function catBibliografia( $idArea = NULL )
	{
		global $conexion;

		$respuesta = array();
		$where     = "";

		if ( !IS_NULL( $idArea ) )
			$where = " AND b.idArea = {$idArea} ";

		$sql = "SELECT b.idBibliografia, b.bibliografia, b.url, b.idArea, a.area
				FROM bibliografia AS b
					JOIN area AS a
						ON b.idArea = a.idArea
				WHERE b.idTipoBibliografia = 1 $where
				ORDER BY b.idBibliografia ASC";
		if ( $result = $conexion->query( $sql ) ) {
			while ( $row = $result->fetch_object() ) {
				$respuesta[] = $row;
			}
		}

		return $respuesta;
	}
}
?>


