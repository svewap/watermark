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

use \TYPO3\CMS\Core\Resource\Processing\AbstractGraphicalTask;

/**
 * A task that takes care of cropping, scaling and/or masking an image.
 */
class ImageCropScaleMaskTask extends AbstractGraphicalTask {

	/**
	 * @var string
	 */
	protected $type = 'Image';

	/**
	 * @var string
	 */
	protected $name = 'SquareThumbnail';

	/**
	 * @return string
	 */
	public function getTargetFileName() {
		return 'csm_' . parent::getTargetFilename();
	}

	/**
	 * Checks if the given configuration is sensible for this task, i.e. if all required parameters
	 * are given, within the boundaries and don't conflict with each other.
	 *
	 * @param array $configuration
	 * @return boolean
	 */
	protected function isValidConfiguration(array $configuration) {
		// TODO: Implement isValidConfiguration() method.
	}

	public function fileNeedsProcessing() {
		// TODO: Implement fileNeedsProcessing() method.
	}
}
