<?php

//----------------------------------------------------------------------------------------
function check_doi($doi)
{
	$url = 'https://doi.org/' . $doi;
	
	//echo $url . "\n";


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);   

	$response = curl_exec($ch);
/*	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
*/
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];

	return $http_code;
}


//----------------------------------------------------------------------------------------


//echo check_doi('10.5962/bhl.part.4477');
//exit();

$filename = 'InvalidDOIs.txt';

$headings = array();

$good = array();

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
	
		//echo 'UPDATE rdmp_reference SET doi=NULL WHERE doi="' . $doi . '";' . "\n";
		
		echo $doi;
		
		$http_code = check_doi($doi);
		
		echo " $http_code\n";
		
		if ($http_code != 404)
		{
			$good[] = $doi;
		}
		
		// Give server a break every 10 items
		if (($row_count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
		
	}

}

print_r($good);

?>

