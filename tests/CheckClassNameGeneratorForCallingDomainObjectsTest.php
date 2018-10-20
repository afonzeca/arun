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
     * Initialise fake whitelist and parameters
     */
    public function setUp()
    {
        $this->fakeParameters = [
            "bosun",
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

        $this->whiteListCustom = [
            "car" => [
                "actions" => [
                    "check" => "Check the car",
                    "on" => "Turn on the car",
                    "accelerate" => "Increment speed",
                    "go" => "Let's start"
                ],
                "general" => [
                    "description" => "Car checker"
                ]
            ]
        ];
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfTheGeneratedCommandIsCorrectAccordingToInputAndWhiteList()
    {
        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($this->fakeParameters, $this->whiteListCustom);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Managers\\Cmd\\CarDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("check", $classAndMethodNamesForCalling[1]);
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfAWrongActionIsSetTheApplicationRedirectsToHelp()
    {
        $this->fakeParameters[1] = "car:wrongAction";
        $nameGenerator = $this->getGeneratorAccordingToWhiteListAndDomainAction($this->fakeParameters, $this->whiteListCustom);

        $classAndMethodNamesForCalling = $nameGenerator->getClassAndMethodNamesForCalling();

        $this->assertEquals("\\App\\Managers\\Cmd\\CarDomain", $classAndMethodNamesForCalling[0]);
        $this->assertEquals("help", $classAndMethodNamesForCalling[1]);
    }

    /**
     * @param $whiteListCustom
     * @return \PHPUnit\Framework\MockObject\MockObject
     *
     * @throws
     */
    private function getGeneratorAccordingToWhiteListAndDomainAction($parameters, $whiteListCustom): \PHPUnit\Framework\MockObject\MockObject
    {
        $consoleInput = $this->getMockBuilder("BosunCore\Core\IO\ConsoleInput")
            ->setConstructorArgs([$parameters])
            ->setMethods(null)
            ->getMock();

        $nameGenerator = $this->getMockBuilder("BosunCore\Core\Domain\DomainActionNameGenerator")
            ->setConstructorArgs(["/fakeWhitListName", "/fakeBasePath", $consoleInput])
            ->setMethods(["getWhiteList"])
            ->getMock();

        $nameGenerator->setCustomWhitelistArray($this->whiteListCustom);
        $nameGenerator->method("getWhiteList")->willReturn($whiteListCustom);

        return $nameGenerator;
    }
}