## JETPACK PHOTON WRAPPER

### What is Photon?

Photon is an image acceleration and modification service for Jetpack-connected WordPress sites. Converted images are cached automatically and served from the WordPress.com CDN. Images can be cropped, resized, and filtered by using a simple API controlled by GET query arguments. When Photon is enabled in Jetpack, images are updated on the fly.

### Why wrapper?

This wrapper allows you to use photon on your host (servers) and its fully object oriented.


### Installation

```
$ composer require bencagri/photon
```

> Notice: to use it on your servers, you need to install [gmagick](https://pecl.php.net/package/gmagick) package

Sample implementation.
```php

<?php

use Photon\Wrapper\Generate;
use Photon\Wrapper\Effect\Height;
use Photon\Wrapper\Effect\Width;
use Photon\Wrapper\Effects;

require 'vendor/autoload.php';

// Set your effects
$effects = new Effects(
    new Width(500),
    new Height(500)
);

$imageUrl = 'http://sample-site.com/sample-image.jpg';

//Generate image with your effects
$process = new Generate($imageUrl,$effects);
$process->process();
```

You can combine multiple effects also.

```php
<?php
use Photon\Wrapper\Effect\Crop;
use Photon\Wrapper\Effect\Filter;
use Photon\Wrapper\Effects;

$crop = new Crop(500,250,330,300);

$effects = new Effects(
    $crop,
    new Filter('emboss')
);
```

Full [documentation](examples/readme.md) of all effects.


###Licence
* The Photon Wrapper is open-sourced software licensed under the MIT license.
* The [Photon](https://code.trac.wordpress.org/browser/photon/LICENSE) is open-sourced software licenced under GNU GENERaL PUBLIC LICENCE
