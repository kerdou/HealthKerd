export default class UrlRegex
{
    public urlRegex(urlValue: string): boolean {
        let urlPattern =
            '((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9.\\-]+|(?:www.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9.\\-]+)((?:\\/[\\+~%\\/.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@.\\w_]*)#? (?:[\\w]*))?)';
        let urlModifier = 'i';
        let urlRegex = new RegExp(urlPattern, urlModifier); // création du regex

        urlValue = urlValue.trim();
        return urlRegex.test(urlValue);
    }
}