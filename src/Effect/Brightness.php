<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Brightness
 * @package Photon\Wrapper\Effect
 */
class Brightness extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $brightness;

    public function __construct($brightness = 0)
    {
        $this->brightness = $brightness;
    }
}