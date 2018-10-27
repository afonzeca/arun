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
 * Class ArunCore allows to kickstart the application
 * by executing the DOMAIN:ACTION requested from CLI
 * using the DomainActionExecutor class.
 *
 * Classes are instantiated via DI Container according to their contracts
 *
 * NOTE: At the moment the ArunCore acts only as a DomainActionExecutor caller due to the early stage of the project.
 * I choose to not call directly the DomainActionExecutor class from App because I will introduce other functionalities
 * that will be processed at ArunCore level
 *
 * Date: 26/09/18
 */

namespace ArunCore\Core;

use ArunCore\Interfaces\Domain\DomainActionExecutorInterface;
use ArunCore\Interfaces\Domain\DomainActionNameGeneratorInterface;

use ArunCore\Interfaces\Core\ArunCoreInterface;

class ArunCore implements ArunCoreInterface
{

#region properties
    /**
     * Contains the instance of an "Executor" Class which adheres to DomainActionExecutor
     *
     * @var \ArunCore\Interfaces\Domain\DomainActionExecutorInterface $executor;
     */
    private $executor;

    /**
     * Contains the instance of the Class that processes a ConsoleInput object, for
     * finding the correct name of the Class to instantiate for managing the DOMAIN and its ACTION(method)
     *
     * @var DomainActionNameGeneratorInterface $classNameGenerator
     */
    private $classNameGenerator;

#endregion properties

    /**
     * ArunCore constructor.
     * @param DomainActionNameGeneratorInterface $classNameGenerator
     * @param DomainActionExecutorInterface $executor
     */
    public function __construct(
        DomainActionNameGeneratorInterface $classNameGenerator,
        DomainActionExecutorInterface $executor
    )
    {
        $this->classNameGenerator = $classNameGenerator;
        $this->executor = $executor;
    }

    /**
     * According to the DOMAIN:ACTION specified from command line it will make an object
     * with the same name of "DOMAIN" and call a method based on the value of "ACTION"
     *
     * @return void
     * @throws \Exception
     */
    public function start(): void
    {
        $classAndMethodName = $this->classNameGenerator->getClassAndMethodNamesForCalling();

        $this->executor->exec(...$classAndMethodName);
    }

}