<?php

// Update a BioStor record

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/ris.php');

$to_update = array();

//----------------------------------------------------------------------------------------
// post
function post($url, $data =  null)
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  		

	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
		
	curl_close($ch);
	
	return $response;
}

//----------------------------------------------------------------------------------------


function biostor_update($reference)
{
	global $to_update;

	
	// convert to BioStor internal structure
	
	$parameters = array();
	
	$obj = new stdclass;
	$obj->PageID = 0; // ignore BHL PageID for now
	
	foreach ($reference as $k => $v)
	{
		switch ($k)
		{
			case 'title':
			case 'volume':
			case 'issue':
			case 'spage':
			case 'epage':
			case 'doi':
			case 'secondary_title':
			case 'genre':
			case 'date':
			case 'year':
			case 'doi':
				$parameters[$k] = $v;
				break;
			
			case 'url':
				if (preg_match('/https?:\/\/biostor.org\/reference\/(?<id>\d+)/', $v, $m))
				{
					$parameters['reference_id'] = $m['id'];
				}
				break;
				
			case 'authors':
				$parameters[$k] = join("\n", $v);
				break;
			
			default:
				break;
		}	
	
	}
	
	if (!isset($parameters['reference_id']))
	{
		echo "\n*** No BioStor id ***\n\n";
		print_r($reference);
		exit();
	
	}
	
	$to_update[] = $parameters['reference_id'];
	
	$parameters['update'] = 'true';
	
	print_r($parameters);
	
	// echo http_build_query($parameters) . "\n";
	
	$url = 'http://direct.biostor.org/update.php';
	
	$response = post($url, $parameters);
	
	echo $response . "\n";
	
    $rand = rand(1000000, 3000000);
    echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
    usleep($rand);


	
	
}


$filename = '';
if ($argc < 2)
{
	echo "Usage: update.php <RIS file>\n";
	exit(1);
}
else
{
	$filename = $argv[1];
}


$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_ris_file($filename, 'biostor_update');

print_r($to_update);

echo "\n" . '$ids=array(';

foreach ($to_update as $id)
{
	echo "\n$id,";
}


echo "\n);\n";



?>