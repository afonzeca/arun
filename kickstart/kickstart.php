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
 * Low level startup... for OOP code see ArunCore ;-)
 *
 * Date: 28/09/18
 * Time: 11.38
 */

use DI\ContainerBuilder;

/**
 * Let's start Autoloading!
 */
require __DIR__ . "/../vendor/autoload.php";

/**
 * Load Application Helpers
 */
require __DIR__ . "/../app/Helpers/Conf.php";

/**
 * Initialize ENV
 *
 * ENV is loaded everytime... I will create a Domain for managing Arun itself!
 */
$dotenv = new Dotenv\Dotenv(__DIR__."/../", '.envcli');
$dotenv->overload();

/**
 * Container PATHS
 */
$coreContainer = __DIR__ . '/../containers/core.php';
$appContainer = __DIR__ . '/../containers/app.php';

/**
 * Build the container
 */
try {

    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->useAnnotations(true);
    $builder->addDefinitions($coreContainer);
    $builder->addDefinitions($appContainer);
    $container = $builder->build();

} catch (\Exception $ex) {
    die("Cannot instantiate Container!\r\n\r\n" . $ex->getMessage());
}

return $container;
