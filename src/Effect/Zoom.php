<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Zoom
 * @package Photon\Wrapper\Effect
 */
class Zoom extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $zoom;

    public function __construct($zoom = 2)
    {
        $this->zoom = $zoom;
    }
}