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
 * Action Synopsis - Action functionality description for CLI help
 */
class ActionSyn
{
    /**
     * @var string
     */
    public $synopsis;

    public function __construct(array $synopsis)
    {
        $this->synopsis = $synopsis["value"];
    }
}