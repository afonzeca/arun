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
 * Date: 15/10/18
 * Time: 13.03
 */

namespace App\Console\Domains;

use ArunCore\Abstracts\BaseDomainCommand;
use ArunCore\Core\Domain\DomainActionNameGenerator;

use ArunCore\Annotations as SET;

abstract class DomainCommand extends BaseDomainCommand
{
    /**
     *
     * Default help when undefined
     *
     * @throws
     */
    public function help()
    {
        $className = (new \ReflectionClass($this))->getName();

        $this->helpGen->makeHelpMessage(
            $className,
            DomainActionNameGenerator::extractDomainNameFromClassName($className)
            , false
        );
    }

    /**
     *
     * The default Action called when Arun is called without parameters.
     * Replace with your code if need
     *
     */
    public function default()
    {
        $this->help();
    }
}