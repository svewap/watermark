<?php
defined('TYPO3_MODE') or die();

/**
 * Add extra field watermark to sys_file_reference record
 */
/*
$newSysFileReferenceColumns = [
    'watermark' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:watermark/Resources/Private/Language/locallang_db.xlf:enable_watermark',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_reference', $newSysFileReferenceColumns, true);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'sys_file_reference',
    'imageoverlayPalette',
    'watermark',
    'after:crop'
);
*/

//$GLOBALS['TCA']['sys_file_reference']['palettes']['imageoverlayPalette']['showitem'] .= ',--linebreak--,watermark';