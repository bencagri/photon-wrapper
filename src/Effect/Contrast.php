<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Contrast
 * @package Photon\Wrapper\Effect
 */
class Contrast extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $contrast;

    public function __construct($contrast = 0)
    {
        $this->contrast = $contrast;
    }
}