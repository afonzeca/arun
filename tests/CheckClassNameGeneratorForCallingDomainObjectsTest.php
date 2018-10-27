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
 * UnitTest class for checking the correct generation of the class that manages a DOMAIN required
 *
 * Date: 18/10/18
 * Time: 12.08
 */

use PHPUnit\Framework\TestCase;

class CheckClassNameGeneratorForCallingDomainObjectsTest extends TestCase
{
    /**
     * @var array
     */
    public $fakeParameters;

    /**
     * @var array
     */
    public $whiteListCustom;

    /**
     * Initialise fake parameters
     */
    public function setUp()
    {
        $this->fakeParameters = [
            "Arun",
            "car:check",
            "Red",
            "Sport",
            "--check-breaks",
            "--engine-start-at-speed=10",
            "--check-key",
            "-t",
            "--check-light=front",
            "-l=on",
            "--engine-end-at-speed=100",
            "-r=off",
            "-v",
            "-m",
            "-q"
        ];

    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfTheGeneratedCommandIsCorrectAccordingToInput()
    {
        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($this->fakeParameters);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Console\\Domains\\CarDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("check", $classAndMethodNamesForCalling[2]);
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfSingleDomainWithoutActionSetActionToDefault()
    {
        $this->fakeParameters[1]="Camion";
        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($this->fakeParameters);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Console\\Domains\\CamionDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("default", $classAndMethodNamesForCalling[2]);
    }

    /**
     * @test
     *
     * @throws
     */
    public function callDomainActionWithoutParams()
    {
        $localFakeParameters = [
            "Arun",
            "test:actionwithnoparams"
        ];

        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($localFakeParameters);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Console\\Domains\\TestDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("actionwithnoparams", $classAndMethodNamesForCalling[2]);
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfCommandActionAndParametersAreNotSetRedirectsToDefaultWithActionHelp()
    {
        unset($this->fakeParameters[1]);
        unset($this->fakeParameters[2]);
        unset($this->fakeParameters[3]);

        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($this->fakeParameters);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Console\\Domains\\DefaultDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("help", $classAndMethodNamesForCalling[2]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     *
     * @throws
     */
    private function getGeneratorAccordingToWhiteListAndDomainAction($parameters): \PHPUnit\Framework\MockObject\MockObject
    {
        $consoleInput = $this->getMockBuilder("ArunCore\Core\IO\ConsoleInput")
            ->setConstructorArgs([$parameters])
            ->setMethods(null)
            ->getMock();

        $nameGenerator = $this->getMockBuilder("ArunCore\Core\Domain\DomainActionNameGenerator")
            ->setConstructorArgs([$consoleInput])
            ->setMethods(null)
            ->getMock();

        return $nameGenerator;
    }
}