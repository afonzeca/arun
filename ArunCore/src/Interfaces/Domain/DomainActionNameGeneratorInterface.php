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
 * This Contract defines how two methods for creating the complete namespace path and class names which corresponds
 * to the DOMAIN and ACTION required
 *
 * Date: 17/10/18
 * Time: 18.32
 */

namespace ArunCore\Interfaces\Domain;

interface DomainActionNameGeneratorInterface
{
    /**
     * Generate the NAMESPACE CLASSNAME AND ACTION for calling
     *
     * @return array
     * @throws \Exception
     */
    public function getClassAndMethodNamesForCalling(): array;

    /**
     * @param string $domain
     *
     * @return string
     */
    public static function getFQDNClass(string $domain): string;

    /**
     * @param string $class
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public static function extractDomainNameFromClassName(string $class);
}