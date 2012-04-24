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

$premiumFile = $modx->getOption('core_path').'components/formsave/premium.txt';

if (isset($_GET['downloadLink'])) { 
	// Start downloading the file and save it to the core package folder
	$packageFolder = $modx->getOption('core_path').'packages/';
	if (is_writable($packageFolder)) {
		$downloadUrl = str_replace('&amp;', '&', base64_decode($_GET['downloadLink']));
		parse_str(parse_url($downloadUrl, PHP_URL_QUERY), $parsed);
		
		if (isset($parsed['fileName']) && $parsed['fileName'] != '') {
			$handle = fopen($packageFolder.$parsed['fileName'], 'w+');
			  
			if ($handle) {
				$writeAction = fwrite($handle, file_get_contents($downloadUrl)); 
				
				if ($writeAction) {
					$output = 'Package succesfully written';
					$output .= '<br />';
					
					// Package is written, scan for local packages
					$response = $modx->runProcessor('workspace/packages/scanlocal', array(
					
					)); 
					
					if ($response->response['success']) {
						// Install the package
						$signature = substr($parsed['fileName'], 0, -strlen('.transport.zip'));
						
						$package = $modx->getObject('transport.modTransportPackage',$signature);
						
						if ($package != null) {
							/* install package */
							$installed = $package->install(array(
								'signature' => $signature
							));
							
							/* empty cache */
							$modx->cacheManager->refresh(array($modx->getOption('cache_packages_key', null, 'packages') => array()));
							$modx->cacheManager->refresh();
							
							if (!$installed) {
								$msg = $modx->lexicon('package_err_install',array('signature' => $package->get('signature')));
								$modx->log(modX::LOG_LEVEL_ERROR,$msg);
								$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
								echo $output;
								echo $msg;
							} else {
								$msg = $modx->lexicon('package_install_info_success',array('signature' => $package->get('signature')));
								$modx->log(modX::LOG_LEVEL_WARN,$msg);
								$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
								
								// Write the premium file
								$premiumHandle = fopen($premiumFile, 'w+');
								fwrite($premiumHandle, '1');
								fclose($premiumHandle);
								
								// Redirect to succes screen
								$modx->sendRedirect('http://www.scherpontwikkeling.nl/components/premium-modx-packages/complete.html');
								exit();
							}
						}
					} else {
						echo 'The package is probably already installed, if not: contact us.';
					}
				} else {
					echo 'Package write failed';
				}
				
				fclose($handle);
			} else {
				echo 'Could not open package for writing';
			}	
		}
	} else {
		echo 'Make sure that core/packages is writable, then try again. Your payment is stored.';
	}
} else {
	if (is_file($premiumFile)) {
		$premium = '1';
	} else {
		$premium = '0';
	}
	return file_get_contents('http://www.scherpontwikkeling.nl/components/formsave/about.html?premium='.$premium);
}