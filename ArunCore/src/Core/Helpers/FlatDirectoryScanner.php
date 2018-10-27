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

namespace ArunCore\Core\Helpers;

use \DirectoryIterator;
use ArunCore\Core\Collections\FlatListCollection;

class FlatDirectoryScanner
{

    /**
     * @var string $directory
     */
    private $directory;

    /**
     * @var FlatListCollection
     */
    private $filenameCollection;

    /**
     * @param string $directory
     * @param FlatListCollection $fileNamesCollection
     *
     * @throws \Exception
     */
    public function __construct(string $directory, FlatListCollection $fileNamesCollection)
    {
        $this->directory = $directory;
        $this->filenameCollection = $fileNamesCollection;
    }

    /**
     * @throws \Exception
     */
    public function doScan(callable $parser = null)
    {
        foreach (new DirectoryIterator($this->directory) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $this->filenameCollection->set(
                    $parser == null ? $fileInfo->getFileName() : $parser($fileInfo->getFilename())
                );
            }
        }
    }

    /**
     * @return FlatListCollection
     */
    public function getPopulatedFilename(): FlatListCollection
    {
        return $this->filenameCollection;
    }

}
