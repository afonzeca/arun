<?php
/**
 * This file is part of "Bosun - CLI Php Microframework" released under the following terms
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
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/bosun
 *
 * This class is the abstract base class for creating Domains.
 *
 * Date: 21/09/18
 * Time: 11.48
 */

namespace BosunCore\Abstracts;

use BosunCore\Interfaces\Domain\DomainInterface;

abstract class BaseDomainCommand implements DomainInterface
{
    /**
     * @Inject("BosunCore\Interfaces\IO\ConsoleInputInterface")
     *
     * @var \BosunCore\Interfaces\IO\ConsoleInputInterface
     */
    public $cIn;

    /**
     * @Inject("BosunCore\Interfaces\IO\ConsoleOutputInterface")
     *
     * @var \BosunCore\Interfaces\IO\ConsoleOutputInterface
     */
    public $cOut;

    /**
     * @Inject("BosunCore\Interfaces\Core\HelpGeneratorInterface");
     *
     * @var \BosunCore\Interfaces\Core\HelpGeneratorInterface
     */
    public $helpGen;

    /**
     * Contains the BasePath of the application (where the runner is started)
     * @var string
     */
    protected $basePath;

    /**
     * The name of the Domain
     *
     * @var string
     */
    protected $commandName;

    /**
     * Stores the content of WhiteList / HelpFile
     *
     * @var array
     */
    protected $helpContent;

    /**
     * Check if an option (-i, --info, etc. is present)
     *
     * @param $optionName
     * @return bool
     */
    public function hasOption(string $optionName): bool
    {
        return $this->cIn->hasOption($optionName);
    }

    /**
     * Check if an option is present and return its value (-i, --info=something, etc.)
     *
     * @param string $optionName
     * @return bool|mixed
     */
    public function getOptionValue(string $optionName)
    {
        return $this->cIn->getOption($optionName);
    }

    /**
     * Basic help command
     *
     * @return mixed
     */
    abstract function help();
}
