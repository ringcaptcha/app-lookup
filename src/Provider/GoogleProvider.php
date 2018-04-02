<?php

/*
 * Copyright 2015 RingCaptcha
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

        $name = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@itemprop), ' '), ' name ')]/descendant::span")->text();
        $owner = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' hrTbp ')]")->text();
        $description = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@itemprop), ' '), ' description ')]/descendant::div")->text();
        $screenshots = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' KDxLi ')]/descendant::img[contains(concat(' ', normalize-space(@itemprop), ' '), ' image ')]")->each(function ($node, $i) {
            if (0 === $i) {
                return;
            }

            return $node->attr('src');
        });
        $screenshots = array_filter($screenshots);
        $tags = array_map('strtolower', (array) $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@itemprop), ' '), ' genre ')]")->text());
        $cover = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' dQrBL ')]/descendant::img[contains(concat(' ', normalize-space(@itemprop), ' '), ' image ')]")->attr('src');

        return new AppInfo($id, $name, $owner, $description, $cover, $tags, $screenshots, AppInfo::PLATFORM_ANDROID);
    }
}
