<?php
/**
 * This file is part of "Bosun - CLI Php Microframework" released under the following terms
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
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/bosun
 *
 *
 * Get the command line input and process its parameters, options, domain, action, etc.
 *
 * Date: 18/10/18
 * Time: 8.37
 */

namespace BosunCore\Interfaces\IO;

interface ConsoleInputInterface
{
    /**
     * Get short options with value (e.g. -u="FOO")
     * @return array
     */
    public function extractShortOptionsWithParams(): array;

    /**
     * @param $key
     * @return string | null
     */
    public function getShortOptionValuedOnly($key);

    /**
     * Get long options with value (e.g. --config="/etc/myapp/config.cfg"
     *
     * @return array
     */
    public function extractLongOptionsWithValues(): array;

    /**
     * @param $key
     * @return string | null
     */
    public function getLongOptionValuedOnly($key);

    /**
     * Get long options without parameters (e.g. --print --help --check)
     *
     * @return array
     */
    public function extractLongOptionsVoid();

    /**
     * @param $key
     * @return bool
     */
    public function hasLongOptionVoid($key): bool;

    /**
     * @param $key
     * @return bool
     */
    public function hasShortOptionVoid($key): bool;

    /**
     * @param $key
     * @return bool
     */
    public function hasOption($key): bool;

    /**
     * Get short options without parameters like -i -t -g
     *
     * @return array
     */
    public function extractShortOptionsVoid(): array;

    /**
     * Get the name of the DOMAIN required
     *
     * @return string
     * @throws \Exception
     */
    public function getDomainName(): string;

    /**
     * Get the name of action required
     *
     * @return string
     * @throws \Exception
     */
    public function getActionName(): string;

    /**
     * Get the list of command line parameters only (e.g. executable, DOMAIN:ACTION, options excluded)
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Get the whole list of the arguments passed from command line (executable, DOMAIN:ACTION, parameters, options).
     * If filtered, executable name and ACTION:DOMAIN are escluded form list
     *
     * @param bool $filtered
     * @return array
     */
    public function getRawArgs($filtered = true): array;

    /**
     * @param $key
     * @return string | null
     */
    public function getOption($key);


}