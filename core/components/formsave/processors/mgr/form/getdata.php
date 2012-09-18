<?php

$id = (int) $_REQUEST['id'];
$formSave =& $modx->formsave;

$form = $modx->getObject('fsForm', $id);
$formArray = $form->toArray();

$output = '';
foreach($formArray['data'] as $key => $value) {
<<<<<<< HEAD
	if (is_array($value)) {
		$value = implode(', ', $value);
	}
=======
        if (is_array($value)) {
            $value = json_encode($value);
        }
>>>>>>> bb1a4e8605e0986c1aa986de6089cfcd2c616e07
	$output .= $formSave->getChunk('fs.datarow', array(
		'key' => $key,
		'value' => $value
	));
}

return $formSave->getChunk('fs.datawrapper', array_merge($formArray, array(
	'content' => $output
)));