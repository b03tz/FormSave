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
* Resolves db changes
*
* @package defaultcomponent
* @subpackage build
*/


if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
			$modx =& $object->xpdo;
			$modelPath = $modx->getOption('formsave.core_path',null,$modx->getOption('core_path').'components/formsave/').'model/';
			$modx->addPackage('formsave',$modelPath);
			
			$manager = $modx->getManager();
			
			// Create the object container
			$manager->createObjectContainer('fsForm');

            break;
    }
}

return true;