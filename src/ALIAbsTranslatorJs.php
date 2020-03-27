<?php

namespace ALI\TranslationJsIntegrate;

use ALI\Translation\Translate\PhrasePackets\OriginalPhrasePacket;
use ALI\Translation\Translate\Translators\TranslatorInterface;

/**
 * SliTranslatorJs
 */
class ALIAbsTranslatorJs
{
    /**
     * @var OriginalPhrasePacket
     */
    protected $originalPhrasePacket;

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
        $this->originalPhrasePacket = new OriginalPhrasePacket();
    }

    /**
     * @param array $texts
     */
    public function addOriginals(array $texts)
    {
        foreach ($texts as $text) {
            $this->originalPhrasePacket->add($text);
        }
    }

    /**
     * @param string $text
     */
    public function addOriginalText($text)
    {
        $this->originalPhrasePacket->add($text);
    }

    /**
     * @param string $translateAliasJsVariableName
     * @return string
     */
    public function generateStartupJs($translateAliasJsVariableName = '__t')
    {
        $translationsPacket = $this->translator->translateAll($this->originalPhrasePacket->getAll());

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
