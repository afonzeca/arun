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
 * Arun Internal Application Container
 *
 */

use ArunCore\Interfaces\Core\HelpGeneratorInterface;
use ArunCore\Interfaces\IO\ConsoleInputInterface;
use ArunCore\Interfaces\IO\ConsoleOutputInterface;
use ArunCore\Interfaces\Domain\DomainActionExecutorInterface;
use ArunCore\Interfaces\Domain\DomainActionNameGeneratorInterface;
use ArunCore\Interfaces\Core\ArunCoreInterface;
use ArunCore\Interfaces\Helpers\LowLevelHelperInterface;
use ArunCore\Interfaces\Security\SanitizerInterface;

return [

    ArunCoreInterface::class => DI\autowire("ArunCore\\Core\\ArunCore"),

    SanitizerInterface::class => DI\get("ArunCore\\Core\\Helpers\\Sanitizer"),

    DomainActionExecutorInterface::class => DI\autowire("ArunCore\\Core\\Domain\\DomainActionExecutor"),

    LowLevelHelperInterface::class => DI\get("ArunCore\\Core\\Helpers\\LowLevelHelper"),

    ConsoleInputInterface::class => DI\autowire("ArunCore\\Core\\IO\\ConsoleInput")
        ->constructorParameter("args", $_SERVER["argv"]),

    ConsoleOutputInterface::class => DI\autowire("ArunCore\\Core\\IO\\ConsoleOutput")
        ->constructorParameter("enableColors", getenv("COLORS")),

    DomainActionNameGeneratorInterface::class => DI\autowire("ArunCore\\Core\\Domain\\DomainActionNameGenerator"),

    HelpGeneratorInterface::class => DI\autowire("ArunCore\\Core\\Helpers\\HelpGenerator"),

    Doctrine\Common\Annotations\Reader::class => DI\get("Doctrine\Common\Annotations\AnnotationReader")
];
