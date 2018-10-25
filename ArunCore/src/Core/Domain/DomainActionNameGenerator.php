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
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/Arun
 *
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
     * Contains the "whitelist" of DOMAINS (classes) that can be instantiated (required from CLI)
     *
     * @var array
     */
    private $domainsActionWhitelist;

    /**
     * The application BasePath
     *
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $whiteListPath;

    /**
     *
     * Get the whiteList info and a ConsoleInput object. According to processed command line and afted some check,
     * it generates a full namespaced class and method to call
     *
     * @param string $whiteListName
     * @param string $basePath
     * @param ConsoleInputInterface $ConsoleInput
     */
    public function __construct(
        string $whiteListName,
        string $basePath,
        ConsoleInputInterface $ConsoleInput
    )
    {
        $this
            ->setWhiteListName($whiteListName)
            ->setBasePath($basePath);

        $this->domainsActionWhitelist = [];
        $this->consoleInput = $ConsoleInput;
    }

    /**
     * Check if the DOMAIN required is present in Whitelist
     *
     * @return bool
     * @throws \Exception
     */
    protected function isDomainValid(): bool
    {
        return array_key_exists($this->consoleInput->getDomainName(), $this->getDomainWhiteList()) === false ? false : true;
    }

    /**
     * Check if and ACTION is allowed to be called by checking Whitelist
     *
     * @return bool
     * @throws \Exception
     */
    private function isActionValid($domain): bool
    {
        if ($this->consoleInput->getActionName() == "default") {
            return true;
        }

        return array_key_exists($this->consoleInput->getActionName(), $this->domainsActionWhitelist[$domain]["actions"]) === false ? false : true;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getWhiteListPath()
    {
        return sprintf($this->getWhiteListName(), $this->getBasePath());
    }

    /**
     * Get the Whitelist
     *
     * @return array
     * @throws \Exception
     */
    public function getDomainWhiteList(): array
    {
        if (count($this->domainsActionWhitelist) == 0) {
            $whitelistName = $this->getWhiteListPath();
            $this->domainsActionWhitelist = include $whitelistName;
        }

        return $this->domainsActionWhitelist;
    }

    /**
     * Get application BasePath
     *
     * @return string
     * @throws \Exception
     */
    private function getBasePath(): string
    {
        if (isset($this->basePath)) {
            return $this->basePath;
        }

        throw new \Exception(sprintf("%s: BasePath is null", get_class($this)));
    }

    /**
     * Set application BasePath
     *
     * @param $basePath
     * @return $this
     */
    private function setBasePath(string $basePath)
    {
        $this->basePath = sprintf("/%s/", trim($basePath, '/'));

        return $this;
    }

    /**
     * @param string $whiteListName
     * @return $this
     */
    private function setWhiteListName(string $whiteListName)
    {
        $this->whiteListPath = $whiteListName;

        return $this;
    }

    /**
     * @return string
     */
    private function getWhiteListName()
    {
        return $this->whiteListPath;
    }

    /**
     * It allows to inject a custom WhiteList for testing - DO NOT USE DURING NORMAL USE
     *
     * @param array $whiteListCustom
     */
    public function setCustomWhitelistArray(array $whiteListCustom)
    {
        $this->domainsActionWhitelist = $whiteListCustom;
    }

    /**
     * Check for the correct DOMAIN:ACTION required from command line according to whitelist
     * and then generate the NAMESPACE CLASSNAME AND ACTION for calling
     *
     * @return array
     * @throws \Exception
     */
    public function getClassAndMethodNamesForCalling(): array
    {
        $domain = $this->consoleInput->getDomainName();
        $action = $this->consoleInput->getActionName();

        if (!$this->isDomainValid()) {
            $domain = "default";
        }

        if (!$this->isActionValid($domain)) {
            $action = "help";
        }

        $className = sprintf("\\App\\Managers\\Cmd\\%sDomain", ucfirst($domain));

        return [$className, $action];
    }

}