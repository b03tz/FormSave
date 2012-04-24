<?php

// Return the config params for this template
return array(
	'method' => 'download',							// Send the file as a download, you can also put "screen" which will just echo the output on the screen
	'filename' => 'export_'.date('Ymd').'.csv',		// File name for the download
	'mimetype' => 'text/csv',						// Mimetype, leave empty if you don't know what to enter here
	'replaceInput' => array("\n"),						// Replace a set of characters with the set in replaceOutput (only used for key / values)
	'replaceOutput' => array(""),						// Replace the characters from replaceInput with these characters (only used for key / values)
	'helpText' => $modx->lexicon('formsave.help_csv_semicolon'), // Help text to display next to the template field
	'fields' => array() // Fields to export (use the keynames). Empty array means all fields
);