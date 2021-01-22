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

        $expectCode = '(function(t,w) {
    if (typeof w.ALIABCTranslator___t === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\');
        w.ALIABCTranslator___t = new t.Translator(\'en\', \'ua\', textTemplateDecoder, {"ua":[]});
        w.__t = w.ALIABCTranslator___t.translate.bind(w.ALIABCTranslator___t);
    } else {
        w.ALIABCTranslator___t.addTranslations({"ua":[]});
    }
})(window.modules.translator,window);';
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

        $expectCode = '(function(t,w) {
    if (typeof w.ALIABCTranslator___t === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\');
        w.ALIABCTranslator___t = new t.Translator(\'en\', \'ua\', textTemplateDecoder, {"ua":{"Hello":"Привіт"}});
        w.__t = w.ALIABCTranslator___t.translate.bind(w.ALIABCTranslator___t);
    } else {
        w.ALIABCTranslator___t.addTranslations({"ua":{"Hello":"Привіт"}});
    }
})(window.modules.translator,window);';

        $this->assertEquals($expectCode, $startupJs);
    }
}
