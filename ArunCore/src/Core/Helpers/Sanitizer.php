<?php
/**
 * This file is part of "Arun - CLI Microframework for Php7.2+" released under the following terms
 *
 * Copyright 2018 Angelo FONZECA ( https://www.linkedin.com/in/angelo-f-1806868/ )
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/arun
 *
 *
 * Class for sanitizing and re-casting command line parameters (NOT USED YET! KEEP ATTENTION TO THE PARAMETERS
 * PASSED FROM COMMAND LINE)
 *
 * TODO: Use it before starting methods!
 *
 *
 * Date: 11/10/18
 */

namespace ArunCore\Core\Helpers;

use ArunCore\Interfaces\Security\SanitizerInterface;

class Sanitizer implements SanitizerInterface
{
    /**
     * List of characters to be stripped from parameters
     *
     * @const string
     */
    const badChars = "$%&/\\[]{}=;.'\"";

    /**
     * Strip unauthorized chars from array
     *
     * @param array $parameters
     * @return array
     */
    public function stripBadCharsFromArray(array $parameters, array $optionalChars = []): array
    {
        return str_replace(self::badChars . $optionalChars, "", $parameters);
    }

    /**
     * Strip unauthorized chars from string
     *
     * @param string $parameter []
     * @param array $optionalChars
     * @return string
     */
    public function stripBadChars(string $parameter, array $optionalChars = []): string
    {
        return str_replace(self::badChars . $optionalChars, "", $parameter);
    }

    /**
     * Check CLASS name validity (according to the definition from PHP Manual)
     *
     * @param string $className
     * @return bool
     */
    public function isClassNameValid(string $className): bool
    {
        return (bool)(preg_match('^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$', $className));
    }

    /**
     * Check correct filename
     *
     * @param string $parameter
     * @return bool
     */
    public function isFilenameValid(string $parameter): bool
    {
        return !preg_match('/^(?:[a-z0-9_-]|\.(?!\.))+$/iD', $parameter);
    }

}