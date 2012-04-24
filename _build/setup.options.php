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
* Build the setup options form.
*
* @package defaultcomponent
* @subpackage build
*/
/* set some default values */
$output = '';
/* 

// Example from Quip by Shaun

$values = array(
    'emailsTo' => 'my@emailhere.com',
    'emailsFrom' => 'my@emailhere.com',
    'emailsReplyTo' => 'my@emailhere.com',
);
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $setting = $modx->getObject('modSystemSetting',array('key' => 'defaultcomponent.emailsTo'));
        if ($setting != null) { $values['emailsTo'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'defaultcomponent.emailsFrom'));
        if ($setting != null) { $values['emailsFrom'] = $setting->get('value'); }
        unset($setting);

        $setting = $modx->getObject('modSystemSetting',array('key' => 'defaultcomponent.emailsReplyTo'));
        if ($setting != null) { $values['emailsReplyTo'] = $setting->get('value'); }
        unset($setting);
    break;
    case xPDOTransport::ACTION_UNINSTALL: break;
}

$output = '<label for="defaultcomponent-emailsTo">Emails To:</label>
<input type="text" name="emailsTo" id="defaultcomponent-emailsTo" width="300" value="'.$values['emailsTo'].'" />
<br /><br />

<label for="defaultcomponent-emailsFrom">Emails From:</label>
<input type="text" name="emailsFrom" id="defaultcomponent-emailsFrom" width="300" value="'.$values['emailsFrom'].'" />
<br /><br />

<label for="defaultcomponent-emailsReplyTo">Emails Reply-To:</label>
<input type="text" name="emailsReplyTo" id="defaultcomponent-emailsReplyTo" width="300" value="'.$values['emailsReplyTo'].'" />';

*/

return $output;