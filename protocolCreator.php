<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Jonas Felix (jonas.felix@typo3.org)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
How To:
-------
1.) forge.typo3.org -> your projects -> issues

2.) select issues that are discussed (we use 'Target version: xyz' to determine the issues of the meeting)
-> tip change the subject in forge to '01 - intro' etc. to modified the order

3.) click "CSV" in the lower left corner

4.) save the export.csv in the same folder as the protocolCreator.php

5.) run it in the terminal: 'php protocolCreator.php' 

6.) open mail.txt / copy it to your mailprogramm + have fun


Columns:
--------
[0] => #, [1] => Status, [2] => Project, [3] => Tracker, [4] => Priority, [5] => Subject,
[6] => Assignee, [7] => Category, [8] => Target version, [9] => Author, [10] => Start date,
[11] => Due date, [12] => % Done, [13] => Estimated time, [14] => Parent task,
[15] => Created, [16] => Updated, [17] => Reporter, [18] => Description
**/


// get issuses from 'export.csv' into an array
$issues = array();
$exportRes = fopen('export.csv', 'r');
while(($issue = fgetcsv($exportRes, 0, ',', '"')) !== FALSE) {
	$issues[$issue[0]] = $issue;
}

// define formating strings
$formatLine = str_repeat('#', 72).chr(10);
$formatSub = str_repeat('.', 72).chr(10);

// drop columns row
$columns = array_shift($issues);


// insert agenda
$content = '';
$content .= $formatLine;
$content .= 'AGENDA'.chr(10);
$content .= $formatLine;

foreach($issues as $issue) {
	$content .= $issue[0].' '.$issue[5].' http://forge.typo3.org/issues/'.$issue[0].chr(10);
}


// insert issue blocks
foreach($issues as $issue) {
	$content .= chr(10).chr(10).chr(10);
	$content .= $formatLine;
	$content .= $issue[0].', '.$issue[1].', '.preg_replace('/^.*(\(.*\))$/', '$1', $issue[2]).chr(10);
	$content .= wordwrap($issue[5], 72).chr(10);
	$content .= $issue[16].', '.$issue[6].', http://forge.typo3.org/issues/'.$issue[0].chr(10);
	$content .= $formatSub;
	$content .= wordwrap($issue[18], 72);
}

// put data into file
file_put_contents('mail.txt', $content);
