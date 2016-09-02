<?php

/**
* Tema
*/
class Tema
{
	public function nuevoTema()
	{
		global $conexion, $data, $session;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );
		$tema      = $data->tema;
		$idTema    = uniqid();

		$tema->descripcion = $conexion->real_escape_string( $tema->descripcion );

		if ( $data->tipoTema == 'tema' )
			$idTipoTema = 1;

		else if ( $data->tipoTema == 'pregunta' )
			$idTipoTema = 2;

		else if ( $data->tipoTema == 'tips' )
			$idTipoTema = 3;

		$sql = "INSERT INTO tema 
				(idTema, idTipoTema, idArea, tema, descripcion, idImportancia, fechaHora, usuario) 
					VALUES 
				('{$idTema}', {$idTipoTema}, {$tema->idArea}, '{$tema->tema}', '{$tema->descripcion}', $tema->idImportancia, 
					now(), '{$session->getUser()}')";
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
								VALUES ( '{$idTema}', {$biblio->idBibliografia}, {$biblio->paginaLibro}, '', now(), '{$session->getUser()}' )";
						$conexion->query( $sql );
					}else if ( $biblio->idTipoBibliografia == 2 ) {
						$sql = "INSERT INTO bibliografia (idTipoBibliografia, bibliografia, url) 
								VALUES ( {$biblio->idTipoBibliografia}, '{$biblio->url}', '{$biblio->url}')";
						$conexion->query( $sql );
						$biblio->idBibliografia = $conexion->insert_id;

						$sql = "INSERT INTO temaBibliografia 
								VALUES ( '{$idTema}', {$biblio->idBibliografia}, NULL, '', now(), '{$session->getUser()}' )";
						$conexion->query( $sql );
					}
				}
			}

			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente", "idTema" => $idTema );
		}
		else{
			$respuesta = array( "response" => 0, "msg" => "ERROR: " . $conexion->error );
		}

		return $respuesta;
	}

	public function modificarTema( $idTema, $descripcion )
	{
		global $conexion, $data, $session;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );

		$descripcion = $conexion->real_escape_string( $descripcion );

		$sql = "SELECT usuario FROM tema WHERE idTema = '{$idTema}' ";
		$rs = $conexion->query( $sql );
		if ( $row = $rs->fetch_object() ) {
			if ( $row->usuario == $session->getUser() ) {
				$sql = "UPDATE tema SET descripcion = '{$descripcion}' 
						WHERE idTema = '{$idTema}' ";
				if ( $conexion->query( $sql ) ) {
					$respuesta = array( "response" => 1, "msg" => "Guardado correctamente" );
				}
				else{
					$respuesta = array( "response" => 0, "msg" => "ERROR: " . $conexion->error );
				}
			}
			else {
				$respuesta = array( "response" => 0, "msg" => "Solo puede modificarlo ({$row->usuario}) " );
			}
		}

		return $respuesta;
	}

	public function lstTemas()
	{
		global $conexion, $data, $session;

		$lstTemas = array();
		$order    = "";
		$where    = "";
		
		if ( isset( $data->importancia ) AND $data->importancia )
			$order = " i.idImportancia ASC, ";

		if ( isset( $data->tag ) AND strlen( $data->tag ) > 1 ):
			$where = " !ISNULL( et.idTema ) AND et.etiqueta = '{$data->tag}' ";
		
		else:
			if ( $data->tipoTema == 'tema' )
				$idTipoTema = 1;

			else if ( $data->tipoTema == 'pregunta' )
				$idTipoTema = 2;

			else if ( $data->tipoTema == 'tips' )
				$idTipoTema = 3;

			$where = " t.idArea = {$data->idArea} AND t.idTipoTema = {$idTipoTema} ";
		endif;

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
				    IF( !ISNULL( tv.idTema ), 1, 0 ) AS 'visto',
				    GROUP_CONCAT(et.etiqueta
				        SEPARATOR '_R_') AS 'etiquetas',
				    tt.idTipoTema,
				    tt.tipoTema
				FROM
				    tema AS t
				        JOIN
				    area AS a ON t.idArea = a.idArea
				    	JOIN
				    tipoTema AS tt ON t.idTipoTema = tt.idTipoTema
				        JOIN
				    importancia AS i ON t.idImportancia = i.idImportancia
				        JOIN
				    usuario AS u ON t.usuario = u.usuario
				        LEFT JOIN
				    etiquetaTema AS et ON t.idTema = et.idTema
				    	LEFT JOIN
				    temaVisto AS tv
				    	ON t.idTema = tv.idTema AND tv.usuario = '{$session->getUser()}'
				WHERE $where
				GROUP BY t.idTema
				ORDER BY i.idImportancia ASC, t.fechaHora ASC ";

		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			$row->visto = (bool)$row->visto;
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
		global $conexion, $session;

		$miTema = (object)array();

		// MARCA COMO TEMA VISTO
		$sql = "INSERT INTO temaVisto VALUES ('{$idTema}', '{$session->getUser()}', NOW() )";
		$conexion->query( $sql );

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
				    GROUP_CONCAT(et.etiqueta SEPARATOR '_R_') AS 'etiquetas',
				    tt.idTipoTema,
				    tt.tipoTema
				FROM
				    tema AS t
				    	JOIN
				    tipoTema AS tt ON t.idTipoTema = tt.idTipoTema
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
			$row->preguntas     = $this->lstPreguntas( $idTema );
			$row->adjuntos      = $this->lstAdjuntos( $idTema );
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
		global $conexion, $session;
		$comentarios = array();

		$sql = "SELECT 
					c.idComentario,
					c.comentario,
					c.idTemaReferencia,
					DATE_FORMAT( c.fechaHora, '%d/%m/%Y - %H:%i' )as 'fecha',
					u.usuario,
					u.nombre,
				    ROUND(SUM( IFNULL( cc.idImportancia, 0) ) /
				    COUNT( cc.idComentario ))AS 'ranking',
					SUM( IF( cc.usuario = '{$session->getUser()}', cc.idImportancia, 0 ) )AS 'miVoto'
				FROM comentario AS c
					JOIN usuario AS u
						ON c.usuario = u.usuario
					LEFT JOIN calificacionComentario AS cc
						ON c.idComentario = cc.idComentario
				WHERE c.idTema = '{$idTema}'
				GROUP BY c.idComentario
				ORDER BY ranking ASC, c.fechaHora ASC ";
		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			$comentarios[] = $row;
		}

		return $comentarios;
	}

	public function lstPreguntas( $idTema )
	{
		global $conexion, $session;
		$preguntas = array();

		$sql = "SELECT 
					p.pregunta,
					p.descripcion,
					p.usuario,
					DATE_FORMAT( p.fechaHora, '%d/%m/%Y %H:%i' ) AS 'fecha'
				FROM pregunta AS p 
				WHERE p.idTema = '{$idTema}' 
				ORDER BY p.fechaHora ASC ";
		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			$preguntas[] = $row;
		}

		return $preguntas;
	}

	public function lstAdjuntos( $idTema )
	{
		global $conexion;
		$adjuntos = array();

		$sql = "SELECT a.nombre, a.url
				FROM temaAdjunto AS ta
					JOIN adjunto AS a 
						ON ta.idAdjunto = a.idAdjunto
				WHERE ta.idTema = '{$idTema}' ";
		$rs = $conexion->query( $sql );
		while ( $row = $rs->fetch_object() ) {
			$adjuntos[] = $row;
		}

		return $adjuntos;
	}

	public function agregarPregunta( $idTema, $pregunta, $descripcion )
	{
		global $conexion, $session;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );

		$descripcion = $conexion->real_escape_string( $descripcion );

		$sql = "INSERT INTO pregunta VALUES 
				( '{$idTema}', '{$pregunta}', '{$descripcion}', '{$session->getUser()}', NOW() )";
		if ( $conexion->query( $sql ) ) {
			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente", "lstPreguntas" => $this->lstPreguntas( $idTema ) );
		}else{
			$respuesta = array( "response" => 0, "msg" => $conexion->error );
		}

		return $respuesta;
	}

	public function agregarComentario( $idTema, $comentario )
	{
		global $conexion, $session;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );

		$idComentario = uniqid();
		$comentario = $conexion->real_escape_string( $comentario );

		$sql = "INSERT INTO comentario VALUES 
				('{$idComentario}', '{$idTema}', NULL, '{$comentario}', now(), '{$session->getUser()}')";
		if ( $conexion->query( $sql ) ) {
			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente", "lstComentarios" => $this->lstComentarios( $idTema ) );
		}else{
			$respuesta = array( "response" => 0, "msg" => $conexion->error );
		}

		return $respuesta;
	}

	public function votarTema( $idComentario, $idTema, $voto )
	{
		global $conexion, $session;
		$respuesta = array( "response" => 0, "msg" => "ERROR" );

		$sql = "INSERT INTO calificacionComentario (idComentario, usuario, idImportancia) 
				VALUES ('{$idComentario}', '{$session->getUser()}', {$voto} )
				ON DUPLICATE KEY UPDATE idImportancia = {$voto} ";
		if ( $conexion->query( $sql ) ) {
			$respuesta = array( "response" => 1, "msg" => "Guardado correctamente", "lstComentarios" => $this->lstComentarios( $idTema ) );
		}else{
			$respuesta = array( "response" => 0, "msg" => $conexion->error );
		}

		return $respuesta;
	}
}
?>










