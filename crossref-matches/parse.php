<?php

$headings = array();

$row_count = 0;

$filename = "SegmentsWithoutDOIs20220214-Final.txt";


function init_html()
{
	$html = '<html>
	<style>
	body { font-family:sans-serif; }
	td {  border-bottom:1px solid black; white-space:wrap;}
	</style>
	<body>';

	$html .= '<table cellpadding="2" cellspacing="0" width="100%">';

	$html .= '<thead>
	<tr>
	<th>Segment ID</th>
	<th>DOI</th>
	<th>Titles</th>
	<th>Authors</th>
	<th>Dates</th>
	</tr>
	</thead>';

	$html .= '<tbody>';
	
	return $html;

}

function end_html()
{
	$html = '</tbody>
	</table>
	</body>
	</html>';	

	return $html;

}


$html = init_html();

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;		
		}
		else
		{
			$obj = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '')
				{
					$obj->{$headings[$k]} = $v;
				}
			}
		
			//print_r($obj);	
			
			$html .= '<tr>';
			
			
			$html .=   '<td>' . $obj->{'Segment ID'} . '</td>';
			$html .=   '<td>' . $obj->{'Crossref DOI'} . '</td>';
			
			// title
			$colour = 'white';
			if ($obj->{'SWG Title Score'} > 0.8)
			{
				$colour = "#00F900";
			}
			elseif ($obj->{'SWG Title Score'} > 0.7)
			{
				$colour = '#FFD479';
			}			
			$html .=   '<td style="background:' . $colour . '">' . $obj->{'Title'} . '<br/><br/>' . $obj->{'Crossref Title'} . '</td>';
							
			// author
			$colour = 'white';
			if ($obj->{'SWG Author Score'} > 0.8)
			{
				$colour = "#00F900";
			}
			elseif ($obj->{'SWG Title Score'} > 0.7)
			{
				$colour = '#FFD479';
			}			
			$html .=   '<td style="background:' . $colour . '">' . $obj->{'Authors'} . '<br/><br/>' . $obj->{'Crossref Authors'} . '</td>';
			
			// date
			$colour = 'white';
			if ($obj->{'SWG Author Score'} > 0.8)
			{
				$colour = "#00F900";
			}
			elseif ($obj->{'SWG Title Score'} > 0.7)
			{
				$colour = '#FFD479';
			}			
			$html .=  '<td style="background:' . $colour . '">' . $obj->{'Date'} . '<br/><br/>' . $obj->{'Crossref Date'} . '</td>';

			
			$html .=  '</tr>';
			
			$html .= "\n";
			
			
			
			
		}
	}	
	$row_count++;	
	
	if ($row_count % 1000 == 0)
	{
		$html .= end_html();
		
		file_put_contents($row_count . '.html', $html);
		
		$html = init_html();
	}
	
}	

$html .= end_html();
		
file_put_contents($row_count . '.html', $html);

?>
