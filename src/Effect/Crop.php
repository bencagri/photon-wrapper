<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Crop
 * @package Photon\Wrapper\Effect
 */
class Crop extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;
    /**
     * @var int
     */
    protected $w;
    /**
     * @var int
     */
    protected $h;

    public function __construct($x = 10, $y = 10, $w = 10, $h = 10)
    {

        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }
}