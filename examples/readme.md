### Width

Set the width of an image. Defaults to pixels, supports percentages.

```php
//for pixels
new Width(300);

//for percent
new Width('20%');
```

Original image:<br>
<img src="images/apple-original.jpeg" />

After<br>
<img src="images/apple-width-300.jpeg" />


> Image Source
> https://www.pexels.com/photo/close-up-of-fruits-hanging-on-tree-257840/


### Height
Set the height of an image. Defaults to pixels, supports percentages.
```php
//for pixels
new Height(300);

//for percent
new Height('20%');
```

### Crop
Crop an image by percentages x-offset,y-offset,width,height (x,y,w,h). 
Percentages are used so that you don’t need to recalculate the cropping when transforming the image in other ways such as resizing it.

Original image:<br>
<img src="images/railway-original.jpeg" />

```php
new Crop('250px','250px','700px','600px');

```
250px,250px,700px,700px takes a 700px by 700px rectangle from the source image starting at 250px offset from the left and 250px offset from the top.


After:<br>
<img src="images/railway-crop.jpeg" />

```php
new Crop('160px','25','1400px','60');

```
shows you can also mix the parameters types, for example a 1400 pixels by 60% rectangle from the image starting at 160 pixels by 25%.


> Image Source
> https://www.pexels.com/photo/branches-daylight-environment-flowers-355296/


### Resize
Resize and crop an image to exact width,height pixel dimensions. Set the first number as close to the target size as possible and then crop the rest. Which direction it’s resized and cropped depends on the aspect ratios of the original image and the target size.

```php
new Resize(400,250);

```

This is useful for taking an image of any size and making it fit into a certain location while losing as little of the image as possible.


### Fit
Fit an image to a containing box of width,height dimensions. Image aspect ratio is maintained.

```php
new Fit(300,300);

```

### LetterBox
Add black letterboxing effect to images, by scaling them to width, height while maintaining the aspect ratio and filling the rest with black.

```php
new LetterBox(700,600);

```
Original image: <br>
<img src="images/sea-original.jpeg" />

After:<br>
<img src="images/sea-letterbox.jpeg" />

> Image Source
> https://www.pexels.com/photo/beach-beautiful-bridge-carribean-449627/

### UnLetterBox
Remove black letterboxing effect from images with ulb. This function takes only one argument, true.
```php
new UnLetterBox(true);

```

### Filter
The filter GET parameter is optional and is used to apply one of multiple filters. 
Valid values are: *negate, grayscale, sepia, edgedetect, emboss, blurgaussian, blurselective, meanremoval*.

```php
new Filter($filterName);
```

<p>
    <img src="images/table-original.jpeg" alt>
    <em>original</em>
</p>

<p>
    <img src="images/table-negate.jpeg" alt>
    <em>negate</em>
</p>

<p>
    <img src="images/table-grayscale.jpeg" alt>
    <em>grayscale</em>
</p>
<p>
    <img src="images/table-sepia.jpeg" alt>
    <em>sepia</em>
</p>
<p>
    <img src="images/table-edgedetect.jpeg" alt>
    <em>edgedetect</em>
</p>
<p>
    <img src="images/table-emboss.jpeg" alt>
    <em>emboss</em>
</p>


> Image Source
> https://www.pexels.com/photo/beautifully-served-table-for-dinner-6269/

### Brightness

Adjust the brightness of an image. 
Valid values are -255 through 255 where -255 is black and 255 is white. 
Higher is brighter. The default is zero. `-40` will darken an image by 40 and `80` will brighten an image by 80.

```php
new Brightness(-50);
```

### Contrast
Adjust the contrast contrast of an image. Valid values are -100 through 100. 
The default is zero. `-50` will decrease contrast by 50 and `50` will increase contrast by 50.

```php
new Contrast(20);

```

### Colorize
Add color hues to an image with colorizeby passing a comma separated list of red,green,blue (RGB) values such as 255,0,0 (red), 0,255,0 (green), 0,0,255 (blue).

```php
new Colorize(0,0,100);
```

<p>
    <img src="images/bird-original.jpeg" alt>
    <em>original</em>
</p>

<p>
    <img src="images/bird-colorize-0,0,100.jpeg" alt>
    <em>0,0,100</em>
</p>
<p>
    <img src="images/bird-colorize-0,100,0.jpeg" alt>
    <em>0,100,0</em>
</p>
<p>
    <img src="images/bird-colorize-100,0,0.jpeg" alt>
    <em>100,0,0</em>
</p>

> Image Source
> https://www.pexels.com/photo/bird-animal-white-pigeon-75973/

### Smooth
The smooth parameter can be used to smooth out the image.

```php
new Smooth(1);
```

### Zoom
Use zoom to size images for high pixel ratio devices and browsers when zoomed. Not available to use with crop. Zoom is intended for use by scripts such as devicepx.js which automatically set the zoom level. Valid zoom levels are 1, 1.5, 2-10.

```php
new Zoom(2)
```

### Quality
Use the quality parameter to manage the quality output of JPEG and PNG images. Valid settings are between the values between 20 and 100. For JPEGs a setting of 100 will output the image at the original unaltered quality setting. However when specifying a quality of 100 for PNGs, the image is compressed using lossless compression and produces an image with identical quality as the original. Note that if the requesting web browser supports the WebP image format, then PNG and JPEG images will automatically be converted to the WebP image format by the server. When specifying 100 as the quality in this instance, a lossless compressed image will be produced, which in some instances may result in a file larger than the original.

Note that the default quality setting for JPEGs is 89%, PNGs 80%, and WebP images is 80%.

```php
new Quality(88);
```
