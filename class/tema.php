<?php

/**
* Tema
*/
class Tema
{
	public function nuevoTema()
	{
		global $conexion, $data;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );
		$tema      = $data->tema;
		$idTema    = uniqid();

		$tema->descripcion = $conexion->real_escape_string( $tema->descripcion );

		$sql = "INSERT INTO tema VALUES 
				('{$idTema}', {$tema->idArea}, '{$tema->tema}', '{$tema->descripcion}', $tema->idImportancia, 
					now(), '" . $_SESSION['user'] . "')";
		if ( $conexion->query( $sql ) ) {
			if ( count( $tema->tags ) ) {
				$tags = "";
				foreach ($tema->tags as $tag) {
					$tags .= "('{$idTema}', '{$tag}'),";
				}

				$sql = "INSERT INTO etiquetaTema VALUES " . rtrim( $tags, "," );
				$conexion->query( $sql );
			}

			if ( count( $tema->bibliografias ) ) {
				foreach ( $tema->bibliografias as $biblio ) {
					if ( $biblio->idTipoBibliografia == 1 ) {
						$biblio->paginaLibro = $biblio->paginaLibro > 0 ? $biblio->paginaLibro : 0;
						$sql = "INSERT INTO temaBibliografia 
								VALUES ( '{$idTema}', {$biblio->idBibliografia}, {$biblio->paginaLibro}, '', now(), '{$_SESSION["user"]}' )";
						$conexion->query( $sql );
					}else if ( $biblio->idTipoBibliografia == 2 ) {
						$sql = "INSERT INTO bibliografia (idTipoBibliografia, bibliografia, url) 
								VALUES ( {$biblio->idTipoBibliografia}, '{$biblio->url}', '{$biblio->url}')";
						$conexion->query( $sql );
						$biblio->idBibliografia = $conexion->insert_id;

						$sql = "INSERT INTO temaBibliografia 
								VALUES ( '{$idTema}', {$biblio->idBibliografia}, NULL, '', now(), '{$_SESSION["user"]}' )";
						$conexion->query( $sql );
					}
				}
			}

			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente" );
		}
		else{
			$respuesta = array( "response" => 0, "msg" => "ERROR: " . $conexion->error );
		}

		return $respuesta;
	}

	public function lstTemas()
	{
		global $conexion, $data;

		$lstTemas = array();
		$order    = "";
		$where    = "";
		
		if ( isset( $data->importancia ) AND $data->importancia )
			$order = " i.idImportancia ASC, ";

		if ( isset( $data->tag ) AND strlen( $data->tag ) > 1 )
			$where = " WHERE !ISNULL( et.idTema ) AND et.etiqueta = '{$data->tag}' ";
		
		else
			$where = " WHERE t.idArea = {$data->idArea} ";

		$sql = "SELECT 
				    t.idTema,
				    t.tema,
				    t.descripcion,
				    CONCAT(LEFT(t.descripcion, 30), '...') AS 'descripcionCorta',
				    DATE_FORMAT(t.fechaHora, '%d/%m/%Y - %H:%i') AS 'fecha',
				    a.idArea,
				    a.area,
				    i.idImportancia,
				    i.importancia,
				    u.usuario,
				    u.nombre,
				    GROUP_CONCAT(et.etiqueta
				        SEPARATOR '_R_') AS 'etiquetas'
				FROM
				    tema AS t
				        JOIN
				    area AS a ON t.idArea = a.idArea
				        JOIN
				    importancia AS i ON t.idImportancia = i.idImportancia
				        JOIN
				    usuario AS u ON t.usuario = u.usuario
				        LEFT JOIN
				    etiquetaTema AS et ON t.idTema = et.idTema
				$where
				GROUP BY t.idTema
				ORDER BY i.idImportancia ASC, t.fechaHora ASC ";

		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			
			if ( strlen( $row->etiquetas ) )
				$row->etiquetas = explode( "_R_", $row->etiquetas );
			
			else
				$row->etiquetas = array();

			$lstTemas[] = $row;
		}

		return $lstTemas;
	}

	public function verTema( $idTema )
	{
		global $conexion;

		$miTema = (object)array();

		$sql = "SELECT 
				    t.idTema,
				    t.tema,
				    t.descripcion,
				    CONCAT(LEFT(t.descripcion, 30), '...') AS 'descripcionCorta',
				    DATE_FORMAT(t.fechaHora, '%d/%m/%Y - %H:%i') AS 'fecha',
				    a.idArea,
				    a.area,
				    t.idImportancia,
				    u.usuario,
				    u.nombre,
				    GROUP_CONCAT(et.etiqueta SEPARATOR '_R_') AS 'etiquetas'
				FROM
				    tema AS t
				        JOIN
				    area AS a ON t.idArea = a.idArea
				        JOIN
				    usuario AS u ON t.usuario = u.usuario
				        LEFT JOIN
				    etiquetaTema AS et ON t.idTema = et.idTema
				WHERE t.idTema = '{$idTema}' ";

		$rs = $conexion->query( $sql );
		if ( $row = $rs->fetch_object() ) {
			if ( strlen( $row->etiquetas ) )
				$row->etiquetas = explode( "_R_", $row->etiquetas );
			
			else
				$row->etiquetas = array();

			$row->comentarios   = $this->lstComentarios( $idTema );
			$row->bibliografias = array();

			$sql = "SELECT 
					    tb.paginaLibro, b.idTipoBibliografia, b.bibliografia, b.url
					FROM
					    temaBibliografia AS tb
					        JOIN
					    bibliografia AS b ON tb.idBibliografia = b.idBibliografia
					WHERE
					    tb.idTema = '{$idTema}'";
			$rs = $conexion->query( $sql );
			while ( $rowc = $rs->fetch_object() ) {
				$row->bibliografias[] = $rowc;
			}

			$miTema = $row;
		}

		return $miTema;
	}

	public function lstComentarios( $idTema )
	{
		global $conexion;
		$comentarios = array();

		$sql = "SELECT 
					c.comentario,
					c.idTemaReferencia,
				    DATE_FORMAT( c.fechaHora, '%d/%m/%Y - %H:%i' )as 'fecha',
				    u.usuario,
				    u.nombre
				FROM comentario AS c
					JOIN usuario AS u
						ON c.usuario = u.usuario
				WHERE c.idTema = '{$idTema}'";
		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			$comentarios[] = $row;
		}

		return $comentarios;
	}

	public function agregarComentario( $idTema, $comentario )
	{
		global $conexion;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );

		$idComentario = uniqid();
		$comentario = $conexion->real_escape_string( $comentario );

		$sql = "INSERT INTO comentario VALUES 
				('{$idComentario}', '{$idTema}', NULL, '{$comentario}', now(), '" . $_SESSION['user'] . "')";
		if ( $conexion->query( $sql ) ) {
			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente", "lstComentarios" => $this->lstComentarios( $idTema ) );
		}else{
			$respuesta = array( "response" => 0, "msg" => $conexion->error );
		}

		return $respuesta;
	}



}
?>










