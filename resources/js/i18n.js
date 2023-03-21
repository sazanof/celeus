import { createI18n, useI18n } from 'vue-i18n'
import store from './store'

/**
 * Load locale messages
 *
 * The loaded `JSON` locale messages is pre-compiled by `@intlify/vue-i18n-loader`, which is integrated into `vue-cli-plugin-i18n`.
 * See: https://github.com/intlify/vue-i18n-loader#rocket-i18n-resource-pre-compilation
 */
function loadLocaleMessages() {
    const locales = require.context(
        '../locales',
        true,
        /[A-Za-z0-9-_,\s]+\.json$/i
    )
    const messages = {}
    locales.keys().forEach(key => {
        const matched = key.match(/([A-Za-z0-9-_]+)\./i)
        if (matched && matched.length > 1) {
            const locale = matched[1]
            messages[locale] = locales(key)
        }
    })
    return messages

}

//TODO dynamic locale select, based on DB config and user settings
export default createI18n({
    // TODO доставать локаль по умолчанию аяксом
    legacy: false,
    globalInjection: true,
    locale: detectLanguage(),
    fallbackLocale: 'en',
    messages: loadLocaleMessages(),
})

function detectLanguage() {
    const lng = document.documentElement.lang
    const locales = require.context(
        '../locales',
        true,
        /[A-Za-z0-9-_,\s]+\.json$/i
    )
    const lang = locales
        .keys()
        .find((key) => lng.includes(key.replace('./', '').replace('.json', '')))
    return lang ? lang.replace('./', '').replace('.json', '') : null
}