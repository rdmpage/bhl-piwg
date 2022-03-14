<?php


$row_count = 0;

$filename = "SegmentsWithoutDOIs20220214-Final.txt";

$fp = fopen('file.csv', 'w');


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		fputcsv($fp, $row);
	}	
	
	$row_count++;
	
	if ($row_count == 11)
	{
		fclose($fp);
		exit();
	}
	
}

fclose($fp);

?>
