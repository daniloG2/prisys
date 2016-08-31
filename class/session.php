<?php

/**
* Session
*/
class Session
{
	public function valid()
	{
		if ( isset( $_SESSION['login'] ) AND $_SESSION['login'] )
			return true;
		else
			return false;
	}

	public function getUser()
	{
		return $_SESSION['user'];
	}
}

?>