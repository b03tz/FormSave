<?php
/**
* defaultcomponent
*
* Copyright 2010-11 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
*
* This file is part of defaultComponent, a simple commenting component for MODx Revolution.
*
* defaultComponent is free software; you can redistribute it and/or modify it under the
* terms of the GNU General Public License as published by the Free Software
* Foundation; either version 2 of the License, or (at your option) any later
* version.
*
* defaultComponent is distributed in the hope that it will be useful, but WITHOUT ANY
* WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
* A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with
* defaultComponent; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
* Suite 330, Boston, MA 02111-1307 USA
*
* @package defaultcomponent
*/
/**
* Default defaultComponent Access Policies
*
* @package defaultcomponent
* @subpackage build
*/
function bld_policyFormatData($permissions) {
    $data = array();
    foreach ($permissions as $permission) {
        $data[$permission->get('name')] = true;
    }
    return $data;
}
$policies = array();

/*
// Example policy from Quip component by Shaun:

$policies[1]= $modx->newObject('modAccessPolicy');
$policies[1]->fromArray(array (
  'id' => 1,
  'name' => 'defaultComponentModeratorPolicy',
  'description' => 'A policy for moderating defaultComponent comments.',
  'parent' => 0,
  'class' => '',
  'lexicon' => 'defaultcomponent:permissions',
  'data' => '{"defaultcomponent.comment_approve":true,"defaultcomponent.comment_list":true,"defaultcomponent.comment_list_unapproved":true,"defaultcomponent.comment_remove":true,"defaultcomponent.comment_update":true,"defaultcomponent.thread_list":true,"defaultcomponent.thread_manage":true,"defaultcomponent.thread_remove":true,"defaultcomponent.thread_truncate":true,"defaultcomponent.thread_view":true,"defaultcomponent.thread_update":true}',
), '', true, true);
*/

return $policies;