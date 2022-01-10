# Image with Overlay in php (no CSS)

**You can do amazing things with CSS’s mix-blend-modes, pseudo-elements and more. And you probably should! But faced with serious performance issues when applying many effects to many DOM elements, I went looking for an alternative solution.**


This php script generates a unique image with baked in overlay from source images, and outputs them as a PNG. This solves two problems: 

1. Eliminate the performance- and repaint penalty of having to use CSS to overlay an image with another (or div or pseudo-element) and applying various filters, opacities, etc.
Doing so in CSS impacts performance negatively, and causes a repaint/recalculation with every scroll, forcing especially slower devices into their knees, and impacting user-experience negatively.

2. By creating image overlays server-side, we are serving up identically looking images across all browsers and platforms. This ensures a maximum of backwards compatibility.
I am also able to control WCAG color-contrast compliance, by making sure a sufficiently adjusted image is served up every time, even if CSS is disabled or not supported.

I’m generating the overlay as a PNG on the fly with php, then using imagemagick to combine it with the original source image.

So to the browser it just acts like a single image, no CSS filters necessary, and it allows for easy control of both the source image, and the overlay with just get parameters!

