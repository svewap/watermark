<?php
defined('TYPO3_MODE') or die();

/**
 * Add extra field watermark to sys_file_reference record
 */
$newColumns = [
    'watermark' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:watermark/Resources/Private/Language/locallang_db.xlf:enable_watermark',
        'config' => [
            'type' => 'check',
            'default' => 0
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file_metadata', $newColumns, true);

$GLOBALS['TCA']['sys_file_metadata']['types']['2']['showitem'] .= ",watermark";


//$GLOBALS['TCA']['sys_file_reference']['palettes']['imageoverlayPalette']['showitem'] .= ',--linebreak--,watermark';


/*
$tca = array(
    'ctrl' => array(
        'type' => 'file:type',
    ),
    'types' => array(

        TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
            'showitem' => '
				fileinfo, title, description, ranking, keywords, watermark,
				    --palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.accessibility;20,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
					--palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.visibility;10,
					fe_groups,
				--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
					creator, creator_tool, publisher, source, copyright, language,
					--palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.geo_location;40,
					--palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.gps;30,
					--palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.content_date;60,
				--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.camera,
					color_space,
					--palette--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.metrics;50
			',
        )
    ),
    'palettes' => array(
        '10' => array(
            'showitem' => 'visible, status',
        ),
        '20' => array(
            'showitem' => 'alternative, --linebreak--, caption, --linebreak--, download_name',
        ),
        '25' => array(
            'showitem' => 'caption, --linebreak--, download_name',
        ),
        '30' => array(
            'showitem' => 'latitude, longitude',
        ),
        '40' => array(
            'showitem' => 'location_country, location_region, location_city',
        ),
        '50' => array(
            'showitem' => 'width, height, unit',
        ),
        '60' => array(
            'showitem' => 'content_creation_date, content_modification_date',
        ),
    ),
    'columns' => array(

        'watermark' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:watermark/Resources/Private/Language/locallang_db.xlf:enable_watermark',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],

    ),
);

$GLOBALS['TCA']['sys_file_metadata'] = array_replace_recursive($GLOBALS['TCA']['sys_file_metadata'], $tca);

*/