<?php
namespace WapplerSystems\Watermark\Resource\Processing;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use \TYPO3\CMS\Core\Resource;
use \TYPO3\CMS\Core\Utility;
use \TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use \TYPO3\CMS\Core\Resource\Processing\TaskInterface;


    /**
 * Helper class to locally perform a crop/scale/mask task with the TYPO3 image processing classes.
 */
class SquareThumbnailHelper {

	/**
	 * @var LocalImageProcessor
	 */
	protected $processor;

	/**
	 * @param LocalImageProcessor $processor
	 */
	public function __construct(LocalImageProcessor $processor) {
		$this->processor = $processor;
	}

	/**
	 * This method actually does the processing of files locally
	 *
	 * Takes the original file (for remote storages this will be fetched from the remote server),
	 * does the IM magic on the local server by creating a temporary typo3temp/ file,
	 * copies the typo3temp/ file to the processing folder of the target storage and
	 * removes the typo3temp/ file.
	 *
	 * The returned array has the following structure:
	 *   width => 100
	 *   height => 200
	 *   filePath => /some/path
	 *
	 * @param TaskInterface $task
	 * @return array|NULL
	 */
	public function process(TaskInterface $task) {
		$result = NULL;
		$targetFile = $task->getTargetFile();
		$sourceFile = $task->getSourceFile();

		$originalFileName = $sourceFile->getForLocalProcessing(FALSE);
		/** @var $gifBuilder \TYPO3\CMS\Frontend\Imaging\GifBuilder */
		$gifBuilder = Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Imaging\\GifBuilder');
		$gifBuilder->init();
		$gifBuilder->absPrefix = PATH_site;

		$configuration = $targetFile->getProcessingConfiguration();
		$configuration['additionalParameters'] = $this->modifyImageMagickStripProfileParameters($configuration['additionalParameters'], $configuration);

		if (empty($configuration['fileExtension'])) {
			$configuration['fileExtension'] = $task->getTargetFileExtension();
		}

		$options = $this->getConfigurationForImageCropScaleMask($targetFile, $gifBuilder);



        // the result info is an array with 0=width,1=height,2=extension,3=filename
        $result = $gifBuilder->imageMagickConvert(
            $originalFileName,
            $configuration['fileExtension'],
            $configuration['width'],
            $configuration['height'],
            $configuration['additionalParameters'],
            $configuration['frame'],
            $options
        );

		// check if the processing really generated a new file
		if ($result !== NULL) {
			if ($result[3] !== $originalFileName) {
				$result = array(
					'width' => $result[0],
					'height' => $result[1],
					'filePath' => $result[3],
				);
			} else {
				// No file was generated
				$result = NULL;
			}
		}

		return $result;
	}

	/**
	 * @param Resource\ProcessedFile $processedFile
	 * @param \TYPO3\CMS\Frontend\Imaging\GifBuilder $gifBuilder
	 *
	 * @return array
	 */
	protected function getConfigurationForImageCropScaleMask(Resource\ProcessedFile $processedFile, \TYPO3\CMS\Frontend\Imaging\GifBuilder $gifBuilder) {
		$configuration = $processedFile->getProcessingConfiguration();

		if ($configuration['useSample']) {
			$gifBuilder->scalecmd = '-sample';
		}
		$options = array();
		if ($configuration['maxWidth']) {
			$options['maxW'] = $configuration['maxWidth'];
		}
		if ($configuration['maxHeight']) {
			$options['maxH'] = $configuration['maxHeight'];
		}
		if ($configuration['minWidth']) {
			$options['minW'] = $configuration['minWidth'];
		}
		if ($configuration['minHeight']) {
			$options['minH'] = $configuration['minHeight'];
		}

		$options['noScale'] = $configuration['noScale'];

		return $options;
	}

	/**
	 * Returns the filename for a cropped/scaled/masked file.
	 *
	 * @param TaskInterface $task
	 * @return string
	 */
	protected function getFilenameForImageCropScaleMask(TaskInterface $task) {

		$configuration = $task->getTargetFile()->getProcessingConfiguration();
		$targetFileExtension = $task->getSourceFile()->getExtension();
		$processedFileExtension = $GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib_png'] ? 'png' : 'gif';
		if (is_array($configuration['maskImages']) && $GLOBALS['TYPO3_CONF_VARS']['GFX']['im'] && $task->getSourceFile()->getExtension() != $processedFileExtension) {
			$targetFileExtension = 'jpg';
		} elseif ($configuration['fileExtension']) {
			$targetFileExtension = $configuration['fileExtension'];
		}

		return $task->getTargetFile()->generateProcessedFileNameWithoutExtension() . '.' . ltrim(trim($targetFileExtension), '.');
	}

	/**
	 * Modifies the parameters for ImageMagick for stripping of profile information.
	 *
	 * @param string $parameters The parameters to be modified (if required)
	 * @param array $configuration The TypoScript configuration of [IMAGE].file
	 * @return string
	 */
	protected function modifyImageMagickStripProfileParameters($parameters, array $configuration) {
			// Strips profile information of image to save some space:
		if (isset($configuration['stripProfile'])) {
			if ($configuration['stripProfile']) {
				$parameters = $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_stripProfileCommand'] . $parameters;
			} else {
				$parameters .= '###SkipStripProfile###';
			}
		}
		return $parameters;
	}


}
