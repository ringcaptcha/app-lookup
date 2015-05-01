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

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use RingCaptcha\AppLookup\AppInfo;
use RingCaptcha\AppLookup\Exception\NotFoundException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Lookup for an app in the Play Store.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class GoogleProvider implements ProviderInterface
{
    const ENDPOINT = 'https://play.google.com/store/apps/details';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Constructor.
     *
     * @param ClientInterface|null $client A ClientInterface instance or null.
     */
    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function lookup($id)
    {
        $response = $this->client->get(self::ENDPOINT, [
            'query' => ['id' => $id]
        ]);

        if (404 === $response->getStatusCode()) {
            throw new NotFoundException($id);
        }

        $body = (string) $response->getBody();

        $crawler = new Crawler($body);

        $name = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-title ')]/descendant::div")->text();
        $owner = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-subtitle ') and (contains(concat(' ', normalize-space(@class), ' '), ' primary '))]/descendant::span")->text();
        $description = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' id-app-orig-desc ')]")->text();
        $screenshots = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' details-section ') and (contains(concat(' ', normalize-space(@class), ' '), ' screenshots '))]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' screenshot-container ')]/descendant::img")->slice(1)->each(function ($node, $i) { return $node->attr('src'); });
        $tags = array_map('strtolower', (array) $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' document-subtitle ') and (contains(concat(' ', normalize-space(@class), ' '), ' category '))]/descendant::span")->text());
        $cover = $crawler->filterXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' details-info ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' cover-container ')]/descendant::img")->attr('src');
        
        return new AppInfo($id, $name, $owner, $description, $cover, $tags, $screenshots);
    }
}
