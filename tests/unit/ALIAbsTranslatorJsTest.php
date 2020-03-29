<?php

namespace ALI\TranslationJsIntegrate\Tests\unit;

use ALI\Translation\Helpers\QuickStart\ALIAbFactory;
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
        $aliAbc = (new ALIAbFactory())->createALIByCsvSource(SOURCE_CSV_PATH, 'en', 'ua');
        $aLIAbsTranslatorJs = (new ALIAbcTranslatorJsFactory())->createALIAbsTranslatorJs($aliAbc);
        $this->checkEmptyTranslate($aLIAbsTranslatorJs);

        $this->checkWithExistTranslate($aLIAbsTranslatorJs);
    }

    /**
     * @param ALIAbcTranslatorJs $aLIAbsTranslatorJs
     * @return string
     */
    private function checkEmptyTranslate(ALIAbcTranslatorJs $aLIAbsTranslatorJs)
    {
        $aLIAbsTranslatorJs->addOriginalText('Hello');

        $startupJs = $aLIAbsTranslatorJs->generateStartupJs();

        $expectCode = '(function() {
  var translatorModules = window.modules.translator,
      textTemplateDecoder = new translatorModules.TextTemplateDecoder(\'{\',\'}\'),
      translator = new translatorModules.Translator("en", "ua", textTemplateDecoder, {"ua":{"Hello":""}});
      __t = translator.translate.bind(translator);
})();';
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

        $expectCode = '(function() {
  var translatorModules = window.modules.translator,
      textTemplateDecoder = new translatorModules.TextTemplateDecoder(\'{\',\'}\'),
      translator = new translatorModules.Translator("en", "ua", textTemplateDecoder, {"ua":{"Hello":"Привіт"}});
      __t = translator.translate.bind(translator);
})();';
        $this->assertEquals($expectCode, $startupJs);
    }
}
