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
