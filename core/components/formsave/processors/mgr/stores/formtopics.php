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

$query = $modx->getOption('query', $scriptProperties, '');
        
$list = array();

$query = $modx->newQuery('fsForm', $query);
$query->groupby('topic');
$query->sortby('topic', 'ASC');
$forms = $modx->getCollection('fsForm', $query);

foreach ($forms as $form) {
    $list[] = array(
    	'topic' => $form->get('topic'),
    	'value' => $form->get('topic')
    );
}

array_unshift($list, array(
	'topic' => $modx->lexicon('formsave.all_forms'),
	'value' => '*'
));

return $this->outputArray($list, sizeof($list));