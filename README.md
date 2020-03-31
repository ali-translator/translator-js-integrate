# ALIAbc js integrate


Additional packet for [ali-translator/translation](https://github.com/ali-translator/translation) which help integrate tanslator to frontend js.


## Installation

```bash
$ composer require ali-translator/translation-js-integrate
```

## Init

For work, you must include `src/assets/js/ali-translation.js` script in your html code.<br>
After that, create instance of `ALIAbsTranslatorJs`:
```php
use ALI\TranslationJsIntegrate\ALIAbcTranslatorJsFactory;
use ALI\Translation\Helpers\QuickStart\ALIAbFactory;

$aliAbc = (new ALIAbFactory())->createALIByCsvSource(SOURCE_CSV_PATH, 'en', 'ua');
$aLIAbsTranslatorJs = (new ALIAbcTranslatorJsFactory())->createALIAbcTranslatorJs($aliAbc);
```   

## Basic Usage
php:
```php
use \ALI\TranslationJsIntegrate\ALIAbcTranslatorJs;

/** @var ALIAbcTranslatorJs $aLIAbsTranslatorJs */

// $aliAbc->saveTranslate('Hello {object}!', 'Привіт {object}!');

$aLIAbsTranslatorJs->addOriginals(['Hello {object}!']);
$startUpJsCode = $aLIAbsTranslatorJs->generateStartupJs('__t');
```
html:
```html
<html>
<head>
<script src="/js/ali-translation.js"></script>
<script><?= $startUpJsCode ?></script>
</head>
<body>...</body>
</html>
```
js:
```js
alert(__t('Hello {object}!',{
    'object' : 'sun'
}))
```
