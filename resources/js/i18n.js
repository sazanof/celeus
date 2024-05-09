import { createI18n } from 'vue-i18n'

/**
 * Load locale messages
 *
 * The loaded `JSON` locale messages is pre-compiled by `@intlify/vue-i18n-loader`, which is integrated into `vue-cli-plugin-i18n`.
 * See: https://github.com/intlify/vue-i18n-loader#rocket-i18n-resource-pre-compilation
 */
async function loadLocaleMessages() {

    const locales = await import.meta.glob([ '../locales/*.json' ])
    /*const locales = require.context(
        '../locales',
        true,
        /[A-Za-z0-9-_,\s]+\.json$/i
    )*/
    const messages = {}
    for (const key of Object.keys(locales)) {
        let _key = key.replace('../locales/', '')
        _key = _key.replace('.json', '')
        const matched = _key.match(/([A-Za-z0-9-_]+)/i)
        if (matched && matched.length > 1) {
            const locale = matched[1]
            messages[locale] = await import(`../locales/${_key}.json`)
        }
    }
    return messages
}

//TODO dynamic locale select, based on DB config and user settings
export default async function createI18nFinnaly() {
    await createI18n({
        // TODO доставать локаль по умолчанию аяксом
        legacy: false,
        globalInjection: true,
        locale: await detectLanguage(),
        fallbackLocale: 'en',
        messages: await loadLocaleMessages(),
    })
}

function detectLanguage() {
    const lng = document.documentElement.lang
    const locales = import.meta.glob([ '../locales/*.json' ])
    console.log(locales)
    const lang = Object.keys(locales)
        .find((key) => lng.includes(key.replace('../locales/', '').replace('.json', '')))

    return lang ? lang.replace('../locales/', '').replace('.json', '') : null
}
