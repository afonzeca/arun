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
 * Code Example made using the Arun CLI Micro-framework for PHP7.2+
 *
 */

namespace App\Console\Domains;

use ArunCore\Interfaces\CodeBuilders\ActionManipulatorInterface;
use ArunCore\Interfaces\CodeBuilders\DomainManipulatorInterface;
use ArunCore\Traits\Builder\CommonOptions;
use ArunCore\Traits\Builder\PlaceholderManipulator;
use ArunCore\Interfaces\IO\FileContentGeneratorInterface;

use ArunCore\Annotations as SET;

/**
 * Class GenerateDomain
 *
 * @SET\DomainSyn("Generates code for Arun Development Speed-up")
 * @SET\DomainEnabled(true)
 *
 */
class GenDomain extends DomainCommand
{
    use PlaceholderManipulator, CommonOptions;

    /**
     * @var DomainManipulatorInterface $dmc
     */
    private $dmc;

    /**
     * @var ActionManipulatorInterface $amc
     */
    private $amc;

    /**
     * GenDomain constructor.
     *
     * @param FileContentGeneratorInterface $fileGen
     * @param DomainManipulatorInterface $dmc
     * @param ActionManipulatorInterface $amc
     */
    public function __construct(
        FileContentGeneratorInterface $fileGen,
        DomainManipulatorInterface $dmc,
        ActionManipulatorInterface $amc
    ) {
        $this->dmc = $dmc;
        $this->amc = $amc;
    }

    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This action generates a Domain class for Arun automatically")
     * @SET\ActionOption("--synopsis='your domain description':Set the description of the domain")
     * @SET\ActionOption("--disabled:Generate the Domain class file but with DomainEnabled set to FALSE")
     * @SET\ActionOption("-f|--force:Force domain code overwriting - NOTE! It will destroy your hand-made code! -")
     *
     * @param string $domainName
     *
     * @throws
     */
    public function domain(string $domainName)
    {
        $force = false;

        $isEnabled = $this->isEnabled();
        $synopsis = $this->getSynopsis();

        if ($this->hasOption("force") || $this->hasOption("f")) {
            $force = true;
        }

        $this->dmc->createDomain($domainName, $synopsis, $isEnabled, $force);
    }

    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This action generates ARUN CODE for an Action linked to a Domain class")
     * @SET\ActionOption("--synopsis='your action description':Set the description of the action")
     * @SET\ActionOption("--disabled:Generate the Action method but disabled")
     *
     * @param string $domainName
     *
     * @throws
     */
    public function action(string $domainName, string $actionName)
    {
        $isEnabled = $this->isEnabled();
        $synopsis = $this->getSynopsis();

        $this->dmc->addActionIntoDomain($domainName, $actionName, $synopsis, $isEnabled);
    }

    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This action adds a parameter to a specified action(method) linked to a domain(class)")
     * @SET\ActionOption("--type='int|string':Set the 'type'. Otherwise the type will be 'string'")
     * @SET\ActionOption("--default='your default value':Otherwise the value will be not set.")
     *
     * @param string $domainName
     * @param string $actionName
     * @param string $paramName
     * @param string $type
     * @param string $defaultValue
     *
     * @return bool
     */
    public function parameter(
        string $domainName,
        string $actionName,
        string $paramName
    ): bool {

        $type = $this->getType("string");
        $defaultValue = $this->getDefault("");

        return $this->amc->addParameterToAction(
            $domainName,
            $actionName,
            $paramName,
            $type,
            $defaultValue
        );
    }

}

