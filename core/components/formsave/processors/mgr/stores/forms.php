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
$offset = $modx->getOption('start', $_REQUEST, 0);
$limit = $modx->getOption('limit', $_REQUEST, 25); 

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

$totalForms = $modx->getCount('fsForm', $query);
$query->limit($limit, $offset);

$forms = $modx->getCollection('fsForm', $query);
foreach ($forms as $form) {
	$addArray = $form->toArray();
	
	$introText = '';
	foreach($addArray['data'] as $dataKey => $dataValue) {
		if (strlen($introText) < 100) {
			$introText .= '<b>'.$dataKey.'</b>: '.strip_tags($dataValue).'.&nbsp;&nbsp;';
		} else {
			break;
		}
	}
	
	$addArray['data_intro'] = $introText;
	
    $list[] = $addArray;
}

return $this->outputArray($list, $totalForms);