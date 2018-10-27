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
 * This class generates the namespace + classname(Domain) + method(Action) to be called
 *
 * Date: 10/10/18
 * Time: 13.29
 */

namespace ArunCore\Core\Domain;

use ArunCore\Interfaces\IO\ConsoleInputInterface;
use ArunCore\Interfaces\Domain\DomainActionNameGeneratorInterface;

class DomainActionNameGenerator implements DomainActionNameGeneratorInterface
{
    /**
     * Used for storing the object which contains parsed parameters from CommandLine
     *
     * @var ConsoleInputInterface $consoleInput
     */
    private $consoleInput;

    /**
     * @param ConsoleInputInterface $ConsoleInput
     */
    public function __construct(
        ConsoleInputInterface $ConsoleInput
    )
    {
        $this->consoleInput = $ConsoleInput;
    }

    /**
     * Generate the NAMESPACE CLASSNAME AND ACTION for calling
     *
     * @return array
     * @throws \Exception
     */
    public function getClassAndMethodNamesForCalling(): array
    {
        $domain = $this->consoleInput->getDomainName();
        $action = $this->consoleInput->getActionName();

        $className = self::getFQDNClass($domain);

        return [$className, $domain, $action];
    }

    /**
     * @param string $domain
     *
     * @return string
     */
    public static function getFQDNClass(string $domain): string
    {
        return sprintf("\\App\\Console\\Domains\\%sDomain", ucfirst($domain));
    }

    /**
     * @param string $class
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public static function extractDomainNameFromClassName(string $class)
    {
        $classNameWithoutNs = (new \ReflectionClass($class))->getShortName();

        return strtolower(str_replace("Domain", "", $classNameWithoutNs));
    }

}