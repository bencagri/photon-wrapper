<?php

namespace Photon\Wrapper;

use Photon\Wrapper\Effect\EffectInterface;

class Generate
{

    /**
     * @var array
     */
    protected $effectInstances = [
        'w' => '\\Photon\\Wrapper\\Effect\\Width',
        'h' => '\\Photon\\Wrapper\\Effect\\Height',
        'crop' => '\\Photon\\Wrapper\\Effect\\Crop',
        'resize' => '\\Photon\\Wrapper\\Effect\\Resize',
        'fit' => '\\Photon\\Wrapper\\Effect\\Fit',
        'lb' => '\\Photon\\Wrapper\\Effect\\LetterBox',
        'ulb' => '\\Photon\\Wrapper\\Effect\\UnLetterBox',
        'filter' => '\\Photon\\Wrapper\\Effect\\Filter',
        'brightness' => '\\Photon\\Wrapper\\Effect\\Brightness',
        'contast' => '\\Photon\\Wrapper\\Effect\\Contrast',
        'colorize' => '\\Photon\\Wrapper\\Effect\\Colorize',
        'smooth' => '\\Photon\\Wrapper\\Effect\\Smooth',
        'zoom' => '\\Photon\\Wrapper\\Effect\\Zoom',
        'quality' => '\\Photon\\Wrapper\\Effect\\Quality',
    ];

    /**
     * _GET query
     * @var array
     */
    protected $query;

    /**
     * @var string
     */
    protected $image_url;


    public function __construct($imageUrl, $effects)
    {
        $params = $this->generateQueryArguments($effects);
        $this->query = $params;
        $this->image_url = $imageUrl;
    }

    /**
     * @param $effects
     * @return array
     */
    public function generateQueryArguments(Effects $effects)
    {
        $query = [];

        foreach ($effects->getEffects() as $effect) {
            /** @var EffectInterface $effect */
            $argument = $this->getEffectArgument($effect);
            $params = array_values($effect->toArray());
            $query[$argument] = implode(',',$params);
        }

        return $query;
    }

    /**
     * @param EffectInterface $effect
     * @return bool|int|string
     */
    public function getEffectArgument(EffectInterface $effect)
    {
        foreach ($this->effectInstances as $argument => $instance) {
            if ($effect instanceof $instance){
                return $argument;
            }
        }

        return false;
    }

    public function process()
    {
      $output =  new Processor($this->image_url,$this->query);
      $output->process();
    }


}