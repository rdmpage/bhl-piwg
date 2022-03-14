<?php

require_once('compare.php');

//----------------------------------------------------------------------------------------
function get($url, $user_agent='', $content_type = '')
{	
	$data = null;

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);

	if ($content_type != '')
	{
		
		$opts[CURLOPT_HTTPHEADER] = array(
			"Accept: " . $content_type, 
			"User-agent: Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405" 
		);
		
	}
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return $data;
}

//----------------------------------------------------------------------------------------
function find_doi($openurl)
{
	$doi = '';
	
	$url = 'http://localhost/~rpage/microcitation/www/api_openurl.php?' . $openurl;
	
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	//echo $url . "\n";
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);
	
	return json_decode($data);

			
}	


//----------------------------------------------------------------------------------------
// Fetch CrossRef DOI
function get_work($doi)
{
	$obj = null;
	
	$url = 'https://api.crossref.org/v1/works/' . $doi;
	
	$json = get($url);
	
	if ($json != '')
	{
		$obj = json_decode($json);
	}
	return $obj;
}


$dois=array(
'10.5962/bhl.part.2956',
'10.5962/bhl.part.2957',
'10.5962/bhl.part.2961',
'10.5962/bhl.part.2958',
'10.5962/bhl.part.2962',
'10.5962/bhl.part.2963',
'10.5962/bhl.part.2964',
'10.5962/bhl.part.2960',
'10.5962/bhl.part.2959',
'10.5962/bhl.part.2969',
'10.5962/bhl.part.2974',
'10.5962/bhl.part.2966',
'10.5962/bhl.part.2973',
'10.5962/bhl.part.2968',
'10.5962/bhl.part.2972',
'10.5962/bhl.part.2965',
'10.5962/bhl.part.2971',
'10.5962/bhl.part.2967',
'10.5962/bhl.part.2970',
'10.5962/bhl.part.2977',
'10.5962/bhl.part.2979',
'10.5962/bhl.part.2978',
'10.5962/bhl.part.2975',
'10.5962/bhl.part.2976',
'10.5962/bhl.part.2980',
'10.5962/bhl.part.2982',
'10.5962/bhl.part.2983',
'10.5962/bhl.part.4565',
'10.5962/bhl.part.4573',
'10.5962/bhl.part.4568',
'10.5962/bhl.part.4572',
'10.5962/bhl.part.4566',
'10.5962/bhl.part.4575',
'10.5962/bhl.part.4567',
'10.5962/bhl.part.4570',
'10.5962/bhl.part.4571',
'10.5962/bhl.part.4574',
'10.5962/bhl.part.4569',
'10.5962/bhl.part.4576',
'10.5962/bhl.part.4581',
'10.5962/bhl.part.4578',
'10.5962/bhl.part.4579',
'10.5962/bhl.part.4577',
'10.5962/bhl.part.4580',
'10.5962/bhl.part.4583',
'10.5962/bhl.part.4657',
'10.5962/bhl.part.4655',
'10.5962/bhl.part.4656',
'10.5962/bhl.part.4665',
'10.5962/bhl.part.4663',
'10.5962/bhl.part.4660',
'10.5962/bhl.part.4661',
'10.5962/bhl.part.4666',
'10.5962/bhl.part.4658',
'10.5962/bhl.part.4664',
'10.5962/bhl.part.4659',
'10.5962/bhl.part.4662',
'10.5962/bhl.part.4669',
'10.5962/bhl.part.4667',
'10.5962/bhl.part.4673',
'10.5962/bhl.part.4670',
'10.5962/bhl.part.4672',
'10.5962/bhl.part.4671',
'10.5962/bhl.part.4674',
'10.5962/bhl.part.4668',
'10.5962/bhl.part.4676',
'10.5962/bhl.part.4675',
'10.5962/bhl.part.4679',
'10.5962/bhl.part.4677',
'10.5962/bhl.part.4678',
'10.5962/bhl.part.4680',
'10.5962/bhl.part.4681',
'10.5962/bhl.part.4685',
'10.5962/bhl.part.4684',
'10.5962/bhl.part.4682',
'10.5962/bhl.part.4683',
'10.5962/bhl.part.4687',
'10.5962/bhl.part.4692',
'10.5962/bhl.part.4693',
'10.5962/bhl.part.4694',
'10.5962/bhl.part.4689',
'10.5962/bhl.part.4690',
'10.5962/bhl.part.4686',
'10.5962/bhl.part.4688',
'10.5962/bhl.part.4691',
'10.5962/bhl.part.4698',
'10.5962/bhl.part.4697',
'10.5962/bhl.part.4696',
'10.5962/bhl.part.4695',
'10.5962/bhl.part.5602',
'10.5962/bhl.part.5604',
'10.5962/bhl.part.5603',
'10.5962/bhl.part.5601',
'10.5962/bhl.part.5605',
'10.5962/bhl.part.5612',
'10.5962/bhl.part.5609',
'10.5962/bhl.part.5611',
'10.5962/bhl.part.5606',
'10.5962/bhl.part.5608',
'10.5962/bhl.part.5610',
'10.5962/bhl.part.5613',
'10.5962/bhl.part.5607',
'10.5962/bhl.part.5618',
'10.5962/bhl.part.5614',
'10.5962/bhl.part.5615',
'10.5962/bhl.part.5617',
'10.5962/bhl.part.5616',
'10.5962/bhl.part.8619',
'10.5962/bhl.part.8621',
'10.5962/bhl.part.8620',
'10.5962/bhl.part.8622',
'10.5962/bhl.part.8617',
'10.5962/bhl.part.8616',
'10.5962/bhl.part.8618',
'10.5962/bhl.part.8623',
'10.5962/bhl.part.9083',
'10.5962/bhl.part.9087',
'10.5962/bhl.part.9088',
'10.5962/bhl.part.9084',
'10.5962/bhl.part.9086',
'10.5962/bhl.part.9090',
'10.5962/bhl.part.9089',
'10.5962/bhl.part.9085',
'10.5962/bhl.part.11420',
'10.5962/bhl.part.11421',
'10.5962/bhl.part.11425',
'10.5962/bhl.part.11427',
'10.5962/bhl.part.11426',
'10.5962/bhl.part.11424',
'10.5962/bhl.part.11423',
'10.5962/bhl.part.11422',
'10.5962/bhl.part.13498',
'10.5962/bhl.part.13497',
'10.5962/bhl.part.13499',
'10.5962/bhl.part.13500',
'10.5962/bhl.part.13507',
'10.5962/bhl.part.13504',
'10.5962/bhl.part.13503',
'10.5962/bhl.part.13506',
'10.5962/bhl.part.13505',
'10.5962/bhl.part.13502',
'10.5962/bhl.part.13509',
'10.5962/bhl.part.13501',
'10.5962/bhl.part.13508',
'10.5962/bhl.part.13510',
'10.5962/bhl.part.13511',
'10.5962/bhl.part.13512',
'10.5962/bhl.part.13515',
'10.5962/bhl.part.13513',
'10.5962/bhl.part.13514',
'10.5962/bhl.part.13516',
'10.5962/bhl.part.2981',
'10.5962/bhl.part.16267',
'10.5962/bhl.part.16270',
'10.5962/bhl.part.16268',
'10.5962/bhl.part.16274',
'10.5962/bhl.part.16273',
'10.5962/bhl.part.16269',
'10.5962/bhl.part.16272',
'10.5962/bhl.part.16271',
'10.5962/bhl.part.16275',
'10.5962/bhl.part.16276',
'10.5962/bhl.part.16282',
'10.5962/bhl.part.16277',
'10.5962/bhl.part.16278',
'10.5962/bhl.part.16284',
'10.5962/bhl.part.16280',
'10.5962/bhl.part.16281',
'10.5962/bhl.part.16283',
'10.5962/bhl.part.16279',
'10.5962/bhl.part.16285',
'10.5962/bhl.part.16287',
'10.5962/bhl.part.16288',
'10.5962/bhl.part.16286',
'10.5962/bhl.part.16289',
'10.5962/bhl.part.16290',
'10.5962/bhl.part.16294',
'10.5962/bhl.part.16293',
'10.5962/bhl.part.16291',
'10.5962/bhl.part.16292',
'10.5962/bhl.part.16295',
'10.5962/bhl.part.16297',
'10.5962/bhl.part.16298',
'10.5962/bhl.part.16296',
'10.5962/bhl.part.16301',
'10.5962/bhl.part.16302',
'10.5962/bhl.part.16299',
'10.5962/bhl.part.16300',
'10.5962/bhl.part.16305',
'10.5962/bhl.part.16307',
'10.5962/bhl.part.16308',
'10.5962/bhl.part.16312',
'10.5962/bhl.part.16310',
'10.5962/bhl.part.16309',
'10.5962/bhl.part.16311',
'10.5962/bhl.part.16303',
'10.5962/bhl.part.16304',
'10.5962/bhl.part.16306',
'10.5962/bhl.part.16314',
'10.5962/bhl.part.16313',
'10.5962/bhl.part.16844',
'10.5962/bhl.part.16845',
'10.5962/bhl.part.16852',
'10.5962/bhl.part.16849',
'10.5962/bhl.part.16851',
'10.5962/bhl.part.16846',
'10.5962/bhl.part.16853',
'10.5962/bhl.part.16848',
'10.5962/bhl.part.16847',
'10.5962/bhl.part.16850',
'10.5962/bhl.part.17839',
'10.5962/bhl.part.17838',
'10.5962/bhl.part.17841',
'10.5962/bhl.part.17840',
'10.5962/bhl.part.19234',
'10.5962/bhl.part.19231',
'10.5962/bhl.part.19233',
'10.5962/bhl.part.19230',
'10.5962/bhl.part.19232',
'10.5962/bhl.part.19235',
'10.5962/bhl.part.19239',
'10.5962/bhl.part.19237',
'10.5962/bhl.part.19238',
'10.5962/bhl.part.19240',
'10.5962/bhl.part.19241',
'10.5962/bhl.part.19245',
'10.5962/bhl.part.19243',
'10.5962/bhl.part.19244',
'10.5962/bhl.part.19242',
'10.5962/bhl.part.21827',
'10.5962/bhl.part.21828',
'10.5962/bhl.part.21829',
'10.5962/bhl.part.21830',
'10.5962/bhl.part.21831',
'10.5962/bhl.part.22258',
'10.5962/bhl.part.22257',
'10.5962/bhl.part.22261',
'10.5962/bhl.part.22260',
'10.5962/bhl.part.22262',
'10.5962/bhl.part.22259',
'10.5962/bhl.part.22263',
'10.5962/bhl.part.22265',
'10.5962/bhl.part.22264',
'10.5962/bhl.part.24164',
'10.5962/bhl.part.24167',
'10.5962/bhl.part.24166',
'10.5962/bhl.part.24171',
'10.5962/bhl.part.24168',
'10.5962/bhl.part.24169',
'10.5962/bhl.part.24170',
'10.5962/bhl.part.24173',
'10.5962/bhl.part.24177',
'10.5962/bhl.part.24172',
'10.5962/bhl.part.24178',
'10.5962/bhl.part.24179',
'10.5962/bhl.part.24174',
'10.5962/bhl.part.24176',
'10.5962/bhl.part.24175',
'10.5962/bhl.part.24184',
'10.5962/bhl.part.24183',
'10.5962/bhl.part.24186',
'10.5962/bhl.part.24185',
'10.5962/bhl.part.24180',
'10.5962/bhl.part.24181',
'10.5962/bhl.part.24182',
'10.5962/bhl.part.24165',
'10.5962/bhl.part.26098',
'10.5962/bhl.part.26097',
'10.5962/bhl.part.26096',
'10.5962/bhl.part.26802',
'10.5962/bhl.part.26803',
'10.5962/bhl.part.26811',
'10.5962/bhl.part.26808',
'10.5962/bhl.part.26805',
'10.5962/bhl.part.26806',
'10.5962/bhl.part.26804',
'10.5962/bhl.part.26807',
'10.5962/bhl.part.26813',
'10.5962/bhl.part.26809',
'10.5962/bhl.part.26812',
'10.5962/bhl.part.26810',
'10.5962/bhl.part.29070',
'10.5962/bhl.part.29068',
'10.5962/bhl.part.29069',
'10.5962/bhl.part.29071',
'10.5962/bhl.part.29500',
'10.5962/bhl.part.29503',
'10.5962/bhl.part.29501',
'10.5962/bhl.part.29502',
'10.5962/bhl.part.29505',
'10.5962/bhl.part.29504',
'10.5962/bhl.part.29615',
'10.5962/bhl.part.29616',
'10.5962/bhl.part.29614',
'10.5962/bhl.part.29621',
'10.5962/bhl.part.29622',
'10.5962/bhl.part.29620',
'10.5962/bhl.part.29617',
'10.5962/bhl.part.29619',
'10.5962/bhl.part.29618',
'10.5962/bhl.part.29624',
'10.5962/bhl.part.29623',

);

echo "BHL DOI\tOTHER DOI\tSCORE\tBHL TITLE\tOTHER TITLE\n";

$count = 1;

foreach ($dois as $doi)
{
	$result = new stdclass;
	$result->bhl_doi = $doi;

	$work = get_work($doi);
	
	// print_r($work);
	
	$title = '';
	if (is_array($work->message->title))
	{
		$title = $work->message->title[0];
	}
	else
	{
		$title = $work->message->title;		
	}
	
	$result->bhl_title = $title;
	
	$parameters = array();
	
	$parameters['pid'] = 'r.page@bio.gla.ac.uk';
	
	$issn = $work->message->ISSN[0];	
	$parameters['issn'] = $issn;
	
	$volume = $work->message->volume;
	
	// journal specific fixes
	switch ($issn)
	{
		case '0037-928X':
			if ($volume >= 1896)
			{
				$volume -= 1896;
				$volume++;
			}
			break;
	
		default:
			break;
	}
	$parameters['volume'] = $volume;
	
	$parts = explode("-", $work->message->page);
	$parameters['spage'] = $parts[0];
	
	// print_r($parameters);
	
	$obj = find_doi(http_build_query($parameters));
	
	//print_r($obj);
	
	$best_hit_doi = '';
	$best_hit_title = '';
	$best_hit_d = 0;
	
	$threshold = 0.8;
	
	if (count($obj->results > 0))
	{
		foreach ($obj->results as $hit)
		{		
			if (isset($hit->doi))
			{
				$d = compare_common_subsequence($title, $hit->title, false);	

				//print_r($d);
			
				if ($d->normalised[1] > $threshold)
				{
					if ($d->normalised[1] > $best_hit_d)
					{
						$best_hit_d = $d->normalised[1];
						$best_hit_doi = $hit->doi;
						$best_hit_title = $hit->title;
					}
				}
			}
			
		}
	
	}
	
	
	
	$result->external_doi 		= $best_hit_doi;
	$result->external_title  	= $best_hit_title;
	$result->external_distance	= $best_hit_d;
	
	//print_r($result);
	
	$row = array();
	
	$row[] = $result->bhl_doi;
	$row[] = $result->external_doi;
	
	$row[] = round($result->external_distance,2);	

	$row[] = $result->bhl_title;
	$row[] = $result->external_title;

	
	echo join("\t", $row) . "\n";
	
	// Give server a break every 10 items
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		//echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
		usleep($rand);
	}
	
	
	if ($count == 10)
	{
		//exit();
	}
	
}

?>

