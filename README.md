# img-spoof

[![Version](https://img.shields.io/packagist/v/aalfiann/img-spoof.svg)](https://packagist.org/packages/aalfiann/img-spoof)
[![Downloads](https://img.shields.io/packagist/dt/aalfiann/img-spoof.svg)](https://packagist.org/packages/aalfiann/img-spoof)
[![License](https://img.shields.io/packagist/l/aalfiann/img-spoof.svg)](https://github.com/aalfiann/img-spoof/blob/HEAD/LICENSE)

This is a very simple and fast to get an image with spoofing referer.

## Installation

1. Install this package via [Composer](https://getcomposer.org/).
```
composer create-project aalfiann/img-spoof [my-app-name]
```

2. Upload to your server

3. Done

**Note:**  
- See `config.php` to adjust GMT time and max-age for cache.

## Query Parameter
- **url** : URL Image target.
- **referer** : Set the referer image. [`optional`]
- **mime** : Set the mime type of image (ex: png). [`optional`]

**Note:**  
- Mime will detect automatically if the url included extension.

## Example

This will GET image with spoofing referer.  
See `example.php`.

```html
<html>
    <head>
    </head>
    <body>
    <img src="./index.php?referer=https://manganelo.com&url=https://s7.mkklcdnv7.com/mangakakalot/l2/love_parameter/chapter_112_qa/1.jpg">
    </body>
</html>
```
