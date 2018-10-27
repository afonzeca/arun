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
 * @Target("CLASS")
 *
 * Allows:
 *
 * Set if a Domain and its methods can be called from CLI
 */
class DomainEnabled
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