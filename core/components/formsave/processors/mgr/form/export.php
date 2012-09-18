<?php
/**
 * FormSave
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of FormSave.
 *
 * FormSave is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * FormSave is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * FormSave; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package FormSave
 */
 
if (!$modx->user->isAuthenticated('mgr')) return $modx->error->failure($modx->lexicon('permission_denied'));

$topic = $modx->getOption('topic', $_REQUEST, false);
$startDate = $modx->getOption('startDate', $_REQUEST, '');
$endDate = $modx->getOption('endDate', $_REQUEST, '');
$template = $modx->getOption('template', $_REQUEST, 'csv');

$assetsPath = $this->modx->getOption('formsave.assets_path', null, $modx->getOption('assets_path').'components/formsave/');
$templateDir = $assetsPath.'mgr/templates/'.$template.'/';
$modx->formsave->config['chunksPath'] = $templateDir;

// Include the template config
if (is_file($templateDir.'config.php')) {
	$templateConfig = include $templateDir.'config.php';
} else {
	$templateConfig = array(
		'method' => 'screen',
		'replaceInput' => array(),
		'replaceOutput' => array(),
		'fields' => array()
	);
}

$templateConfig['replaceInput'] = $modx->getOption('replaceInput', $templateConfig, array());
$templateConfig['replaceOutput'] = $modx->getOption('replaceOutput', $templateConfig, array());
$templateConfig['fields'] = $modx->getOption('fields', $templateConfig, array());

$formSave =& $modx->formsave;

$list = array();

$query = $modx->newQuery('fsForm');

if ($topic != false && $topic != '*') {
	$query->where(array('topic' => $topic));
}

if ($startDate != '') {
	$query->andCondition(array('time:>' => strtotime($startDate.' 00:00:00')));
}

if ($endDate != '') {
	$query->andCondition(array('time:<' => strtotime($endDate.' 23:59:59')));
}

$query->sortby('time', 'DESC');

$forms = $modx->getCollection('fsForm', $query);
$output = '';

foreach ($forms as $form) {
	$formData = array_merge($form->toArray(), $form->get('data'));
	$rowOutput = '';
	$count = 0;
	foreach($formData as $key => $value) {
		if ($key == 'data') {
			$count++;
			$separatorAdded = true;
			continue;
		}
		
		if (!empty($templateConfig['fields']) && !in_array($key, $templateConfig['fields'])) {
			$count++;
			$separatorAdded = true;
			continue;
		}
		
		$separatorAdded = false;
		if (is_array($value)) {
			$value = implode(',', $value);
		}
		
		$rowOutput .=  $formSave->getChunk('fs.rowdata', array(
			'key' => str_replace($templateConfig['replaceInput'], $templateConfig['replaceOutput'], $key),
			'value' => str_replace($templateConfig['replaceInput'], $templateConfig['replaceOutput'], html_entity_decode($value)),
			'formData' => $formData
		));
		
		$count++;
		if ($count < sizeof($formData)) {
			$separator = $formSave->getChunk('fs.rowdataseparator');
			$rowOutput .= $separator;
		}
	} 
	
	if ($separatorAdded) {
		$rowOutput = substr($rowOutput, 0, (strlen($rowOutput) - strlen($separator)));
	}

	$output .= $formSave->getChunk('fs.rowwrapper', array(
		'content' => $rowOutput,
		'formData' => $formData
	));
}

$output = $formSave->getChunk('fs.exportwrapper', array(
	'content' => $output,
	'formData' => $formData
));

switch($templateConfig['method']) {
	default:
	case 'screen':
		return $output;
		break;
	case 'download':
		$fileName = isset($templateConfig['filename']) ? $templateConfig['filename'] : 'download.txt';

		if (isset($templateConfig['mimetype']) && $templateConfig['mimetype'] != '') {
			header('Content-type: '.$templateConfig['mimetype']);
		}
		header('Content-disposition: attachment; filename="'.$fileName.'"');
		echo $output;
		exit();
		break;
}