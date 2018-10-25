<?php
/**
 * This file is part of "Arun - CLI Php Microframework" released under the following terms
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
 * Date: 17/10/18
 * Time: 19.09
 */

namespace ArunCore\Interfaces\Domain;

interface DomainUtilsInterface
{
    /**
     * Check if the number of parameters from command line
     * is the same required from DOMAIN:ACTION class
     * @param string $className
     * @param string $action
     * @return mixed
     * @throws \ReflectionException
     */
    public function numberOfMandatoryParameters(string $className, string $action);

    /**
     * Recast all command line parameters to the class parameters that
     * corresponds to DOMAIN:ACTION required from CLI
     * @param string $className
     * @param string $action
     * @param array $realParameters
     *
     * @return array
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getReCastedParameters(string $className, string $action, array $realParameters): array;
}