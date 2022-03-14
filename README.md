# BHL Persistent Identifier Working Group

Code related to BHL PIWG.

## BHL BioStor harvesting

See https://github.com/gbhl/bhl-us/tree/master/BHLBioStorHarvest for code BHL uses to harvest articles from BioStor.

## List of DOIs for a journal in CrossRef

https://data.crossref.org/depositorreport?pubid=J297275 where J means journal and 297275 is the CrossRef id for that journal.

## Match BHL DOIs to other DOI sources

`match.php` takes a list of BHL DOI, resolves them in Crossref to get metadata, then does an OpenURL search of local database to see if we match other DOIs. Matches are output as a TSV file.