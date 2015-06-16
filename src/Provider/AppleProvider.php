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

/**
 * Lookup for an app in the iTunes Store.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class AppleProvider extends CurlProvider
{
    /**
     * {@inheritdoc}
     */
    public function lookup($id)
    {
        $url = sprintf('https://itunes.apple.com/lookup?id=%s', $id);

        $options = array(
            CURLOPT_FAILONERROR => true,
        );

        $response = $this->exec($url, $options);

        $this->close();

        $data = json_decode($response, true);

        if (0 === $data['resultCount']) {
            throw new NotFoundException($id);
        }

        $data = $data['results'][0];

        return new AppInfo($id, $data['trackName'], $data['sellerUrl'], $data['description'], $data['artworkUrl512'], array_map('strtolower', $data['genres']), $data['screenshotUrls'], 'iOS');
    }
}
