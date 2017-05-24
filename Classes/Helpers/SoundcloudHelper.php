<?php

namespace Yahatix\Soundcloud\Helpers;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOEmbedHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Soundcloud helper class
 */
class SoundcloudHelper extends AbstractOEmbedHelper
{
    /**
     * Get public url
     *
     * @param File $file
     * @param bool $relativeToCurrentScript
     * @return string|NULL
     *
     */
    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        $playlistId = $this->getOnlineMediaId($file);
        return sprintf('https://soundcloud.com/%s', $playlistId);
    }

    /**
     * Get local absolute file path to preview image
     *
     * @param File $file
     * @return string
     */
    public function getPreviewImage(File $file)
    {
        $playlistId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'soundcloud_' . md5($playlistId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $oEmbedData = $this->getOEmbedData($playlistId);
            $previewImage = GeneralUtility::getUrl($oEmbedData['thumbnail_url']);
            if ($previewImage !== false) {
                file_put_contents($temporaryFileName, $previewImage);
                GeneralUtility::fixPermissions($temporaryFileName);
            }
        }
        return $temporaryFileName;
    }

    /**
     * Try to transform given URL to a File
     *
     * @param string $url
     * @param Folder $targetFolder
     * @return File|NULL
     */
    public function transformUrlToFile($url, Folder $targetFolder)
    {
        $playlistPath = null;
        if (preg_match('%((https|http)(://))?(soundcloud.com)/(.*)%i', $url, $match)) {
            $playlistPath = $match[5];
        }
        if (empty($playlistPath)) {
            return null;
        }
        return $this->transformMediaIdToFile($playlistPath, $targetFolder, $this->extension);
    }

    /**
     * Get oEmbed url to retrieve oEmbed data
     *
     * @param string $mediaId
     * @param string $format
     * @return string
     */
    protected function getOEmbedUrl($mediaId, $format = 'json')
    {
        return sprintf('https://soundcloud.com/oembed?url=%s&format=%s',
            urlencode(sprintf('https://soundcloud.com/%s', $mediaId)),
            rawurlencode($format)
        );
    }

    /**
     * @param File $file
     * @return array
     */
    public function getMetaData(File $file)
    {
        $metadata = [];

        $oEmbed = $this->getOEmbedData($this->getOnlineMediaId($file));

        if ($oEmbed) {
            $metadata['width'] = (int)$oEmbed['width'];
            $metadata['height'] = (int)$oEmbed['height'];
            $metadata['html'] = (string)$oEmbed['html'];
            if (empty($file->getProperty('title'))) {
                $metadata['title'] = strip_tags($oEmbed['title']);
            }
            $metadata['author'] = $oEmbed['author_name'];
        }

        return $metadata;
    }
}
