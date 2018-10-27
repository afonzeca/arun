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
 **/

namespace ArunCore\Abstracts;

use ArunCore\Traits\Generics\MixedCollectionTrait;

use Countable;
use Iterator;

abstract class GenericMixedCollectionAbstract implements Countable, Iterator
{
    use MixedCollectionTrait;

    /**
     * @return bool
     */
    abstract public function valid();

    /**
     * GenericMixedCollection constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->elements = [];
    }

    /**
     * @param string $element
     * @param string $nameId
     *
     * @return void
     *
     * @throws \Exception
     */
    public function set(string $element, string $nameId = null): void
    {
        $nameId != null ? $this->elements[$nameId] = $element : $this->elements[] = $element;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get(string $key): string
    {
        return $this->elements[$key];
    }

    /**
     * @param string $nameId
     *
     * @return bool
     */
    public function remove(string $nameId): bool
    {
        if (array_key_exists($nameId, $this->elements) === false) {
            return false;
        }

        unset($this->elements[$nameId]);
        return false;
    }

    /**
     * @return mixed|null
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * @return mixed|null
     */
    public function rewind()
    {
        return reset($this->elements);
    }

}