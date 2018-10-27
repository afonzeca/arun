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
 */

namespace ArunCore\Traits\Generics;

trait MixedCollectionTrait
{

    /**
     * @var array
     */
    private $elements;

    /**
     * @param  $theClosure
     * @return bool
     *
     * @throws \ReflectionException
     */
    private function isClosure($theClosure): bool
    {
        return (new \ReflectionFunction($theClosure))->isClosure();
    }

    public function toArray()
    {
        return $this->elements;
    }

    public function keys(): array
    {
        return array_keys($this->elements);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function isEmpty(): bool
    {
        return (bool)count($this->elements);
    }

    public function destroyContent()
    {
        $this->elements = [];
    }

    public function current()
    {
        return current($this->elements);
    }

    public function key()
    {
        return key($this->elements);
    }

    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->elements);
    }

}
