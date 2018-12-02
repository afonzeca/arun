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

use ArunCore\Annotations as SET;
use ArunCore\Facades\Sanitizer;

/**
 * Class ExampleDomain
 *
 * @SET\DomainSyn("Domain example created using Arun Framework")
 * @SET\DomainEnabled(true)
 *
 */
class ExampleDomain extends DomainCommand
{
    /**
     *
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This method says hello to a specified name")
     * @SET\ActionOption("--say-hello-to-friend=<name>:It also says hello to a friend of yours")
     *
     * @param string $name
     * @param string $yourPlanet
     *
     * @throws
     */
    public function hello($name, $yourPlanet = "Earth")
    {
        $this->cOut->writeln("\nHi, #RED#$name#DEF#! Do you came from planet $yourPlanet?");
        if ($this->hasOption("say-hello-to-friend")) {

            $friendName = $this->getOptionValue("say-hello-to-friend");
            $this->cOut->writeln("\nI'm pleased to say hello to your friend #LGRAY#$friendName#DEF#\n");

        }
    }

    /**
     * @SET\ActionEnabled(true)
     * @SET\ActionSyn("This method says bye")
     */
    public function bye()
    {
        echo "Bye";
    }
}
