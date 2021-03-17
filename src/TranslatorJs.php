<?php

namespace ALI\TranslatorJsIntegrate;

/**
 * TranslatorJs
 */
class TranslatorJs
{
    /**
     * @var string
     */
    protected $originalLanguageAlias;

    /**
     * @var string
     */
    protected $translationLanguageAlias;

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
     * @param string $originalLanguageAlias
     * @param string $defaultTranslationLanguageAlias
     * @param string $templateVariableStart
     * @param string $templateVariableEnd
     */
    public function __construct(
        $originalLanguageAlias,
        $defaultTranslationLanguageAlias,
        $templateVariableStart = '{',
        $templateVariableEnd = '}'
    )
    {
        $this->originalLanguageAlias = $originalLanguageAlias;
        $this->translationLanguageAlias = $defaultTranslationLanguageAlias;
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
     * @param string $translateAliasJsVariableName
     * @return string
     */
    public function generateRegisterJs($translateAliasJsVariableName = '__t')
    {
        $originalLanguageAlias = $this->originalLanguageAlias;
        $currentLanguage = $this->translationLanguageAlias;
        $translations = json_encode($this->translations, JSON_UNESCAPED_UNICODE);

        $templateVariableStart = $this->templateVariableStart;
        $templateVariableEnd = $this->templateVariableEnd;

        $js = '(function(t,w) {
    if (typeof w.ALIABCTranslator_' . $translateAliasJsVariableName . ' === \'undefined\') {
        var textTemplateDecoder = new t.TextTemplateDecoder(\'' . $templateVariableStart . '\',\'' . $templateVariableEnd . '\');
        w.ALIABCTranslator_' . $translateAliasJsVariableName . ' = new t.Translator(\'' . $originalLanguageAlias . '\', \'' . $currentLanguage . '\', textTemplateDecoder, ' . $translations . ');
        w.' . $translateAliasJsVariableName . ' = w.ALIABCTranslator_' . $translateAliasJsVariableName . '.translate.bind(w.ALIABCTranslator_' . $translateAliasJsVariableName . ');
    } else {
        w.ALIABCTranslator_' . $translateAliasJsVariableName . '.addTranslations(' . $translations . ');
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
