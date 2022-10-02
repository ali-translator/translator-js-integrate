<?php

namespace ALI\TranslatorJsIntegrate\Tests\unit;

use ALI\Translator\PlainTranslator\PlainTranslatorFactory;
use ALI\TranslatorJsIntegrate\ALIAbcTranslatorJs;
use ALI\TranslatorJsIntegrate\Tests\components\Factories\SourceFactory;
use ALI\TranslatorJsIntegrate\TranslatorJs;
use PHPUnit\Framework\TestCase;

class ALIAbsTranslatorJsTest extends TestCase
{
    public function test()
    {
        $sourceFactory = new SourceFactory();
        $source = $sourceFactory->generateSource($sourceFactory::ORIGINAL_LANGUAGE_ALIAS, $sourceFactory::TRANSLATION_LANGUAGE_ALIAS);
        $plainTranslator = (new PlainTranslatorFactory())->createPlainTranslator($source, $sourceFactory::TRANSLATION_LANGUAGE_ALIAS);

        $translatorJs = new TranslatorJs();
        $ALIAbcTranslatorJs = new ALIAbcTranslatorJs($plainTranslator, $translatorJs);

        $this->checkEmptyTranslate($ALIAbcTranslatorJs);

        $this->checkWithExistTranslate($ALIAbcTranslatorJs);
    }

    /**
     * @param ALIAbcTranslatorJs $aLIAbsTranslatorJs
     */
    private function checkEmptyTranslate(ALIAbcTranslatorJs $aLIAbsTranslatorJs)
    {
        $aLIAbsTranslatorJs->addOriginalText('Hello');

        $startupJs = $aLIAbsTranslatorJs->generateStartupJs();

        $expectCode = '(function(t,w) {
    var translationData = {"ua":[]};
    if (typeof w.ALIABCTranslator___t === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\');
        w.ALIABCTranslator___t = new t.Translator(\'en\', \'ua\', textTemplateDecoder, translationData);
        w.__t = w.ALIABCTranslator___t.translate.bind(w.ALIABCTranslator___t);
    } else {
        w.ALIABCTranslator___t.addTranslations(translationData);
    }
})(window.modules.translator,window);';
        $this->assertEquals($expectCode, $startupJs);

        $aLIAbsTranslatorJs->getPlainTranslator()->getSource()->delete('Hello');
    }

    /**
     * @param ALIAbcTranslatorJs $aLIAbsTranslatorJs
     */
    private function checkWithExistTranslate(ALIAbcTranslatorJs $aLIAbsTranslatorJs)
    {
        $aLIAbsTranslatorJs->getPlainTranslator()->saveTranslate('Hello', 'Привіт');
        $startupJs = $aLIAbsTranslatorJs->generateStartupJs();

        $aLIAbsTranslatorJs->getPlainTranslator()->getSource()->delete('Hello');

        $expectCode = '(function(t,w) {
    var translationData = {"ua":{"Hello":"Привіт"}};
    if (typeof w.ALIABCTranslator___t === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'{\',\'}\');
        w.ALIABCTranslator___t = new t.Translator(\'en\', \'ua\', textTemplateDecoder, translationData);
        w.__t = w.ALIABCTranslator___t.translate.bind(w.ALIABCTranslator___t);
    } else {
        w.ALIABCTranslator___t.addTranslations(translationData);
    }
})(window.modules.translator,window);';

        $this->assertEquals($expectCode, $startupJs);
    }
}
