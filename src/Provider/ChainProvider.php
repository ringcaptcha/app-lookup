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
