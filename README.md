# Image with Overlay in php (no CSS)

*please see file for more documentation on how it works :)*

php Script that will generate an custom overlay on an image, and output it as a PNG. 

This script prevents having to use CSS to overlay and image with another (or div or pseudo element) and applying various filters, opacities, etc. Doing so in CSS impacts performance negatively, and causes a repaint/recalculation with every scroll.

Instead, I’m generating the overlay as a PNG on the fly with php, then using imagemagick to combine it with the original source image 

So to the browser it just acts like a single image, no css-filters necesary, and it allows for easy control of both the source image, and the overlay with just get parameters!
