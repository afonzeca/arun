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
 * This is the default class called when Arun in started without parameters
 *
 * Date: 18/10/18
 * Time: 17.40
 */

namespace App\Console\Domains;

use ArunCore\Annotations as SET;
use ArunCore\Core\Domain\DomainActionNameGenerator;

/**
 * Class DefaultDomain
 *
 * @SET\DomainEnabled(true)
 * @SET\DomainSyn("Arun without parameters displays general help")
 *
 * @package App\Console\Domains
 */
class DefaultDomain extends DomainCommand
{
    /**
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("Generic help for the whole application")
     *
     * @throws \ReflectionException
     */
    public function help()
    {
        $className = (new \ReflectionClass($this))->getName();

        $this->helpGen->makeHelpMessage(
            $className,
            DomainActionNameGenerator::extractDomainNameFromClassName($className)
            , true
        );
    }

    /**
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("Default Arun Action")
     *
     * The default Action called when Arun is called without parameters.
     * Replace with your code if need
     *
     * @throws \ReflectionException
     */
    public function default()
    {
        if($this->hasOption("v") || $this->hasOption("version"))
        {
            $this->cOut->blank();
            $this->cOut->writeln("#RED#Arun Microframework ".getenv("APP_VERSION")."#DEF# - (C) 2018 by Angelo Fonzeca (Apache License 2.0)");
            $this->cOut->blank();
            $this->cOut->writeln("Please check #CYAN#https://github.com/afonzeca/arun#DEF# for source code and documentation");
            $this->cOut->blank();

            return;
        }

       $this->help();
    }

}