<?php

use Photon\Wrapper\Effect\Crop;
use Photon\Wrapper\Effect\Filter;
use Photon\Wrapper\Generate;
use Photon\Wrapper\Effect\Height;
use Photon\Wrapper\Effect\Width;
use Photon\Wrapper\Effects;

error_reporting(0); // Disable all errors.
require 'vendor/autoload.php';


$effects = new Effects(
    new Width(700),
    new Filter('emboss')
);

$imageUrl = 'https://images.pexels.com/photos/6269/dinner-meal-table-wine.jpg?w=1260&h=750&dpr=2&auto=compress&cs=tinysrgb';

$process = new Generate($imageUrl,$effects);
//dump($process->generateQueryArguments($effects)); exit;
$process->process();