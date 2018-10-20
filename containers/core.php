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
 *
 * Bosun Internal Application Container
 *
 *
 * Date: 27/09/18
 * Time: 13.20
 */

use BosunCore\Interfaces\Core\HelpGeneratorInterface;
use BosunCore\Interfaces\IO\ConsoleInputInterface;
use BosunCore\Interfaces\IO\ConsoleOutputInterface;
use BosunCore\Interfaces\Domain\DomainActionExecutorInterface;
use BosunCore\Interfaces\Domain\DomainActionNameGeneratorInterface;
use BosunCore\Interfaces\Domain\DomainUtilsInterface;
use BosunCore\Interfaces\Core\BosunCoreInterface;
use BosunCore\Interfaces\Security\SanitizerInterface;

return [

    BosunCoreInterface::class => DI\autowire("BosunCore\\Core\\BosunCore"),

    SanitizerInterface::class => DI\get("BosunCore\\Core\\Helpers\\Sanitizer"),

    DomainUtilsInterface::class => DI\autowire("BosunCore\\Core\\Domain\\DomainUtils"),

    DomainActionExecutorInterface::class => DI\autowire("BosunCore\\Core\\Domain\\DomainActionExecutor"),

    ConsoleInputInterface::class => DI\autowire("BosunCore\\Core\\IO\\ConsoleInput")
        ->constructorParameter("args", $_SERVER["argv"]),

    ConsoleOutputInterface::class => DI\autowire("BosunCore\\Core\\IO\\ConsoleOutput")
        ->constructorParameter("enableColors", getenv("COLORS")),

    DomainActionNameGeneratorInterface::class => DI\autowire("BosunCore\\Core\\Domain\\DomainActionNameGenerator")
        ->constructorParameter("whiteListName", "%s/".conf("whiteListName"))
        ->constructorParameter("basePath", ((new SplFileInfo(__DIR__))->getRealPath()) . "/../"),

    HelpGeneratorInterface::class => DI\autowire("BosunCore\\Core\\Helpers\\HelpGenerator")
        ->constructorParameter("helpContent", include(sprintf("%s".conf("whiteListName"), (new SplFileInfo(__DIR__))->getRealPath() . "/../")))

];