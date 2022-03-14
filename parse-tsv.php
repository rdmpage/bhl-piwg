<?php

// Parse a TSV file and output RIS.



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

$filename = 'BHL A specimen of the botany of New Holland - Sheet1.tsv';

$filename = 'BHL Zoology of New Holland - Sheet1.tsv';

$filename = 'BHL Gould A Monograph of the Macropodidae - Sheet1.tsv';
$filename = 'BHL Gould The Mammals of Australia - Sheet1.tsv';
$filename = 'BHL Gould The Birds of Australia - Sheet1.tsv';

// BM(NH)
$filename = 'BHL Bulletin of the British Museum (Natural History). Geology. - Sheet1.tsv';
$filename = 'BHL Bulletin of the Natural History Museum. Botany series. - Sheet1.tsv';
$filename = 'BHL Bulletin of the British Museum (Natural History). Entomology. Supplement. - Sheet1.tsv';

$filename = 'BHL Bulletin of the Natural History Museum. Geology series. - Sheet1-2.tsv';
$filename = 'BHL Bulletin of the British Museum (Natural History). Mineralogy. - Sheet1.tsv';
$filename = 'BHL Bulletin of the British Museum (Natural History). Zoology - Sheet1.tsv';

$filename = 'BHL The mammals of Australia (Krefft, Scott & Forde) - Sheet1.tsv';

$bhl_pages = array();

//----------------------------------------------------------------------------------------
// Vital to decide whether we are going to update existing records or add new ones
// If new ones, set mode to "add" and run ~/Dropbox/Development/import-html.php 
// on the RIS file to add to BioStor
//
// If updating existing records (e.g., adding authors) then mode to "update" and 
// run update.php on RIS file to update existing records

$mode = 'update'; // Update existing records
$mode = 'add'; // Add new records


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
			
			// print_r($headings);
			
			
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
						case 'Title':
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
						case 'Author':
							$heading = 'authors';
							break;

						case 'Date':
							$heading = 'date';
							break;

						case 'VolumeDate':
							$heading = 'year';
							break;

						case 'DOI':
							$heading = 'doi';
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
				
				
				
					$obj->{$heading} = trim($v);
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

					case 'Bulletin of the British Museum (Natural History). Geology.':
						$obj->issn = '0007-1471';
						break;

					case 'Bulletin of the Natural History Museum. Botany series.':
						$obj->issn = '0968-0446';
						break;
						
					case 'Bulletin of the British Museum (Natural History). Entomology. Supplement.':
						$obj->issn = '0007-1501';
						break;
						
					case 'Bulletin of the Natural History Museum. Geology series.':
						$obj->issn = '0968-0462';
						break;
						
					case 'Bulletin of the British Museum (Natural History). Mineralogy.':
						$obj->issn = '0007-148X';
						break;
						
					case 'Bulletin of the British Museum (Natural History). Zoology.':
						$obj->issn = '0007-1498';
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
			
				if (preg_match('/[A-Z]\w+\s+\d+/', $obj->ArticleDate))
				{			
					$dateTime = date_create_from_format('F d, Y', $obj->ArticleDate . ', ' . $obj->year);
					$obj->date = date_format($dateTime, 'Y-m-d');
				}

				if (preg_match('/\d+\s+[A-Z]\w+/', $obj->ArticleDate))
				{			
					$dateTime = date_create_from_format('d F, Y', $obj->ArticleDate . ', ' . $obj->year);
					$obj->date = date_format($dateTime, 'Y-m-d');
				}

				if (preg_match('/^[0-9]{4}$/', $obj->ArticleDate))
				{
					$obj->year = $obj->ArticleDate;
				}

				if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $obj->ArticleDate))
				{
					$obj->date = $obj->ArticleDate;
				}

				if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $obj->ArticleDate))
				{
					$obj->date = $obj->ArticleDate . '-00';
				}
			
			}

			if (isset($obj->{'Article Date'}))
			{
				if (preg_match('/[A-Z]\w+\s+\d+/', $obj->{'Article Date'}))
				{			
					$dateTime = date_create_from_format('F d, Y', $obj->{'Article Date'} . ', ' . $obj->year);
					$obj->date = date_format($dateTime, 'Y-m-d');
				}
				
				if (preg_match('/^[A-Z]\w+$/', $obj->{'Article Date'}))
				{			
					$dateTime = date_create_from_format('F, Y', $obj->{'Article Date'} . ', ' . $obj->year);
					$obj->date = date_format($dateTime, 'Y-m-00');
				}
				
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
				$obj->journal = "A specimen of the botany of New Holland";
				$obj->journal = "Zoology of New Holland";
			}
			
			// Gould
			if (isset($obj->StartPageBHLURL))
			{
				$obj->spage = $obj->StartPageBHLURL;
				$obj->spage = str_replace('https://www.biodiversitylibrary.org/page/', '', $obj->spage );
				$obj->spage = str_replace('https://wwwbiodiversitylibraryorg/page/', '', $obj->spage );

				$obj->url = $obj->StartPageBHLURL;
				$obj->url = str_replace('wwwbiodiversitylibraryorg', 'www.biodiversitylibrary.org', $obj->url);
				$obj->url = str_replace('https', 'http', $obj->url);
			
				if (isset($obj->EndPageBHLURL))
				{
					$obj->epage = $obj->EndPageBHLURL;
					
					$obj->epage = str_replace('https://www.biodiversitylibrary.org/page/', '', $obj->epage );
					$obj->epage = str_replace('https://wwwbiodiversitylibraryorg/page/', '', $obj->epage );

				}
				
				// $obj->journal = "A monograph of the Macropodidae, or family of kangaroos";
				// $obj->volume = 1; // fake but need this for BioStor
				
				$obj->journal = "The mammals of Australia";
				$obj->journal = "The birds of Australia";
				$obj->journal = "The mammals of Australia";
			}
			
			
			// BM
			if (isset($obj->StartPageURL))
			{
				$obj->url = $obj->StartPageURL;
				$obj->url = str_replace('https', 'http', $obj->url);
			
			}
			
			
			
			//print_r($obj);
			
			if ($mode == 'add') // by default add everything
			{
				$go = true;
			}
			
			if ($mode == 'update') // by default don't update unless we have a BioStor ID
			{
				$go = false;
			}
			
			if (isset($obj->ArticleID))
			{
				$go = false;
			}
			
			if (isset($obj->BHLSegmentID))
			{
				$go = false;
			}
			
			if (!isset($obj->volume))
			{
				$obj->volume = 1;
			}
			
			// If we are updating then make sure we have access to the BioStor id,
			// if we are adding then suppress records that already exist
			if (isset($obj->{'BioStor ID'}))
			{
				if ($mode == 'update')
				{
					$obj->url = 'https://biostor.org/reference/' . $obj->{'BioStor ID'};
					$go = true;
				}
				if ($mode == 'add')
				{
					$go = false;
				}
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


