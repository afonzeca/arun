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
 * This class executes the DOMAIN:ACTION required
 *
 *
 * Date: 10/10/18
 * Time: 13.29
 */

namespace ArunCore\Core\Domain;

use ArunCore\Interfaces\IO\ConsoleInputInterface;
use ArunCore\Interfaces\Domain\DomainActionExecutorInterface;
use ArunCore\Interfaces\Domain\DomainUtilsInterface;

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
     * @var \Di\FactoryInterface
     */
    private $container;

    /**
     * An Helper class for analyzing a class that represents a Domain
     *
     * @var DomainUtilsInterface $utils
     */
    private $utils;

    /**
     * DomainAction constructor.
     *
     * Needs a Factory for making objects and a class set of support class
     *
     * The initial parameters/dependencies are taken one time from container instantiated according to the console params.
     *
     * This class is a stateless service, so no setters are present.
     *
     * @param \Di\FactoryInterface
     * @param DomainUtilsInterface $utils
     * @param ConsoleInputInterface $ConsoleInput
     */
    public function __construct(
        \Di\FactoryInterface $container,
        DomainUtilsInterface $utils,
        ConsoleInputInterface $ConsoleInput
    )
    {
        $this->container = $container;
        $this->utils = $utils;
        $this->cInput = $ConsoleInput;
    }


    /**
     * Get DOMAIN:ACTION and parameters processed from a Class which adhere to CommandLineManagerInterface
     * check if the number of parameters from cli corresponds to class parameters that manages the DOMAIN:ACTION
     * This method uses Factory for making the Class (because DOMAINS are not services, we do not store it into the
     * container)
     *
     * @param $className
     * @param $action
     *
     * @throws \Exception
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     *
     * @return bool
     */
    public function exec(
        $className,
        $action
    ): bool
    {
        if (class_exists($className)) {
            $numOfMandatoryParams = $this->utils->numberOfMandatoryParameters($className, $action);
            $realParameters = $this->cInput->getParams();

            $numOfMandatoryParams <= count($this->cInput->getParams()) ?: $action = "help";
            $this->makeObjectAndRunMethod($className, $action, $realParameters);

            return true;
        }

        $this->makeObjectAndRunMethod("App\Managers\Cmd\DefaultDomain", "help", []);

        return false;
    }

    /**
     * @param $className
     * @param $action
     * @param $realParameters
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \ReflectionException
     */
    private function makeObjectAndRunMethod($className, $action, $realParameters): void
    {
        $this->container->make($className)
            ->{$action}
            (...($this->utils->getReCastedParameters($className, $action, $realParameters)));
    }
}