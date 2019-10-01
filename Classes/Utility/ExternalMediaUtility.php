<?php

namespace Yahatix\Soundcloud\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExternalMediaUtility extends \BK2K\BootstrapPackage\Utility\ExternalMediaUtility
{

    protected $mediaProvider = [
        'youtube',
        'youtu',
        'vimeo',
        'soundcloud'
    ];


    protected function processSoundcloud($url)
    {  
        $oEmbed = GeneralUtility::getUrl(
            $this->getOEmbedUrl($url)
        );
        if ($oEmbed) {
            $oEmbed = json_decode($oEmbed, true);
        }
        $iframe = $oEmbed['html'];
        preg_match('/"(https:\/\/w\.soundcloud\.com\/.*)"/', $iframe, $src);
        return $src[1];
    }


    /**
     * Get oEmbed url to retrieve oEmbed data
     *
     * @param string $url
     * @param string $format
     * @return string
     */
    protected function getOEmbedUrl($url, $format = 'json')
    {
        return sprintf('https://soundcloud.com/oembed?url=%s&format=%s',
            urlencode(sprintf($url)),
            rawurlencode($format)
        );
    }
}