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
 *
 * This class contains low level methods for analyzing classes and objects via PHP Reflection
 *
 * Date: 17/10/18
 * Time: 12.20
 */

namespace BosunCore\Core\Domain;

use \BosunCore\Interfaces\Domain\DomainUtilsInterface;

class DomainUtils implements DomainUtilsInterface
{
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

}