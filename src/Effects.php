<?php

namespace Photon\Wrapper;

/**
 * Class Effects
 * @package Photon\Wrapper
 */
class Effects
{

    /**
     * @var
     */
    protected $effects;

    public function __construct(...$effects)
    {
        foreach ($effects as $effect) {
            $this->effects[] = $effect;
        };
    }

    /**
     * @return mixed
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @param mixed $effects
     */
    public function setEffects($effects)
    {
        $this->effects = $effects;
    }

}