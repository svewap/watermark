<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

call_user_func(
    function ($extKey) {


        /* square thumbnails */

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
            'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
            \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
            'WapplerSystems\\Watermark\\Resource\\Processing\\PostfileProcessing',
            'postProcess'
        );


    },
    'watermark'
);
