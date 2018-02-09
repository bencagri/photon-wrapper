<?php

namespace Photon\Wrapper\Effect;

/**
 * Class EffectAbstract
 * @package Photon\Wrapper\Effect
 */
abstract class EffectAbstract
{

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}