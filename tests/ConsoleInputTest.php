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
 * UnitTest for the ConsoleInput class
 *
 */

use PHPUnit\Framework\TestCase;
use ArunCore\Core\IO\ConsoleInput;

use ArunCore\Interfaces\IO\ConsoleInputInterface;

class ConsoleInputTest extends TestCase
{

    /**
     * @var array
     */
    public $fakeParameters;

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
     * Check if the number of options are well extracted by the code
     *
     * @test
     *
     * @throws Exception
     */
    public function checkIfNumberOfOptionsMatchesWithExtractedSets()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals(2, count($cIn->extractShortOptionsWithParams()));
        $this->assertEquals(3, count($cIn->extractLongOptionsWithValues()));
        $this->assertEquals(4, count($cIn->extractShortOptionsVoid()));
        $this->assertEquals(2, count($cIn->extractLongOptionsVoid()));
    }

    /**
     * Check for smallOptionValue validity
     *
     * @test
     *
     * @throws Exception
     */
    public function checkShortOptionsWithValuesCorrectExtraction()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals("on", $cIn->getShortOptionValuedOnly("l"));
        $this->assertEquals("off", $cIn->getShortOptionValuedOnly("r"));
        $this->assertEquals(null, $cIn->getShortOptionValuedOnly("wrongOptionName"));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkShortOptionsWithVoidValuesExtraction()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertTrue($cIn->hasShortOptionVoid("t"));
        $this->assertTrue($cIn->hasShortOptionVoid("v"));
        $this->assertTrue($cIn->hasShortOptionVoid("m"));
        $this->assertTrue($cIn->hasShortOptionVoid("q"));
        $this->assertFalse($cIn->hasShortOptionVoid("p"));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkLongOptionsWithValuesExtraction()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals(10, $cIn->getLongOptionValuedOnly("engine-start-at-speed"));
        $this->assertEquals(100, $cIn->getLongOptionValuedOnly("engine-end-at-speed"));
        $this->assertEquals("front", $cIn->getLongOptionValuedOnly("check-light"));
        $this->assertEquals(null, $cIn->getLongOptionValuedOnly("wrongOptionName"));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkLongOptionsWithVoidValuesExtraction()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertTrue($cIn->hasLongOptionVoid("check-breaks"));
        $this->assertTrue($cIn->hasLongOptionVoid("check-key"));
        $this->assertFalse($cIn->hasLongOptionVoid("wrongOptionName"));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkIfNumberOfParamsIsCorrect()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals(2, count($cIn->getParams()));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkIfAllParamsAreCorrect()
    {
        $expectedArray = [
            "Red",
            "Sport"
        ];

        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $realArray = $cIn->getParams();

        sort($expectedArray);
        sort($realArray);

        $this->assertEquals(json_encode($expectedArray), json_encode($realArray));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkIfGlobalOptionsAreAvailable()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals(10, $cIn->getOption("engine-start-at-speed"));
        $this->assertEquals("off", $cIn->getOption("r"));
        $this->assertTrue($cIn->hasOption("check-key"));
        $this->assertTrue($cIn->hasOption("t"));
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfTheDomainNameIsExtractedCorrectly()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals("car", $cIn->getDomainName());
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfTheActionNameIsExtractedCorrectly()
    {
        $cIn = $this->getConsoleInputWithParams($this->fakeParameters);

        $this->assertEquals("check", $cIn->getActionName());
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function checkIfADomainOrActionAreNotInfluencedByWhiteList()
    {
        $fakeParameters = [
            "Arun",
            "car:x",
        ];

        // Gets a new instance despite the setUp method
        $cIn = $this->getConsoleInputWithParams($fakeParameters);
        $this->assertEquals("car", $cIn->getDomainName());
        $this->assertEquals("x", $cIn->getActionName());
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfDefaultAndHelpValuesAreSetIfDomainAndActionAreNotPresent()
    {
        $fakeParameters = [
            "Arun",
        ];

        // Gets a new instance despite the setUp method
        $cIn = $this->getConsoleInputWithParams($fakeParameters);
        $this->assertEquals("default", $cIn->getDomainName());
        $this->assertEquals("help", $cIn->getActionName());
    }

    /**
     * @test
     *
     * @throws
     */
    public function checkIfDomainAndDefaultValuesAreSetIfDomainOnlyIsSpecified()
    {
        $fakeParameters = [
            "Arun",
            "car"
        ];

        // Gets a new instance despite the setUp method
        $cIn = $this->getConsoleInputWithParams($fakeParameters);
        $this->assertEquals("car", $cIn->getDomainName());
        $this->assertEquals("default", $cIn->getActionName());
    }

    /**
     * @test
     *
     * @throws
     */
    public function callDomainActionWithoutParams()
    {
        $fakeParameters = [
            "Arun",
            "test:actionwithnoparams"
        ];

        // Gets a new instance despite the setUp method
        $cIn = $this->getConsoleInputWithParams($fakeParameters);
        $this->assertEquals("test", $cIn->getDomainName());
        $this->assertEquals("actionwithnoparams", $cIn->getActionName());
    }

    /**
     * @param $fakeParameters
     * @return ConsoleInputInterface
     * @throws Exception
     */
    private function getConsoleInputWithParams($fakeParameters): ConsoleInputInterface
    {
        return new ConsoleInput($fakeParameters);
    }

}