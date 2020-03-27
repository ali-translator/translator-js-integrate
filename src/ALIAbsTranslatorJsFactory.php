<?php

namespace ALI\TranslationJsIntegrate;

use ALI\Translation\ALIAbc;
use ALI\Translation\Buffer\KeyGenerators\StaticKeyGenerator;

/**
 * Class
 */
class ALIAbsTranslatorJsFactory
{
    /**
     * @param ALIAbc $aliAbc
     * @return ALIAbsTranslatorJs
     * @throws \ALI\Translation\Exceptions\TranslateNotDefinedException
     */
    public function createALIAbsTranslatorJs(ALIAbc $aliAbc, $templateVariableStart = null, $templateVariableEnd = null)
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

        return new ALIAbsTranslatorJs($aliAbc->getTranslator(), $translatorJs);
    }
}
