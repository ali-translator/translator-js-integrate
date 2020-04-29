<?php

namespace ALI\TranslationJsIntegrate;

use ALI\Translation\Translate\PhrasePackets\OriginalPhraseCollection;
use ALI\Translation\Translate\Translators\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var TranslatorJs
     */
    protected $translatorJs;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, TranslatorJs $translatorJs)
    {
        $this->translator = $translator;
        $this->translatorJs = $translatorJs;
        $this->originalPhraseCollection = new OriginalPhraseCollection();
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
        $translationsPacket = $this->translator->translateAll($this->originalPhraseCollection->getAll());

        $this->translatorJs->setTranslationsByLanguages([
            $this->translator->getLanguageAlias() => $translationsPacket->getAll()
        ]);

        return $this->translatorJs->generateRegisterJs($translateAliasJsVariableName);
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }
}
