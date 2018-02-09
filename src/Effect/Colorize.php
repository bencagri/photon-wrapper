<?php

namespace Photon\Wrapper\Effect;


/**
 * Class Colorize
 * @package Photon\Wrapper\Effect
 */
class Colorize extends EffectAbstract implements EffectInterface
{

    /**
     * @var
     */
    protected $r;
    /**
     * @var
     */
    protected $g;
    /**
     * @var
     */
    protected $b;

    public function __construct($r, $g, $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

}