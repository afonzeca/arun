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
 *
 * Process the CLI DOMAIN:ACTION, parameters and options
 *
 * Date: 19/09/18
 * Time: 19.29
 */

namespace ArunCore\Core\IO;

use ArunCore\Interfaces\IO\ConsoleInputInterface;

class ConsoleInput implements ConsoleInputInterface
{

#region properties

    /**
     * Contains the value of the DOMAIN NAME (e.g. DOMAINNAME:*)
     *
     * @var string
     */
    private $domainName;

    /**
     * Contains the value of the ACTION (e.g. *:ACTION)
     *
     * @var string
     */
    private $actionName;

    /**
     * Contains the whole values passed from CLI
     *
     * @var array
     */
    private $rawArgs;

    /**
     * Contains only the values of parameters passed from CLI
     *
     * @var array
     */
    private $options;

    /**
     * Contains only the options (--something) of parameters passed from CLI
     *
     * @var array
     */
    private $params;

    private $shortOptionsValued;
    private $shortOptionsVoid;
    private $longOptionsValued;
    private $longOptionsVoid;

#endregion

    /**
     * CommandLineManager constructor.
     * @param array $args (the whole arguments from command line)
     * @throws \Exception
     */
    public function __construct(
        array $args
    )
    {
        $this
            ->setRawsArgs($args)
            ->initializeOtherValues()
            ->process();
    }

#region Setters (Fluent Interface)

    /**
     * Store DOMAIN name for further requests
     *
     * @param string $commandName
     * @return ConsoleInput
     */
    private function setDomainName(string $commandName): ConsoleInput
    {
        $this->domainName = trim($commandName);

        return $this;
    }

    /**
     * Store ACTION name for further requests
     *
     * @param string $actionName
     * @return ConsoleInput
     */
    private function setActionName(string $actionName): ConsoleInput
    {
        $this->actionName = trim($actionName);

        return $this;
    }

    /**
     * Store the whole command line arguments (ARGV)
     *
     * @param array $args
     * @return ConsoleInput
     */
    private function setRawsArgs(array $args): ConsoleInput
    {
        $this->rawArgs = $args;

        return $this;
    }

    /**
     * Get the arguments list and applies and extract data according to the regexpr passed parameter
     *
     * @param string $pattern
     * @return array
     */
    private function getOptionByRegExpr(string $pattern): array
    {
        $purgedArgs = $this->getRawArgs(true);
        return preg_grep("/" . $pattern . "/", $purgedArgs);
    }

    /**
     * Get short options with value (e.g. -u="FOO")
     * @return array
     */
    public function extractShortOptionsWithParams(): array
    {
        $options = $this->getOptionByRegExpr('\-[A-z0-9][\=][\'"]?[^$"\'{};]*[\'"]?');

        return $this->makeArrayFromOptionsAndValues($options, $this->shortOptionsValued);
    }

    /**
     * @param $key
     * @return string | null
     */
    public function getShortOptionValuedOnly($key)
    {
        if (array_key_exists($key, $this->shortOptionsValued)) {
            return $this->shortOptionsValued[$key];
        }

        return null;
    }

    /**
     * Get long options with value (e.g. --config="/etc/myapp/config.cfg"
     *
     * @return array
     */
    public function extractLongOptionsWithValues(): array
    {
        $options = $this->getOptionByRegExpr('\-\-[^$"{}; ]{2,}\=[\'"]?[^$"{}; ]+[\'"]?');

        return $this->makeArrayFromOptionsAndValues($options, $this->longOptionsValued);
    }

    /**
     * @param $key
     * @return string | null
     */
    public function getLongOptionValuedOnly($key)
    {
        if (array_key_exists($key, $this->longOptionsValued)) {
            return $this->longOptionsValued[$key];
        }

        return null;
    }

    /**
     * Get long options without parameters (e.g. --print --help --check)
     *
     * @return array
     */
    public function extractLongOptionsVoid()
    {
        $options = $this->getOptionByRegExpr('^\-\-[^$"{};=]*[^=]$');

        return $this->makeArrayFromOptionsWithVoidValues($options, $this->longOptionsVoid);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasLongOptionVoid($key): bool
    {
        return array_key_exists($key, $this->longOptionsVoid);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasShortOptionVoid($key): bool
    {
        return array_key_exists($key, $this->shortOptionsVoid);
    }

    /**
     * Get short options without parameters like -i -t -g
     *
     * @return array
     */
    public function extractShortOptionsVoid(): array
    {
        $options = $this->getOptionByRegExpr('^\-{1}[^$"{};=]?[^=]$');
        return $this->makeArrayFromOptionsWithVoidValues($options, $this->shortOptionsVoid);
    }

    /**
     * Store Parameters only inside $this->params property
     *
     * @throws \Exception
     */
    private function analyzeAndSetParams()
    {
        $this->params = array_slice($this->extractCommandLineParamsWithoutOptions(), 2);
    }

    /**
     * @param $key
     * @return string | null
     */
    public function getOption($key)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasOption($key): bool
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Extract DOMAIN:ACTION only
     *
     * @throws \Exception
     *
     * TODO: MUST BE OPTIMIZED!
     */
    private function getDomainActionValues()
    {
        $allParams = $this->extractCommandLineParamsWithoutOptions();

        if (count($allParams) < 2) {
            return null;
        };

        return $allParams[1];

    }

    /**
     * Check DOMAIN:ACTION. If no parameters
     * class "Default" with "help" method will run
     *
     * @return array
     * @throws \Exception
     */
    private function checkArguments(): array
    {
        $domainAction = $this->getDomainActionValues();
        return ($domainAction != null) ?
            $this->extractDomainAction($domainAction) :
            ["default", "help"];
    }

    /**
     * Extract DOMAIN:ACTION parameters. If the ACTION is not present
     * "default" will be called
     *
     * @param $firstParam
     * @return array
     */
    protected function extractDomainAction($firstParam)
    {
        return strpos($firstParam, ":") !== false ?
            explode(":", $firstParam) : [$firstParam, "default"];
    }

#endregion

#region getters

    /**
     * Get the name of the DOMAIN required
     *
     * @return string
     * @throws \Exception
     */
    public function getDomainName(): string
    {
        if ($this->domainName == "") {
            throw new \Exception("No command name!");
        }

        return $this->domainName;
    }

    /**
     * Get the name of action required
     *
     * @return string
     * @throws \Exception
     */
    public function getActionName(): string
    {
        if ($this->actionName == "") {
            throw new \Exception("No action name!");
        }

        return $this->actionName;
    }

    /**
     * Get the list of command line parameters only (e.g. executable, DOMAIN:ACTION, options excluded)
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get the whole list of the arguments passed from command line (executable, DOMAIN:ACTION, parameters, options).
     * If filtered, executable name and ACTION:DOMAIN are escluded form list
     *
     * @param bool $filtered
     * @return array
     */
    public function getRawArgs($filtered = true): array
    {
        return $filtered ? array_slice($this->rawArgs, 2) : $this->rawArgs;
    }

#endregion getters

#region helpers
    /**
     * Set default values to empty for properties before processing command line
     *
     * @return ConsoleInputInterface
     */
    protected function initializeOtherValues(): ConsoleInputInterface
    {
        $this->domainName = "";
        $this->actionName = "";
        $this->options = [];
        $this->params = [];

        return $this;
    }

    /**
     * Separate Options and Values and store them into an array as KEY=>VALUE
     * E.g. --c='/etc/Arun/config.cfg" will be converted in
     *
     * $storeVariable["c"] = '/etc/Arun/config.cfg'
     *
     * @param $source
     * @param $destination
     * @return mixed
     */
    private function makeArrayFromOptionsAndValues($source, &$destination)
    {
        $destination = [];

        foreach ($source as $value) {
            list($k, $v) = explode("=", $value);
            $destination[trim($k, "- ")] = trim($v);
        }

        return $destination;
    }

    /**
     * Generates an array with key from array and a default value (usually "")
     * E.g. --i be converted in
     *
     * $dest["i"] = ""
     *
     * @param $source
     * @param $destination
     * @return mixed
     */
    private function makeArrayFromOptionsWithVoidValues($source, &$destination)
    {
        $destination = [];

        foreach ($source as $option) {
            $destination[trim($option, "- ")] = "";
        }

        return $destination;
    }

    /**
     * Process the command line and set the environment
     *
     * @throws \Exception
     */
    private function process(): void
    {
        list($commandName, $actionName) = $this->checkArguments();

        $this->options = array_merge(
            $this->extractShortOptionsWithParams(),
            $this->extractShortOptionsVoid(),
            $this->extractLongOptionsWithValues(),
            $this->extractLongOptionsVoid()
        );

        $this->setDomainName($commandName);
        $this->setActionName($actionName);
        $this->analyzeAndSetParams();
    }

    /**
     * @return array
     */
    private function extractCommandLineParamsWithoutOptions(): array
    {
        $purgedArgs = $this->getRawArgs(false);

        $allParams = array_values(array_filter(
            $purgedArgs,
            function ($arg) {
                return (strpos($arg, '-') === false) ? true : false;
            }
        ));

        return $allParams;
    }

#endregion helpers
}

