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
 * Interface that must be implemented by all providers.
 *
 * @author Diego Saint Esteben <diego@ringcaptcha.com>
 */
interface ProviderInterface
{
    /**
     * Lookup for an app.
     *
     * @param mixed $id Unique identifier.
     *
     * @return AppInfo An AppInfo instance.
     *
     * @throws NotFoundException When the app is not found.
     */
    public function lookup($id);
}
