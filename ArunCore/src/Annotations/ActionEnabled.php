<?php
/**
 * Created by PhpStorm.
 * User: angelo
 * Date: 26/10/18
 * Time: 11.05
 */

namespace ArunCore\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 *
 * Set if an Action is public and can be called from CLI
 *
 */
class ActionEnabled
{
    /**
     * @var bool
     */
    public $enabled;

    public function __construct(array $action)
    {
        $this->enabled = (bool)($action["value"]);
    }

}