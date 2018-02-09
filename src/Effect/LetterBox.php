<?php

namespace Photon\Wrapper\Effect;

/**
 * Class LetterBox
 * @package Photon\Wrapper\Effect
 */
class LetterBox extends EffectAbstract implements EffectInterface
{

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    public function  __construct($width = 300, $height = 300)
    {

        $this->width = $width;
        $this->height = $height;
    }

}