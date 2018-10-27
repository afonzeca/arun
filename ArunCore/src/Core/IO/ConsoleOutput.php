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
 * Basic Output Console class
 *
 * Date: 18/10/18
 * Time: 19.44
*/

namespace ArunCore\Core\IO;

use ArunCore\Interfaces\IO\ConsoleOutputInterface;

class ConsoleOutput implements ConsoleOutputInterface
{

    private $enableColors;

    /**
     * ConsoleOutput constructor.
     *
     * @param string $enableColors
     */
    public function __construct(string $enableColors)
    {
        $this->enableColors = (bool)$enableColors;
    }
    /**
     * @param string $text
     * @return mixed
     */
    protected function convertColors(string $text)
    {
        $colorTableCodes = "";
        $colorTableNames = ["#BLACK#", "#BLUE#", "#GREEN#", "#CYAN#", "#RED#", "#PURPLE#", "#BROWN#", "#LGRAY#", "#DGRAY#", "#LBLUE#", "#LGREEN#", "#DEF#"];

        if ($this->enableColors) {
            $colorTableCodes = ["\033[30m", "\033[34m", "\033[32m", "\033[36m", "\033[31m", "\033[35m", "\033[33m", "\033[37m", "\033[30m", "\033[34m", "\033[32m", "\033[0m"];
        }

        return str_replace($colorTableNames, $colorTableCodes, $text);
    }

    /**
     * @param string $string
     */
    public function write(string $string)
    {
        echo $this->convertColors($string);
    }

    /**
     * @param string $string
     */
    public function writeln(string $string)
    {
        echo $this->convertColors($string) . "\r\n";
    }

    public function blank()
    {
        echo "\r\n";
    }

}