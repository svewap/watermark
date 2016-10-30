<?php


namespace WapplerSystems\Watermark\Resource\Processing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\TypoScriptService;


class PostfileProcessing {


    /** @var array settings */
    protected $settings = array();


    /**
     * @var array
     */
    protected static $typoScript = array();




    /**
     * @param $caller \TYPO3\CMS\Core\Resource\Service\FileProcessingService
     * @param $driver \TYPO3\CMS\Core\Resource\Driver\LocalDriver
     * @param $processedFile \TYPO3\CMS\Core\Resource\ProcessedFile
     * @param $file \TYPO3\CMS\Core\Resource\File
     * @param $context string
     * @param $configuration array
     */
    public function postProcess($caller, $driver, $processedFile, $file, $context, $configuration) {

        $properties = $file->getProperties();

        if (isset($properties['watermark']) && $properties['watermark'] == 1) {

            $input = $output = $processedFile->getForLocalProcessing();

            $params = "  -watermark 30%  -gravity /srv/www/vhosts/typo3webs/v7/typo3conf/ext/demotemplate/Resources/Public/Images/logo.png SouthEast ";

            //echo $input."\n";


            $frame = "";

            $cmd = GeneralUtility::imageMagickCommand('composite', $params . ' ' . $this->wrapFileName($input . $frame). ' ' .  $this->wrapFileName($output));
            $this->IM_commands[] = array($output, $cmd);
            //echo $cmd."\n";
            $ret = \TYPO3\CMS\Core\Utility\CommandUtility::exec($cmd);
            // Change the permissions of the file
            GeneralUtility::fixPermissions($output);

            if (file_exists($output)) {

                $imageSize = getimagesize($output);
                $info = array(
                    'width'  => $imageSize[0],
                    'height' => $imageSize[1],
                    'type'   => $imageSize['mime'],
                );

                $processedFile->updateProperties($info);
            }


            $processedFile->updateWithLocalFile($output);
            $processedFile->needsReprocessing();


        }

    }


    /**
     * Get TypoScript and FlexForm
     *
     * @param ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectTypoScript(ConfigurationManagerInterface $configurationManager)
    {
        $typoScriptSetup = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var TypoScriptService $typoScriptService */
        $typoScriptService = $objectManager->get(TypoScriptService::class);


        if (!isset($typoScriptSetup['plugin.']['tx_watermark.']['settings.'])) return;

        $this->settings = $typoScriptService->convertTypoScriptArrayToPlainArray(
            $typoScriptSetup['plugin.']['tx_watermark.']['settings.']
        );

    }



    /**
     * Escapes a file name so it can safely be used on the command line.
     *
     * @param string $inputName filename to safeguard, must not be empty
     * @return string $inputName escaped as needed
     */
    protected function wrapFileName($inputName) {
        if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem']) {
            $currentLocale = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, $GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLocale']);
        }
        $escapedInputName = escapeshellarg($inputName);
        if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem']) {
            setlocale(LC_CTYPE, $currentLocale);
        }
        return $escapedInputName;
    }

}