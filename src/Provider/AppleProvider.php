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

/**
 * Lookup for an app in the iTunes Store.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class AppleProvider implements ProviderInterface
{
    const ENDPOINT = 'https://itunes.apple.com/lookup';

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
        $response = $this->client->get(self::ENDPOINT, array(
            'query' => array('id' => $id)
        ));

        $data = $response->json();

        if (0 === $data['resultCount']) {
            throw new NotFoundException($id);
        }

        $data = $data['results'][0];

        return new AppInfo($id, $data['trackName'], $data['artistName'], $data['description'], $data['artworkUrl512'], array_map('strtolower', $data['genres']), $data['screenshotUrls']);
    }
}
