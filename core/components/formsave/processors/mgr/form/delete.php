<?php

$id = (int) $_REQUEST['id'];
$formSave =& $modx->formsave;

$form = $modx->getObject('fsForm', $id);
$formArray = $form->toArray();

$form->remove();

return $modx->error->success();