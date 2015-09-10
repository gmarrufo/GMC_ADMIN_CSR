<?php

// GMC - 12/03/08 - Domestic Vs. International 3rd Phase
function mssql_escape_string($string_to_escape)
{
	$replaced_string = str_replace("'","''",$string_to_escape);
	return $replaced_string;
}

?>
