<?php

namespace ALI\TranslationJsIntegrate\Tests\unit;

use ALI\Translation\Helpers\QuickStart\ALIAbcFactory;
use ALI\Translation\Translate\Sources\Exceptions\SourceException;
use ALI\TranslationJsIntegrate\ALIAbcTranslatorJs;
use ALI\TranslationJsIntegrate\ALIAbcTranslatorJsFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class
 */
class ALIAbsTranslatorJsTest extends TestCase
{
    /**
     * @throws SourceException
     * @throws \ALI\Translation\Exceptions\TranslateNotDefinedException
     * @throws \ALI\Translation\Translate\Sources\Exceptions\CsvFileSource\UnsupportedLanguageAliasException
     */
    public function test()
    {
        $aliAbc = (new ALIAbcFactory())->createALIByCsvSource(SOURCE_CSV_PATH, 'en', 'ua');
        $aLIAbsTranslatorJs = (new ALIAbcTranslatorJsFactory())->createALIAbcTranslatorJs($aliAbc);
        $this->checkEmptyTranslate($aLIAbsTranslatorJs);

        $this->checkWithExistTranslate($aLIAbsTranslatorJs);
    }

    /**
     * @param ALIAbcTranslatorJs $aLIAbsTranslatorJs
     */
    private function checkEmptyTranslate(ALIAbcTranslatorJs $aLIAbsTranslatorJs)
    {
        $aLIAbsTranslatorJs->addOriginalText('Hello');

        $startupJs = $aLIAbsTranslatorJs->generateStartupJs();

        $expectCode = '(function(t) {
  var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\'),
      translator = new t.Translator("en", "ua", textTemplateDecoder, {"ua":{"Hello":""}});
      __t = translator.translate.bind(translator);
})(window.modules.translator);';
        $this->assertEquals($expectCode, $startupJs);

        $aLIAbsTranslatorJs->getTranslator()->getSource()->delete('Hello');
    }

    /**
     * @param ALIAbcTranslatorJs $aLIAbsTranslatorJs
     * @throws SourceException
     */
    private function checkWithExistTranslate(ALIAbcTranslatorJs $aLIAbsTranslatorJs)
    {
        $aLIAbsTranslatorJs->getTranslator()->saveTranslate('Hello', 'Привіт');
        $startupJs = $aLIAbsTranslatorJs->generateStartupJs();

        $aLIAbsTranslatorJs->getTranslator()->getSource()->delete('Hello');

        $expectCode = '(function(t) {
  var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\'),
      translator = new t.Translator("en", "ua", textTemplateDecoder, {"ua":{"Hello":"Привіт"}});
      __t = translator.translate.bind(translator);
})(window.modules.translator);';

        $this->assertEquals($expectCode, $startupJs);
    }
}
