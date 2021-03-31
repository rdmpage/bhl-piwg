<?php

//----------------------------------------------------------------------------------------

$filename = 'InvalidDOIs.txt';

$headings = array();

$row_count = 0;

$file = @fopen($filename, "r") or die("couldn't open $filename");
		
$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$row = fgetcsv(
		$file_handle, 
		0, 
		"\t" 
		);
		
	if (is_array($row))	
	{
		
		$doi = $row[0];
	
		echo 'UPDATE rdmp_reference SET doi=NULL WHERE doi="' . $doi . '";' . "\n";
	}

}

?>

