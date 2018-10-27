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
 * Date: 17/10/18
 * Time: 18.35
 */

namespace ArunCore\Interfaces\Domain;

interface DomainActionExecutorInterface
{
    /**
     * Get DOMAIN:ACTION and parameters processed from a Class which adheres to DomainActionNameGeneratorInterface
     * check if the number of parameters from cli corresponds to class parameters that manages the DOMAIN:ACTION
     * This EXEC method uses Factory for making the Class (because DOMAINS are not services, we do not store them into the
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

    public function exec(string $className, string $domain, string $action): bool;
}