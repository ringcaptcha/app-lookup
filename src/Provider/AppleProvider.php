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

        return new AppInfo($id, $data['trackName'], $data['sellerUrl'], $data['description'], $data['artworkUrl512'], array_map('strtolower', $data['genres']), $data['screenshotUrls'], AppInfo::PLATFORM_IOS);
    }
}
