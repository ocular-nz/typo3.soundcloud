<?php
/**
 * Created by PhpStorm.
 * User: pflaumti
 * Date: 11.05.17
 * Time: 13:45
 */

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['soundcloud'] = \Yahatix\Soundcloud\Helpers\SoundcloudHelper::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['soundcloud'] = 'audio/soundcloud';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] . ',soundcloud';
$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(\Yahatix\Soundcloud\Renderer\SoundcloudRenderer::class);
