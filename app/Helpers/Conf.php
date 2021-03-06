<?php
/**
 * This file is part of "This file is part of "Arun - CLI Microframework for Php7.2+" released under the following terms" released under the following terms
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
 */

namespace App\Helpers\Conf;

/**
 * Get the configuration from the config.php file
 *
 * @param $key
 * @return mixed
 *
 */
function get($key)
{
    $env = (array)(require __DIR__ . "/../../config/config.php");

    try {
        if (!array_key_exists($key, $env)) {
            throw new Exception("No existing key", -1);
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }

    return $env[$key];
}
