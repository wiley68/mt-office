import { defineBoot } from '#q-app/wrappers'
import { createI18n } from 'vue-i18n'
import messages from 'src/i18n'

const wpLocale = window.mt_office_rest?.locale || 'en_US'

export default defineBoot(({ app }) => {
  const i18n = createI18n({
    legacy: false,
    locale: wpLocale,
    fallbackLocale: 'en_US',
    globalInjection: true,
    messages
  })

  // Set i18n instance on app
  app.use(i18n)
})
