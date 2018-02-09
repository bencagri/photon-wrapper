<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Resize
 * @package Photon\Wrapper\Effect
 */
class Resize extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;

    public function __construct($x = 100, $y = 100)
    {

        $this->x = $x;
        $this->y = $y;
    }
}