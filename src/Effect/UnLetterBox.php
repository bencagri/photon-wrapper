<?php

namespace Photon\Wrapper\Effect;

/**
 * Class LetterBox
 * @package Photon\Wrapper\Effect
 */
class UnLetterBox extends EffectAbstract implements EffectInterface
{

    /**
     * @var bool
     */
    protected $ulb;

    public function  __construct($ulb = true)
    {
        $this->ulb = $ulb;
    }

}