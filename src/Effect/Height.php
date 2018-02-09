<?php


namespace Photon\Wrapper\Effect;

/**
 * Class Height
 * @package Photon\Wrapper\Effect
 */
class Height extends EffectAbstract implements EffectInterface
{
    /**
     * @var int
     */
    protected $h;

    public function  __construct($h = 100)
    {
        $this->h = $h;
    }

}