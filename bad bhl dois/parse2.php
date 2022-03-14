<?php


$headings = array();

/*
10.5962/bhl.part.4477
10.5962/bhl.part.10505
10.5962/bhl.part.11241
10.5962/bhl.part.11243
10.5962/bhl.part.14514
10.5962/bhl.part.15115
10.5962/bhl.part.15348
10.5962/bhl.part.18170
10.5962/bhl.part.20013
10.5962/bhl.part.24479
10.5962/bhl.part.28906
*/

$good = array(
'10.5962/bhl.part.4477',
'10.5962/bhl.part.10505',
'10.5962/bhl.part.11241',
'10.5962/bhl.part.11243',
'10.5962/bhl.part.14514',
'10.5962/bhl.part.15115',
'10.5962/bhl.part.15348',
'10.5962/bhl.part.18170',
'10.5962/bhl.part.20013',
'10.5962/bhl.part.24479',
'10.5962/bhl.part.28906',
    );
    
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
		
		if (in_array($doi, $good))
		{
			echo "-- $doi is good\n";		
		}
		else
		{
			echo 'UPDATE rdmp_reference SET doi=NULL WHERE doi="' . $doi . '";' . "\n";
		}

		
	}

}



?>

