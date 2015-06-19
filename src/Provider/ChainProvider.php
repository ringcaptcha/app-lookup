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

use RingCaptcha\AppLookup\Exception\NotFoundException;

/**
 * Lookup for an app through multiple providers.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
class ChainProvider implements ProviderInterface
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->providers = new \SplObjectStorage();
    }

    /**
     * Attach a provider to the chain.
     *
     * @param ProviderInterface $provider A ProviderInterface instance.
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers->attach($provider);
    }

    /**
     * {@inheritdoc}
     */
    public function lookup($id)
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->lookup($id);
            } catch (NotFoundException $e) {
                // Try with the next provider.
            }
        }

        throw new NotFoundException($id);
    }
}
