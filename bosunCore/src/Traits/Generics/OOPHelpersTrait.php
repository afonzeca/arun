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
 * Date: 24/09/18
 * Time: 19.13
 */

namespace BosunCore\Traits\Generics;

use \ReflectionFunction;

trait OOPHelpersTrait
{
    /**
     * @param Closure $theClosure
     * @return bool
     * @throws \ReflectionException
     */
    private function isClosure($theClosure): bool
    {
        return (bool)(new ReflectionFunction($theClosure))->isClosure();
    }

    /**
     * @param string $className
     * @return bool
     * @throws \ReflectionException
     */
    public function implementsInterface(string $className, string $interfaceName): bool
    {
        $class = new \ReflectionClass($className);
        return $class->implementsInterface($interfaceName);
    }


}