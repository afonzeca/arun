<?php
/**
 * Created by PhpStorm.
 * User: angelo
 * Date: 26/10/18
 * Time: 11.02
 */

namespace ArunCore\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 *
 * Action Option - The option remains globals, this annotation at the moment is user for help only
 *
 * Option is defined as option syntax:description
 *
 * E.g. @ActionOption("--primay-key='primary_key_name':Set the primary key value")
 */
class ActionOption
{
    /**
     * @var string
     */
    public $optionValueDescr;

    public function __construct(array $valueDescr)
    {
        $this->optionValueDescr = $valueDescr["value"];
    }
}