<?php

namespace Photon\Wrapper\Effect;


class Filter extends EffectAbstract implements EffectInterface
{

    private $available = [
        'grayscale','negate','sepia','edgedetect','emboss','blurgaussian','blurselective','meanremoval'
    ];

    /**
     * @var string
     */
    protected $filter;


    public function __construct($filter = 'grayscale')
    {
        $this->filter = in_array($filter,$this->available) ? $filter : false;
    }
}