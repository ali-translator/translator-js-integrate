# Js integration of `php AliAbc Translator` to frontend 


Additional packet for [ali-translator/translator](https://github.com/ali-translator/translator) which help integrate this tanslator to frontend js.


## Installation

```bash
$ composer require ali-translator/translator-js-integrate
```

## Init

Since this package extended from <b>[ali-translator/translator](https://github.com/ali-translator/translator)</b>,
at first you need create `$translator` and wrapper, with vector of his translation - `$plaiTranslator`

Than include `src/assets/js/ali-translator.js` script in your html code.<br>
After that, create instance of `ALIAbsTranslatorJs`:
```php
use ALI\TranslatorJsIntegrate\ALIAbcTranslatorJs;
use ALI\Translator\PlainTranslator\PlainTranslator;

/** @var PlainTranslator $plainTranslator */

$ALIAbcTranslatorJs = new ALIAbcTranslatorJs($plainTranslator);
```   

## Basic Usage
php:
```php
use \ALI\TranslatorJsIntegrate\ALIAbcTranslatorJs;

/** @var ALIAbcTranslatorJs $aLIAbsTranslatorJs */

// $aliAbc->saveTranslate('Hello {object}!', 'Привіт {object}!');

$aLIAbsTranslatorJs->addOriginals(['Hello {object}!']);
$startUpJsCode = $aLIAbsTranslatorJs->generateStartupJs('__t');
```
html:
```html
<html>
<head>
<script src="/js/ali-translator.js"></script>
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
### Tests
In packet exist docker-compose file, with environment for testing.
```bash
docker-compose run php composer install
docker-compose run php vendor/bin/phpunit
```
