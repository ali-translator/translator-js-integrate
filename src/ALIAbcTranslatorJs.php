<?php

namespace ALI\TranslatorJsIntegrate;

use ALI\Translator\PhraseCollection\OriginalPhraseCollection;
use ALI\Translator\PlainTranslator\PlainTranslatorInterface;

/**
 * SliTranslatorJs
 */
class ALIAbcTranslatorJs
{
    /**
     * @var OriginalPhraseCollection
     */
    protected $originalPhraseCollection;

    /**
     * @var PlainTranslatorInterface
     */
    protected $plainTranslator;

    /**
     * @var TranslatorJs
     */
    protected $translatorJs;

    /**
     * @param PlainTranslatorInterface $plainTranslator
     * @param TranslatorJs $translatorJs
     */
    public function __construct(PlainTranslatorInterface $plainTranslator, TranslatorJs $translatorJs)
    {
        $this->plainTranslator = $plainTranslator;
        $this->translatorJs = $translatorJs;
        $this->originalPhraseCollection = new OriginalPhraseCollection($plainTranslator->getSource()->getOriginalLanguageAlias());
    }

    /**
     * @param array $texts
     */
    public function addOriginals(array $texts)
    {
        foreach ($texts as $text) {
            $this->originalPhraseCollection->add($text);
        }
    }

    /**
     * @param string $text
     */
    public function addOriginalText($text)
    {
        $this->originalPhraseCollection->add($text);
    }

    /**
     * @param string $translateAliasJsVariableName
     * @return string
     */
    public function generateStartupJs($translateAliasJsVariableName = '__t')
    {
        $translationsPacket = $this->plainTranslator->translateAll($this->originalPhraseCollection->getAll());

        $translations = [];
        foreach ($translationsPacket->getAll() as $original => $translate) {
            if ($translate) {
                $translations[$original] = $translate;
            }
        }

        $this->translatorJs->setTranslationsByLanguages([
            $this->plainTranslator->getTranslationLanguageAlias() => $translations,
        ]);

        return $this->translatorJs->generateRegisterJs($translateAliasJsVariableName);
    }

    /**
     * @return PlainTranslatorInterface
     */
    public function getPlainTranslator()
    {
        return $this->plainTranslator;
    }
}
