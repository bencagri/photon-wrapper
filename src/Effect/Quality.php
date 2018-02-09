<?php

namespace Photon\Wrapper\Effect;

/**
 * Class Quality
 * @package Photon\Wrapper\Effect
 */
class Quality extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $quality;

    public function __construct($quality = 100)
    {
        $this->quality = $quality;
    }
}