<?php

namespace ALI\TranslatorJsIntegrate;

class TranslatorJs
{
    /**
     * @var string
     */
    protected $templateVariableStart = '{';

    /**
     * @var string
     */
    protected $templateVariableEnd = '}';

    /**
     * @var string[][]
     */
    protected $translations;

    /**
     * @param string $templateVariableStart
     * @param string $templateVariableEnd
     */
    public function __construct(
        $templateVariableStart = '{',
        $templateVariableEnd = '}'
    )
    {
        $this->templateVariableStart = $templateVariableStart;
        $this->templateVariableEnd = $templateVariableEnd;
    }

    /**
     * @param \string[][] $translations
     */
    public function setTranslationsByLanguages(array $translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param string $languageAlias
     * @param array $languageTranslations
     */
    public function addTranslations($languageAlias, array $languageTranslations = null)
    {
        $this->translations[$languageAlias] = $languageTranslations + $this->translations[$languageAlias];
    }

    /**
     * @param string $originalLanguageAlias
     * @param string $currentLanguage
     * @param string $translateAliasJsVariableName
     * @return string
     */
    public function generateRegisterJs(
        $originalLanguageAlias,
        $currentLanguage,
        $translateAliasJsVariableName = '__t')
    {
        $translations = json_encode($this->translations, JSON_UNESCAPED_UNICODE);

        $templateVariableStart = $this->templateVariableStart;
        $templateVariableEnd = $this->templateVariableEnd;

        $js = '(function(t,w) {
    var translationData = '.$translations.';
    if (typeof w.ALIABCTranslator_' . $translateAliasJsVariableName . ' === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'' . $templateVariableStart . '\',\'' . $templateVariableEnd . '\');
        w.ALIABCTranslator_' . $translateAliasJsVariableName . ' = new t.Translator(\'' . $originalLanguageAlias . '\', \'' . $currentLanguage . '\', textTemplateDecoder, translationData);
        w.' . $translateAliasJsVariableName . ' = w.ALIABCTranslator_' . $translateAliasJsVariableName . '.translate.bind(w.ALIABCTranslator_' . $translateAliasJsVariableName . ');
    } else {
        w.ALIABCTranslator_' . $translateAliasJsVariableName . '.addTranslations(translationData);
    }
})(window.modules.translator,window);';

        return $js;
    }

    /**
     * @return string
     */
    public function getTranslationLanguageAlias()
    {
        return $this->translationLanguageAlias;
    }
}
