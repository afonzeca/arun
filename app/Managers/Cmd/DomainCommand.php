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
 * Date: 15/10/18
 * Time: 13.03
 */

namespace App\Managers\Cmd;

use ArunCore\Abstracts\BaseDomainCommand;
use ArunCore\Traits\CmdManagers\DependencyInjectionCapabilities;

abstract class DomainCommand extends BaseDomainCommand
{
    use DependencyInjectionCapabilities;

    /**
     * Default help when undefined
     */
    public function help()
    {
        echo "No available help for this command\r\n";
    }

}