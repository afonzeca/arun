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
 * This class executes the DOMAIN:ACTION required
 *
 *
 * Date: 10/10/18
 * Time: 13.29
 */

namespace ArunCore\Core\Domain;

use ArunCore\Interfaces\IO\ConsoleInputInterface;
use ArunCore\Interfaces\Domain\DomainActionExecutorInterface;
use ArunCore\Interfaces\Helpers\LowLevelHelperInterface;

class DomainActionExecutor implements DomainActionExecutorInterface
{
    /**
     * Used for storing the object which contains parsed parameters from CommandLine
     *
     * @var ConsoleInputInterface $cInput
     */
    private $cInput;

    /**
     * Factory for non-static classes
     *
     * @var \DI\FactoryInterface
     */
    private $container;

    /**
     * An Helper class for analyzing a class that represents a Domain
     *
     * @var LowLevelHelperInterface $lHelper
     */
    private $lHelper;

    /**
     * DomainAction constructor.
     *
     * Needs a Factory for making objects and a class set of support class
     *
     * The initial parameters/dependencies are taken one time from container instantiated according to the console params.
     *
     * This class is a stateless service, so no setters are present.
     *
     * @param \DI\FactoryInterface $container
     * @param LowLevelHelperInterface $lHelper
     * @param ConsoleInputInterface $ConsoleInput
     */
    public function __construct(
        \DI\FactoryInterface $container,
        LowLevelHelperInterface $lHelper,
        ConsoleInputInterface $ConsoleInput
    )
    {
        $this->container = $container;
        $this->lHelper = $lHelper;
        $this->cInput = $ConsoleInput;
    }


    /**
     * Get DOMAIN:ACTION and parameters processed from a Class which adhere to CommandLineManagerInterface
     * check if the number of parameters from cli corresponds to class parameters that manages the DOMAIN:ACTION
     * This method uses Factory for making the Class (because DOMAINS are not services, we do not store it into the
     * container)
     *
     * @param string $className
     * @param string $domain
     * @param string $action
     *
     * @throws \Exception
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     *
     * @return bool
     */
    public function exec(
        string $className,
        string $domain,
        string $action
    ): bool {
        if (
            class_exists($className) &&
            $this->lHelper->isDomainEnabled($domain)
        ) {
            $numOfMandatoryParams = $this->lHelper->numberOfMandatoryParameters($className, $domain, $action);
            $realParameters = $this->cInput->getParams();

            $numOfMandatoryParams !== null && ($numOfMandatoryParams <= count($this->cInput->getParams()))
                ?: $action = "help";

            $this->makeObjectAndRunMethod($className, $domain, $action, $realParameters);

            return true;
        }

        $this->makeObjectAndRunMethod("App\Console\Domains\DefaultDomain", $domain, "help", []);

        return false;
    }

    /**
     * @param string $className
     * @param string $domain
     * @param string $action
     * @param array $realParameters
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    private function makeObjectAndRunMethod(
        string $className,
        string $domain,
        string $action,
        array $realParameters
    ): void
    {
        if (!$this->lHelper->isActionEnabled($className, $action)) {
            $action = "help";
        }

        $this->container->make($className)
            ->{$action}
            (...($this->lHelper->getReCastedParameters($className, $domain, $action, $realParameters)));
    }
}