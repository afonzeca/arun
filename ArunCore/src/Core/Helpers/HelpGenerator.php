<?php
/**
 * This file is part of "Arun - CLI Php Microframework" released under the following terms
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
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/Arun
 *
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/Arun
 *
 * Generates the help screen
 *
 * Date: 18/10/18
 * Time: 20.26
 */

namespace ArunCore\Core\Helpers;

use ArunCore\Interfaces\Core\HelpGeneratorInterface;

class HelpGenerator implements HelpGeneratorInterface
{
    /**
     * @Inject("ArunCore\Interfaces\IO\ConsoleOutputInterface")
     *
     * @var \ArunCore\Interfaces\IO\ConsoleOutputInterface
     */
    private $cOut;

    /**
     * @var array
     */
    private $helpContent;

    /**
     * HelpGenerator constructor.
     * @param array $helpContent
     */
    public function __construct(array $helpContent)
    {
        $this->helpContent = $helpContent;
    }

    /**
     * Generates different messages according to the "global" param
     *
     * @param $domain
     * @param $classInstance
     * @throws \ReflectionException
     */
    public function makeHelpMessage($domain, $classInstance, $global = false)
    {
        $help = $this->helpContent[$domain];

        $this->helpMessageHeader($domain, $help);

        if (!$global) {
            $actions = $this->helpContent[$domain]["actions"];
            $this->helpMessageBody($classInstance, $domain, $actions);
            return;
        }

        $this->helpMessageGlobalBody($this->helpContent);
    }

    /**
     * Thanks to reflection, the method analyzes every method (action) from the class(domain)
     * and describe them according to parameter characteristic (e.g. type hint and if it is optional)
     *
     * @param  \Reflector[] $parameters
     * @return bool
     */
    private function printParameterSyntax(array $parameters): bool
    {
        if (count($parameters) > 0) {
            foreach ($parameters as $param) {

                $sc = "<";
                $ec = ">";
                $default = "";

                if ($param->isOptional()) {
                    $sc = "[";
                    $ec = "]";
                    $default = $param->getDefaultValue();
                }

                $this->cOut->write($sc . $param->name);

                if ($default != "") {
                    echo "=$default";
                }
                $this->cOut->write($ec . " ");
            }

            return true;
        }

        return false;
    }

    /**
     * @param $domain
     * @param $help
     */
    private function helpMessageHeader($domain, $help): void
    {
        $this->cOut->blank();
        $this->cOut->writeln("#RED#Arun Microframework " . getenv("APP_VERSION") . "#DEF# - (C) 2018 by Angelo Fonzeca (Apache License 2.0)");
        $this->cOut->blank();
        $this->cOut->writeln("#LGRAY#" . ucfirst($domain) . ": " . $help["general"]["description"] . "#DEF#");
        $this->cOut->blank();

    }

    /**
     *
     * This method uses the ConsoleOutput for producing the help file on the screen for a specific domain
     *
     * @param $classInstance
     * @param $actions
     * @throws \ReflectionException
     */
    private function helpMessageBody($classInstance, $domain, $actions): void
    {
        $this->cOut->writeln("Usage: ");
        $this->cOut->blank();
        $this->cOut->writeln("  #BLUE#$domain#DEF#:#BLUE#ACTION#DEF# [options] [arguments]");
        $this->cOut->blank();

        $this->cOut->writeln("Where ACTIONs are:");
        $this->cOut->blank();
        $class = new \ReflectionClass($classInstance);

        foreach ($actions as $name => $desc) {

            $this->cOut->writeln("#CYAN#" . $name . "#DEF#");
            if (isset($desc[0])) {
                $this->cOut->write("  #LGRAY#Description:#DEF# " . $desc[0] . "\r\n");
            }


            if (isset($desc[1])) {
                $this->cOut->write("  #LGRAY#Parameters#DEF# : ");
                $this->cOut->write($desc[1] . " ");
            }


            if ($this->printParameterSyntax($class->getMethod($name)->getParameters())) {
                $this->cOut->blank();
            }

            $this->cOut->blank();
        }
    }

    /**
     * This method users the ConsoleOutput for producing the help file on the screen for all the domains (global help)
     *
     * @param array $help
     */
    private function helpMessageGlobalBody(array $help): void
    {
        $this->cOut->writeln("Usage: ");
        $this->cOut->blank();
        $this->cOut->writeln("  #BLUE#DOMAIN#DEF#:#BLUE#ACTION#DEF# [options] [arguments]");
        $this->cOut->blank();
        $this->cOut->writeln("Available DOMAINS (set of aggregate commands) are:");
        $this->cOut->blank();

        foreach ($help as $name => $item) {

            $this->cOut->write("  #BLUE#" . $name . "#DEF#");
            if (isset($item["general"])) {
                $this->cOut->write(": " . $item["general"]["description"] . "\r\n");
            }
            $this->cOut->blank();
        }

        $this->cOut->writeln("Please write " . $_SERVER["SCRIPT_FILENAME"] . " DOMAIN:help to list the ACTIONS available for a DOMAIN");
        $this->cOut->blank();
    }

}