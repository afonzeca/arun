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

namespace ArunCore\Core\Collections;

use ArunCore\Abstracts\GenericMixedCollectionAbstract;
use ArunCore\Traits\Generics\OOPHelpersTrait;

class FlatListCollection extends GenericMixedCollectionAbstract
{

    use OOPHelpersTrait;

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
        parent::set($element, $nameId);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (($this->current() !== null && $this->current() !==false) ? true : false);
    }

}