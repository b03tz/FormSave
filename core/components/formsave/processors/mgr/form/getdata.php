<?php

$id = (int) $_REQUEST['id'];
$formSave =& $modx->formsave;

$form = $modx->getObject('fsForm', $id);
$formArray = $form->toArray();

$output = '';
foreach($formArray['data'] as $key => $value) {
	if (is_array($value)) {
		$value = implode(', ', $value);
	}
	$output .= $formSave->getChunk('fs.datarow', array(
		'key' => $key,
		'value' => $value
	));
}

return $formSave->getChunk('fs.datawrapper', array_merge($formArray, array(
	'content' => $output
)));