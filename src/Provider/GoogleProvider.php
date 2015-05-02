<?php

/*
 * This file is part of the app-lookup library.
 *
 * (c) RingCaptcha, LLC.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RingCaptcha\AppLookup\Provider;

use RingCaptcha\AppLookup\AppInfo;
use RingCaptcha\AppLookup\Exception\NotFoundException;
use RingCaptcha\AppLookup\Exception\RuntimeException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Lookup for an app in the Play Store.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class GoogleProvider extends CurlProvider
{
    /**
     * {@inheritdoc}
     */
    public function lookup($id)
    {
        if (!class_exists('Symfony\Component\DomCrawler\Crawler')) {
            throw new RuntimeException('symfony/dom-crawler is required.');
        }

        $url = sprintf('https://play.google.com/store/apps/details?id=%s', $id);

        $response = $this->exec($url);

        if (404 === $this->getInfo(CURLINFO_HTTP_CODE)) {
            throw new NotFoundException($id);
        }

        $this->close();

        $crawler = new Crawler($response);

        $name = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-title ')]/descendant::div")->text();
        $owner = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-subtitle ') and (contains(concat(' ', normalize-space(@class), ' '), ' primary '))]/descendant::span")->text();
        $description = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' id-app-orig-desc ')]")->text();
        $screenshots = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' details-section ') and (contains(concat(' ', normalize-space(@class), ' '), ' screenshots '))]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' screenshot-container ')]/descendant::img")->each(function ($node, $i) {
            if (0 === $i) {
                return;
            }
            return $node->attr('src');
        });
        $tags = array_map('strtolower', (array) $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-subtitle ') and (contains(concat(' ', normalize-space(@class), ' '), ' category '))]/descendant::span")->text());
        $cover = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' details-info ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' cover-container ')]/descendant::img")->attr('src');
        
        return new AppInfo($id, $name, $owner, $description, $cover, $tags, $screenshots);
    }
}
