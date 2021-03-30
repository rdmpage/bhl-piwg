<?php

error_reporting(E_ALL);

//----------------------------------------------------------------------------------------
function reference_to_ris($reference)
{
	$field_to_ris_key = array(
		'title' 	=> 'TI',
		'alternativetitle' 	=> 'TT',
		'journal' 	=> 'JO',
		'secondary_title' 	=> 'JO',
		'book' 		=> 'T2',
		'issn' 		=> 'SN',
		'volume' 	=> 'VL',
		'issue' 	=> 'IS',
		'spage' 	=> 'SP',
		'epage' 	=> 'EP',
		'year' 		=> 'Y1',
		'date'		=> 'PY',
		'abstract'	=> 'N2',
		'url'		=> 'UR',
		'pdf'		=> 'L1',
		'doi'		=> 'DO',
		'notes'		=> 'N1',
		'oai'		=> 'ID',

		'publisher'	=> 'PB',
		'publoc'	=> 'PP',
		
		'publisher_id' => 'ID',
		
		'xml'		=> 'XM', // I made this up
		
		// correspondence
		
		);
		
	$ris = '';
	
	switch ($reference->genre)
	{
		case 'article':
			$ris .= "TY  - JOUR\n";
			break;

		case 'chapter':
			$ris .= "TY  - CHAP\n";
			break;

		case 'book':
			$ris .= "TY  - BOOK\n";
			break;

		default:
			$ris .= "TY  - GEN\n";
			break;
	}

	//$ris .= "ID  - " . $result->fields['guid'] . "\n";
	
	// Need journal to be output early as some pasring routines that egnerate BibJson
	// assume journal alreday defined by the time we read pages, etc.
	if (isset($reference->journal))
	{
		$ris .= 'JO  - ' . $reference->journal . "\n";
	}

	foreach ($reference as $k => $v)
	{
		switch ($k)
		{
			// eat this
			case 'journal':
				break;
				
			case 'authors':
				foreach ($v as $a)
				{
					if ($a != '')
					{
						$a = str_replace('*', '', $a);
						$a = trim(preg_replace('/\s\s+/u', ' ', $a));						
						$ris .= "AU  - " . $a ."\n";
					}
				}
				break;

			case 'alternativeauthors':
				foreach ($v as $a)
				{
					if ($a != '')
					{
						$a = str_replace('*', '', $a);
						$a = trim(preg_replace('/\s\s+/u', ' ', $a));						
						$ris .= "AT  - " . $a ."\n";
					}
				}
				break;
				
			case 'editors':
				foreach ($v as $a)
				{
					if ($a != '')
					{
						$ris .= "ED  - " . $a ."\n";
					}
				}
				break;				
				
			case 'date':
				//echo "|$v|\n";
				if (preg_match("/^(?<year>[0-9]{4})\-(?<month>[0-9]{2})\-(?<day>[0-9]{2})$/", $v, $matches))
				{
					//print_r($matches);
					$ris .= "PY  - " . $matches['year'] . "/" . $matches['month'] . "/" . $matches['day']  . "/" . "\n";
					//$ris .= "Y1  - " . $matches['year'] . "\n";
				}
				else
				{
					$ris .= "Y1  - " . $v . "\n";
				}		
				break;
				
			case 'handle':
				$ris .= 'UR  - https://hdl.handle.net/' . $v . "\n";
				break;
				
			/*
			case 'jstor':
				$ris .= 'UR  - https://hdl.handle.net/' . $v . "\n";
				break;
			*/

			case 'bhl':
				$ris .= 'UR  - https://www.biodiversitylibrary.org/page/' . $v . "\n";
				break;
				
				
			default:
				if ($v != '')
				{
					if (isset($field_to_ris_key[$k]))
					{
						$ris .= $field_to_ris_key[$k] . "  - " . $v . "\n";
					}
				}
				break;
		}
	}
	
	$ris .= "ER  - \n";
	$ris .= "\n";
	
	return $ris;
}

//----------------------------------------------------------------------------------------
// http://stackoverflow.com/a/5996888/9684
function translate_quoted($string) {
  $search  = array("\\t", "\\n", "\\r");
  $replace = array( "\t",  "\n",  "\r");
  return str_replace($search, $replace, $string);
}

//----------------------------------------------------------------------------------------



$headings = array();

$row_count = 0;

$filename = 'BHL Vol 1-4 Bulletin of The African Bird Club - Sheet1.tsv';
$filename = 'BHL Batch #2 Bulletin of The African Bird Club v.5 to v.24_no.1 - Sheet1.tsv';
$filename = '22.2.tsv';

$filename = 'BHL Bulletin of the British Museum (Natural History). Geology. Supplement. - Sheet1.tsv';
$filename = 'BHL Bulletin of the Natural History Museum (Natural History). Historical Series. - Sheet1.tsv';

$filename = 'The Naturalist Miscellany Article Data March 2021 - Sheet1.tsv';

$bhl_pages = array();


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	// $line = trim(fgets($file_handle));
	// $row = explode("\t",$line);
	
	$row = fgetcsv(
		$file_handle, 
		0, 
		"\t",
		translate_quoted('"') 
		);
	
	//print_r($row);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;	
			//print_r($headings);
			
			
		}
		else
		{
			$obj = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '')
				{
					$heading = $headings[$k];
					
					// translation...
					
					switch ($heading)
					{
						case 'ArticleTitle':
							$heading = 'title';
							break;

						case 'FullTitle':
							$heading = 'journal';
							break;

						case 'Year':
						case 'Volume':
							$heading = strtolower($heading);
							break;
							
						case 'Issue':
						case 'No.':
							$heading = 'issue';
							break;							

						case 'StartPageNo':
							$heading = 'spage';
							break;

						case 'EndPageNo':
							$heading = 'epage';
							break;
							

						case 'Authors':
							$heading = 'authors';
							break;

						case 'Date':
							$heading = 'date';
							break;

						// Naturalist's Miscc
						/*
						case 'StartPageBHLID':
							$heading = 'spage';
							break;
							
						case 'EndPageBHLID':
							$heading = 'epage';
							break;
						*/

					
						default:
							break;
					}
				
				
				
					$obj->{$heading} = $v;
				}
			}
		
			if (0)
			{
				print_r($obj);
			}
			
			// clean up
			
			
			$obj->genre = 'article';
			
			if (isset($obj->journal) && !isset($obj->issn))
			{
				switch($obj->journal)
				{
					case 'Bulletin of the British Museum (Natural History). Geology. Supplement.':
						$obj->issn = '0524-644X';
						break;

					case 'Bulletin of the Natural History Museum (Natural History). Historical Series.':
						$obj->issn = '0068-2306';
						break;

				
					default:
						break;
				}
			}
			


			

			
			if (isset($obj->authors))
			{
				$authorstring = $obj->authors;
				$authorstring = str_replace (' and ', ';', $authorstring);
				$obj->authors = explode(';', $authorstring);				
			}

			if (isset($obj->ArticleDate))
			{
				$dateTime = date_create_from_format('F d, Y', $obj->ArticleDate . ', ' . $obj->year);
				$obj->date = date_format($dateTime, 'Y-m-d');
			}


			if (isset($obj->date))
			{
				if (strlen($obj->date) == 7)
				{
					$obj->date .= '-00';
				}
				
				unset($obj->year);
			}
			
			// Which column is the BHL PageID
			$bhl_key = '';
			
			if (isset($obj->{'BHL URL: start page'}))
			{
				$bhl_key = 'BHL URL: start page';
			}

			if (isset($obj->StartPageID))
			{
				$bhl_key = 'StartPageID';
			}

			if (isset($obj->StartPageBHLID))
			{
				$bhl_key = 'StartPageBHLID';
			}
			

			if (isset($obj->{$bhl_key}))
			{
				$PageID = $obj->{$bhl_key};
				$PageID = preg_replace('/https?:\/\/(www.)?biodiversitylibrary.org\/page\//', '', $PageID);
				
				$obj->url = $obj->{$bhl_key};
				$obj->url = 'http://www.biodiversitylibrary.org/page/' . $PageID;
				
				if (!isset($bhl_pages[$PageID]))
				{
					$bhl_pages[$PageID] = array();
				}
				$bhl_pages[$PageID][] = $obj; 
				
			}
			
			// Nat Misc
			// we use PageIDs as page numbers,
			// will need to fix BioStor code to handle pages that are actually BHL Page IDs
			if (isset($obj->StartPageBHLID))
			{
				$obj->spage = $obj->StartPageBHLID;
				
				if (isset($obj->EndPageBHLID))
				{
					$obj->epage = $obj->EndPageBHLID;

				}
				
				$obj->journal = "The Naturalist's Miscellany";
			}
			
			
			
			//print_r($obj);
			
			
			$go = true;
			
			if (isset($obj->ArticleID))
			{
				$go = false;
			}
			
			if (isset($obj->BHLSegmentID))
			{
				$go = false;
			}
			
			
			if (isset($obj->{'BioStor ID'}))
			{
				$go = false;
			}
			
			if ($go)
			{
				echo reference_to_ris($obj);
			}
			
			
		}
	}	
	$row_count++;	
	
}	

// Check for articles that start on same BHL page

$duplicates = array();

foreach ($bhl_pages as $PageID => $articles)
{
	if (count($articles) > 1)
	{
		echo "Duplicates http://www.biodiversitylibrary.org/page/$PageID\n";
		
		$duplicates[] = $PageID;
		
		print_r($articles);
		
		$spages = array();
		
		foreach ($articles as $obj)
		{
			$spages[] = $obj->spage;
		}
		
		$spages = array_unique($spages);
		
		if (count($spages) > 1)
		{
			// problem
			
			foreach ($articles as $obj)
			{
				print_r($obj);
			}
			
		
		}
		
	
		
		echo "----------\n";
	}

}

print_r($duplicates);


