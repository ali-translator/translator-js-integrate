window.modules = window.modules || {};
window.modules.translator = window.modules.translator || {};

(function (TextTemplateDecoderModules) {

    /**
     * TextTemplateDecoder
     * Merge text with parameters.
     *
     * Example:
     * var textTemplateDecoder = new TextTemplateDecoder('{','}');
     * textTemplateDecoder.decode('You user agent is "{userAgent}"',{'userAgent':'Chrome'});
     * Return: 'You user agent Chrome'
     *
     * @param {string} paramKeyStart
     * @param {string} paramKeyEnd
     * @constructor
     */
    var TextTemplateDecoder = function (paramKeyStart, paramKeyEnd) {
        this.paramKeyStart = paramKeyStart;
        this.paramKeyEnd = paramKeyEnd;
    };

    /**
     * @param {string} text
     * @param {{string}} parameters
     * @return {string}
     */
    TextTemplateDecoder.prototype.decode = function (text, parameters) {
        var paramValue,
            paramKeyTemplate;
        for (var paramName in parameters) {
            if (parameters.hasOwnProperty(paramName)) {
                paramValue = parameters[paramName];
                paramKeyTemplate = this.__generateParamKeyTemplate(paramName);
                text = text.replace(paramKeyTemplate, paramValue);
            }
        }

        return text;
    };

    /**
     * @param parameterName
     * @return {string}
     * @private
     */
    TextTemplateDecoder.prototype.__generateParamKeyTemplate = function (parameterName) {
        return this.paramKeyStart + parameterName + this.paramKeyEnd;
    };

    TextTemplateDecoderModules.TextTemplateDecoder = TextTemplateDecoder;

})(window.modules.translator);
