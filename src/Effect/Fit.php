<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Fit
 * @package Photon\Wrapper\Effect
 */
class Fit extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $w;
    /**
     * @var int
     */
    protected $h;

    public function __construct($w = 300, $h = 300)
    {
        $this->w = $w;
        $this->h = $h;
    }
}