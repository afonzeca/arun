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
 * Date: 18/10/18
 * Time: 20.42
 */

namespace ArunCore\Interfaces\Core;

interface HelpGeneratorInterface
{
    /**
     * Generate the correct help for a Domain (set of aggregated commands). Help is uniq for all Action (commands)
     * owned by a Domain.
     *
     * The classInstance parameters allows to inspect the class methods and their syntax for checking number of parameters, type,
     * and optional flags
     *
     * @param $domain
     * @param $classInstance
     *
     * @throws \ReflectionException
     */
    public function makeHelpMessage($domain, $classInstance, $global = false);
}