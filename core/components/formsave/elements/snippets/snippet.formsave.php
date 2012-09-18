<?php

// Load the FormSave class
$formSave = $modx->getService('formsave','FormSave', $modx->getOption('formsave.core_path', null, $modx->getOption('core_path').'components/formsave/').'model/formsave/', array());
if (!($formSave instanceof FormSave)) return '';

$formit =& $hook->formit;
$formValues = $hook->getValues();
$formTopic = $modx->getOption('fsFormTopic', $formit->config, 'form');
$formFields = $modx->getOption('fsFormFields', $formit->config, false);
$formPublished = (int) $modx->getOption('fsFormPublished', $formit->config, 1);

if ($formFields !== false) {
	$formFields = explode(',', $formFields);
	foreach($formFields as $key => $value) {
		$formFields[$key] = trim($value);
	}
}

// Create new form object
$newForm = $modx->newObject('fsForm');

// Build the data array
$dataArray = array();
if ($formFields === false) {
	$dataArray = $hook->getValues();
} else {
	$values = $hook->getValues();
	foreach($formFields as $field) {
		if (!isset($values[$field])) {
			// Add empty field
			$dataArray[$field] = '';
			continue;
		}
		
		$dataArray[$field] = $values[$field];
	}
}

// Fill the database object
$newForm->fromArray(array(
	'topic' => $formTopic,
	'time' => time(),
	'published' => $formPublished,
	'data' => $dataArray,
	'ip' => $_SERVER['REMOTE_ADDR']
));

// Save the form
$newForm->save();

// Set the form in FormIt so you can access it in any hook through $form->formSave
$formit->formSave = $newForm;

return true;