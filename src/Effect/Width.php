<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Width
 * @package Photon\Wrapper\Effect
 */
class Width extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $w;

    public function __construct($w = 50)
    {

        $this->w = $w;
    }

}