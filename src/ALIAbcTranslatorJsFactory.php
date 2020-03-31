<?php

namespace ALI\TranslationJsIntegrate;

use ALI\Translation\ALIAbc;
use ALI\Translation\Buffer\KeyGenerators\StaticKeyGenerator;

/**
 * Class
 */
class ALIAbcTranslatorJsFactory
{
    /**
     * @param ALIAbc $aliAbc
     * @return ALIAbcTranslatorJs
     * @throws \ALI\Translation\Exceptions\TranslateNotDefinedException
     */
    public function createALIAbcTranslatorJs(ALIAbc $aliAbc, $templateVariableStart = null, $templateVariableEnd = null)
    {
        $templatesKeyGenerator = $aliAbc->getTemplatesKeyGenerator();

        if (!$templateVariableStart) {
            if ($templatesKeyGenerator instanceof StaticKeyGenerator) {
                $templateVariableStart = $templatesKeyGenerator->getKeyPrefix();
            } else {
                $templateVariableStart = '{';
            }
        }
        if (!$templateVariableEnd) {
            if ($templatesKeyGenerator instanceof StaticKeyGenerator) {
                $templateVariableEnd = $templatesKeyGenerator->getKeyPostfix();
            } else {
                $templateVariableEnd = '}';
            }
        }

        $originalLanguageAlias = $aliAbc->getTranslator()->getSource()->getOriginalLanguageAlias();
        $currentLanguageAlias = $aliAbc->getCurrentLanguageAlias();
        $translatorJs = new TranslatorJs($originalLanguageAlias, $currentLanguageAlias, $templateVariableStart, $templateVariableEnd);

        return new ALIAbcTranslatorJs($aliAbc->getTranslator(), $translatorJs);
    }
}
