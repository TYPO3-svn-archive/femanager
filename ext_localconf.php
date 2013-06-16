<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'In2.' . $_EXTKEY,
	'Pi1',
	array(
		'User' => 'create, edit, update, delete, list, show, new, createStatus',
	),
	array(
		'User' => 'create, edit, update, delete, list, show, new, createStatus',
	)
);

?>