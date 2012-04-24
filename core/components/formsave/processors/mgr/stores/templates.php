<?php

$assetsPath = $this->modx->getOption('formsave.assets_path', null, $modx->getOption('assets_path').'components/formsave/');
$baseDir = $assetsPath.'mgr/templates/';

if ($handle = opendir($baseDir)) {
	
    while (false !== ($template = readdir($handle))) {
    	
        if ($template != "." && $template != ".." && is_dir($baseDir.$template)) {
        	if (is_file($baseDir.$template.'/config.php')) {
        		$helpText = '';
        		$config = include $baseDir.$template.'/config.php';
        		if (isset($config['helpText'])) {
        			$helpText = $config['helpText'];
        		}
        	}
            $list[] = array(
		    	'template' => $template,
		    	'helpText' => $helpText
		    );
        }
    }
    closedir($handle);
}

return $this->outputArray($list, sizeof($list));