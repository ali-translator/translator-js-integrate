<?php

namespace ALI\TranslationJsIntegrate;

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
    protected $currentLanguageAlias;

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
     * @param string $currentLanguageAlias
     * @param string $templateVariableStart
     * @param string $templateVariableEnd
     */
    public function __construct($originalLanguageAlias, $currentLanguageAlias, $templateVariableStart = '{', $templateVariableEnd = '}')
    {
        $this->originalLanguageAlias = $originalLanguageAlias;
        $this->currentLanguageAlias = $currentLanguageAlias;
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
    public function addTranslations($languageAlias, array $languageTranslations)
    {
        $this->translations[$languageAlias] = $languageTranslations + $this->translations[$languageAlias];
    }

    /**
     * @param string $translateAliasJsVariableName
     * @return string
     */
    public function generateRegisterJs($translateAliasJsVariableName = '__t')
    {
        $defaultLanguage = $this->originalLanguageAlias;
        $currentLanguage = $this->currentLanguageAlias;
        $translations = json_encode($this->translations, JSON_UNESCAPED_UNICODE);

        $templateVariableStart = $this->templateVariableStart;
        $templateVariableEnd = $this->templateVariableEnd;

        $js = <<<JS
(function() {
  var translatorModules = window.modules.translator,
      textTemplateDecoder = new translatorModules.TextTemplateDecoder('$templateVariableStart','$templateVariableEnd'),
      translator = new translatorModules.Translator("$defaultLanguage", "$currentLanguage", textTemplateDecoder, $translations);
      $translateAliasJsVariableName = translator.translate.bind(translator);
})();
JS;
        return $js;
    }

    /**
     * @return string
     */
    public function getCurrentLanguageAlias()
    {
        return $this->currentLanguageAlias;
    }
}
