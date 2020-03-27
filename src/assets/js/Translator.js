window.modules = window.modules || {};
window.modules.translator = window.modules.translator || {};

(function (translatorModules) {

    /**
     * Translator
     *
     * @param {string} defaultLanguage
     * @param {string} currentLanguage
     * @param {TextTemplateDecoder} textTemplateDecoder
     * @param {Object} translations
     *  Example: {'ua':{'some text {parameter}' : 'деякий текст {parameter}'}}
     * @constructor
     */
    var Translator = function (
        defaultLanguage,
        currentLanguage,
        textTemplateDecoder,
        translations
    ) {
        this.defaultLanguage = defaultLanguage;
        this.currentLanguage = currentLanguage;
        this.textTemplateDecoder = textTemplateDecoder;
        this.translations = translations;
    };

    /**
     * @param {string} text
     * @param {Object} [parameters]
     * @param {string} [language]
     */
    Translator.prototype.translate = function (text, parameters, language) {
        language = language ? language : this.currentLanguage;

        // Translate text
        if (this.defaultLanguage !== language) {
            var translateText = this.__getTranslate(text, language);
            if (typeof translateText !== "string") {
                translateText = text;
            }
        } else {
            translateText = text;
        }

        // Apply parameters
        if (typeof parameters === "object") {
            translateText = this.textTemplateDecoder.decode(translateText, parameters);
        }

        return translateText;
    };

    /**
     * @param {string} text
     * @param {string} language
     * @return {*}
     * @private
     */
    Translator.prototype.__getTranslate = function (text, language) {
        if (typeof this.translations[language] === "undefined") {
            return undefined;
        }
        if (typeof this.translations[language][text] === "undefined") {
            return undefined;
        }

        return this.translations[language][text];
    };

    /**
     * Change current language
     *
     * @param currentLanguage
     */
    Translator.prototype.setCurrentLanguage = function (currentLanguage) {
        this.currentLanguage = currentLanguage;
    };

    // Alias function Interface for IDE
    var __t = Translator.prototype.translate;

    translatorModules.Translator = Translator;

})(window.modules.translator);
