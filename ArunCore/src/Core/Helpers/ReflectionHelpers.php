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
 * Linkedin contact ( https://www.linkedin.com/in/angelo-f-1806868/ ) - Project @ https://github.com/afonzeca/Arun
 *
 *
 * This class contains low level methods for analyzing classes and objects via PHP Reflection
 *
 * Date: 17/10/18
 * Time: 12.20
 */

namespace ArunCore\Core\Helpers;

use \ArunCore\Interfaces\Helpers\ReflectionHelpersInterface;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;

class ReflectionHelpers implements ReflectionHelpersInterface
{

    /**
     * @var Reader
     */
    private $aReader;

    /**
     * ReflectionHelpers constructor.
     *
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->aReader = $annotationReader;
    }

    /**
     * Check if the number of parameters from command line
     * is the same required from DOMAIN:ACTION class
     *
     * @param string $className
     * @param string $action
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function numberOfMandatoryParameters(string $className, string $action)
    {
        $reflection = new \ReflectionMethod($className, $action);

        $numberOfRequiredParameters = $reflection->getNumberOfRequiredParameters();

        return $numberOfRequiredParameters;
    }

    /**
     * Recast all command line parameters to the class parameters that
     * corresponds to DOMAIN:ACTION required from CLI
     *
     * @param string $className
     * @param string $action
     * @param array $realParameters
     *
     * @return array
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getReCastedParameters(string $className, string $action, array $realParameters): array
    {
        $numOfMandatoryParams = $this->numberOfMandatoryParameters($className, $action);

        if ($numOfMandatoryParams !== FALSE) {
            $reflection = new \ReflectionMethod(
                $className,
                $action
            );

            $reflectionParams = $reflection->getParameters();

            return $this->recastParamsFromMethodDefinitions($reflectionParams, $realParameters);
        }

        return [];
    }

    /**
     * Recast every parameters according to the Class definition
     *
     * @param \ReflectionParameter[] $methodParamsList
     * @param $commandLineParameters
     * @param $mandatoryParams
     * @return mixed
     *
     * TODO: Must be optimized!
     */
    private function recastParamsFromMethodDefinitions(
        array $methodParamsList,
        array $commandLineParameters
    ): array
    {

        $paramPos = 0;
        $maxNumOfParams = count($commandLineParameters);

        foreach ($methodParamsList as $param) {

            $methodParamType = $param->getType();

            if ($methodParamType == "") {
                $methodParamType = "string";
            }

            if ($paramPos < $maxNumOfParams) {
                $key = key($commandLineParameters);
                settype($commandLineParameters[$key], $methodParamType);
                next($commandLineParameters);
                $paramPos++;
            }
        }
        return $commandLineParameters;
    }

    /**
     * @param $domain
     * @param $action
     * @return bool
     * @throws \ReflectionException
     */
    public function isActionEnabled($domainFQDN, $action)
    {
        $rClass = $this->getReflectionClassFromFQDN($domainFQDN);
        $rMethod = $rClass->getMethod($action);

        $actionStatus = $this->aReader->getMethodAnnotation($rMethod, "\ArunCore\Annotations\ActionEnabled");

        if ($actionStatus !== null) {
            return $actionStatus->enabled;
        }

        return false;
    }

    /**
     * @param $antReader
     * @param $method
     * @param $actions
     * @return mixed
     */
    public function getEnabledActionsAnnotations($methodsList)
    {
        $antReader = $this->aReader;

        $actions = [];
        foreach ($methodsList as $method) {

            $isActionEnabled = $antReader->getMethodAnnotation($method, "\ArunCore\Annotations\ActionEnabled");

            if ($isActionEnabled != null && $isActionEnabled->enabled == true) {

                $actions[$method->name][0] = "No description available for the action";
                $actions[$method->name][1] = "";

                $methodAnnotations = $antReader->getMethodAnnotations($method);

                foreach ($methodAnnotations AS $annotation) {

                    if ($annotation instanceof \ArunCore\Annotations\ActionSyn) {
                        $actions[$method->name][0] = $annotation->synopsis;
                    }

                    if ($annotation instanceof \ArunCore\Annotations\ActionOption) {
                        $actions[$method->name]["options"][] = explode(":", $annotation->optionValueDescr);
                    }

                }
            }
        }

        return $actions;
    }

    /**
     * @param string $className
     * @param string $domain
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public function getDomainSynopsis(string $className, string $domain): string
    {
        $rClass = $this->getReflectionClassFromFQDN($className);

        $domainSynopsis = $this->aReader->getClassAnnotation($rClass, "\ArunCore\Annotations\DomainSyn");
        if ($domainSynopsis != null) {
            return $domainSynopsis->synopsis;
        }

        return "No synopsis for this domain";
    }

    /**
     * @param string $domain
     * @return bool
     *
     * @throws
     */
    public function isDomainEnabled(string $domain): bool
    {
        $rClass = $this->getReflectionClassFromDomain($domain);

        $domainStatus = $this->aReader->getClassAnnotation($rClass, "\ArunCore\Annotations\DomainEnabled");
        if ($domainStatus != null) {
            return $domainStatus->enabled;
        }

        return false;
    }

    /**
     * @param string $className
     * @param string $domain
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function getClassPublicMethods(string $className, string $domain)
    {
        $rClass = $this->getReflectionClassFromFQDN($className);

        return $rClass->getMethods(\ReflectionMethod::IS_PUBLIC);
    }

    /**
     * @param $domain
     * @return \Reflector
     * @throws \ReflectionException
     */
    public function getReflectionClassFromDomain(string $domain): \Reflector
    {
        return new \ReflectionClass($this->makeDomainFQDN($domain));
    }

    /**
     * @param $domainFQDN
     * @return \Reflector
     * @throws \ReflectionException
     */
    public function getReflectionClassFromFQDN(string $domainFQDN): \Reflector
    {
        return new \ReflectionClass($domainFQDN);
    }

    /**
     * @param $domain
     * @return string
     */
    public function makeDomainFQDN(string $domain): string
    {
        return sprintf("\App\Console\Domains\%s%s", ucfirst($domain), "Domain");
    }

}