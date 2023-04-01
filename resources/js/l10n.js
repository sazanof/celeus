function initializeL10n() {
    if (window.L10N === undefined) {
        window.L10N = {
            locale: getLocale(),
            translations: {}
        }
    }
}

export function getLocale() {
    return document.documentElement.lang || navigator.language || navigator.userLanguage
}

export function registerTranslationObject(app, translationObject) {
    Object.assign(window.L10N.translations, {
        [app]: translationObject
    })
}

export function getTranslationObject(app) {
    return window.L10N.translations[app]
}

export function deleteTranslationObject(app) {
    delete window.L10N.translations[app]
}

export function translate(app, text) {
    // todo pluralization
    const appTranslation = getTranslationObject(app)
    return appTranslation.hasOwnProperty(text) ? appTranslation[text] : text
}

initializeL10n()