var UrlRegex = /** @class */ (function () {
    function UrlRegex() {
    }
    UrlRegex.prototype.urlRegex = function (urlValue) {
        // ((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9.\-]+|(?:www.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9.\-]+)((?:\/[\+~%\/.\w\-_]*)?\??(?:[\-\+=&;%@.\w_]*)#? (?:[\w]*))?)
        var urlPattern = '((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9.\\-]+|(?:www.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9.\\-]+)((?:\\/[\\+~%\\/.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@.\\w_]*)#? (?:[\\w]*))?)';
        var urlModifier = 'i';
        var urlRegex = new RegExp(urlPattern, urlModifier); // création du regex
        urlValue = urlValue.trim();
        return urlRegex.test(urlValue);
    };
    return UrlRegex;
}());
export default UrlRegex;
