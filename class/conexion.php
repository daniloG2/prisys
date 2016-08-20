<?php 
/**
* CONEXION
*/
function Conexion()
{
	$host = "localhost";
	$user = "root";
	$pass = "root";
	$db   = "privado_db";

	$con = new mysqli($host, $user, $pass, $db) or die ('No se pudo conectar a la BD');
	$con->set_charset('utf8');

	return $con;
}
?>