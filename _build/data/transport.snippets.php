<?php
/**
* FormSave
*
* Copyright 2010-11 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
*
* This file is part of FormSave, a simple commenting component for MODx Revolution.
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
* @package formsave
*/
/**
* @package formsave
* @subpackage build
*/
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'FormSave',
    'description' => 'A FormIt hook that allows you to save any form and export them to a variety of formats.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.formsave.php'),
));

return $snippets;