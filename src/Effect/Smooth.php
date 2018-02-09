<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Smooth
 * @package Photon\Wrapper\Effect
 */
class Smooth extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $smooth;

    public function __construct($smooth = 0)
    {
        $this->smooth = $smooth;
    }
}