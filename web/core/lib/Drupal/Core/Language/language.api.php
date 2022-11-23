<?php

/**
 * @file
 * Hooks provided by the base system for language support.
 */

use Drupal\Core\Language\LanguageInterface;

/**
 * @defgroup i18n Internationalization
 * @{
 * Internationalization and translation
 *
 * The principle of internationalization is that it should be possible to make a
 * Drupal site in any language (or a multi-lingual site), where only content in
 * the desired language is displayed for any particular page request. In order
 * to make this happen, developers of modules, themes, and installation profiles
 * need to make sure that all of the displayable content and user interface (UI)
 * text that their project deals with is internationalized properly, so that it
 * can be translated using the standard Drupal translation mechanisms.
 *
 * @section internationalization Internationalization
 * Different @link info_types types of information in Drupal @endlink have
 * different methods for internationalization, and different portions of the
 * UI also have different methods for internationalization. Here is a list of
 * the different mechanisms for internationalization, and some notes:
 * - UI text is always put into code and related files in English.
 * - Any time UI text is displayed using PHP code, it should be passed through
 *   either the global t() function or a t() method on the class. If it
 *   involves plurals, it should be passed through either the global
 *   \Drupal\Core\StringTranslation\PluralTranslatableMarkup::createFromTranslatedString()
 *   or a formatPlural() method on the class. Use
 *   \Drupal\Core\StringTranslation\StringTranslationTrait to get these methods
 *   into a class.
 * - Dates displayed in the UI should be passed through the 'date' service
 *   class's format() method. Again see the Services topic; the method to
 *   call is \Drupal\Core\Datetime\Date::format().
 * - Some YML files contain UI text that is automatically translatable:
 *   - *.routing.yml files: route titles. This also applies to
 *     *.links.task.yml, *.links.action.yml, and *.links.contextual.yml files.
 *   - *.info.yml files: module names and descriptions.
 * - For configuration, make sure any configuration that is displayable to
 *   users is marked as translatable in the configuration schema. Configuration
 *   types label, text, and date_format are translatable; string is
 *   non-translatable text. See the @link config_api Config API topic @endlink
 *   for more information.
 * - For annotation, make sure that any text that is displayable in the UI
 *   is wrapped in \@Translation(). See the
 *   @link plugin_translatable Plugin translatables topic @endlink for more
 *   information.
 * - Content entities are translatable if they have
 *   @code
 *   translatable = TRUE,
 *   @endcode
 *   in their annotation. The use of entities to store user-editable content to
 *   be displayed in the site is highly recommended over creating your own
 *   method for storing, retrieving, displaying, and internationalizing content.
 * - For Twig templates, use 't' or 'trans' filters to indicate translatable
 *   text. See https://www.drupal.org/node/2133321 for more information.
 * - In JavaScript code, use the Drupal.t() and Drupal.formatPlural() functions
 *   (defined in core/misc/drupal.js) to translate UI text.
 * - If you are using a custom module, theme, etc. that is not hosted on
 *   Drupal.org, see
 *   @link interface_translation_properties Interface translation properties topic @endlink
 *   for information on how to make sure your UI text is translatable.
 *
 * @section translation Translation
 * Once your data and user interface are internationalized, the following Core
 * modules are used to translate it into different languages (machine names of
 * modules in parentheses):
 * - Language (language): Define which languages are active on the site.
 * - Interface Translation (locale): Translate UI text.
 * - Content Translation (content_translation): Translate content entities.
 * - Configuration Translation (config_translation): Translate configuration.
 *
 * The Interface Translation module deserves special mention, because besides
 * providing a UI for translating UI text, it also imports community
 * translations from the
 * @link https://localize.drupal.org Drupal translation server. @endlink If
 * UI text and provided configuration in Drupal Core and contributed modules,
 * themes, and installation profiles is properly internationalized (as described
 * above), the text is automatically added to the translation server for
 * community members to translate, via *.po files that are generated by
 * scanning the project files.
 *
 * @section context Translation string sharing and context
 * By default, translated strings are only translated once, no matter where
 * they are being used. For instance, there are many forms with Save
 * buttons on them, and they all would have t('Save') in their code. The
 * translation system will only store this string once in the translation
 * database, so that if the translation is updated, all forms using that text
 * will get the updated translation.
 *
 * Because the source of translation strings is English, and some words in
 * English have multiple meanings or uses, this centralized, shared translation
 * string storage can sometimes lead to ambiguous translations that are not
 * correct for every place the string is used. As an example, the English word
 * "May", in a string by itself, could be part of a list of full month names or
 * part of a list of 3-letter abbreviated month names. So, in languages where
 * the month name for May is longer than 3 letters, you'd need to translate May
 * differently depending on how it's being used. To address this problem, the
 * translation system includes the concept of the "context" of a translated
 * string, which can be used to disambiguate text for translators, and obtain
 * the correct translation for each usage of the string.
 *
 * Here are some examples of how to provide translation context with strings, so
 * that this information can be included in *.po files, displayed on the
 * localization server for translators, and used to obtain the correct
 * translation in the user interface:
 * @code
 * // PHP code
 * t('May', [], ['context' => 'Long month name']);
 * \Drupal::translation()->formatPlural($count, '1 something',
 *   '@count somethings', [], ['context' => 'My context']);
 *
 * // JavaScript code
 * Drupal.t('May', {}, {'context': 'Long month name'});
 * Drupal.formatPlural(count, '1 something', '@count somethings', {},
 *   {'context': 'My context'});
 *
 * // *.links.yml file
 * title: 'May'
 * title_context: 'Long month name'
 *
 * // *.routing.yml file
 * my.route.name:
 *   pattern: '/something'
 *   defaults:
 *     _title: 'May'
 *     _title_context: 'Long month name'
 *
 * // Config schema to say that a certain piece of configuration should be
 * // translatable using the Config Translation API. Note that the schema label
 * // is also translatable, but it cannot have context.
 * date_format:
 *  type: string
 *  label: 'PHP date format'
 *  translatable: true
 *  translation context: 'PHP date format'
 *
 * // Twig template
 * {% trans with {'context': 'Long month name'} %}
 *  May
 * {% endtrans %}
 * @endcode
 *
 * @see transliteration
 * @see t()
 * @}
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Perform alterations on language switcher links.
 *
 * A language switcher link may need to point to a different path or use a
 * translated link text before going through the link generator, which will
 * just handle the path aliases.
 *
 * @param array $links
 *   Nested array of links keyed by language code.
 * @param string $type
 *   The language type the links will switch.
 * @param \Drupal\Core\Url $url
 *   The URL the switch links will be relative to.
 */
function hook_language_switch_links_alter(array &$links, $type, \Drupal\Core\Url $url)
{
    $language_interface = \Drupal::languageManager()->getCurrentLanguage();

    if ($type == LanguageInterface::TYPE_CONTENT && isset($links[$language_interface->getId()])) {
        foreach ($links[$language_interface->getId()] as $link) {
            $link['attributes']['class'][] = 'active-language';
        }
    }
}

/**
 * @} End of "addtogroup hooks".
 */

/**
 * @defgroup transliteration Transliteration
 * @{
 * Transliterate from Unicode to US-ASCII
 *
 * Transliteration is the process of translating individual non-US-ASCII
 * characters into ASCII characters, which specifically does not transform
 * non-printable and punctuation characters in any way. This process will always
 * be both inexact and language-dependent. For instance, the character Ö (O with
 * an umlaut) is commonly transliterated as O, but in German text, the
 * convention would be to transliterate it as Oe or OE, depending on the context
 * (beginning of a capitalized word, or in an all-capital letter context).
 *
 * The Drupal default transliteration process transliterates text character by
 * character using a database of generic character transliterations and
 * language-specific overrides. Character context (such as all-capitals
 * vs. initial capital letter only) is not taken into account, and in
 * transliterations of capital letters that result in two or more letters, by
 * convention only the first is capitalized in the Drupal transliteration
 * result. Also, only Unicode characters of 4 bytes or less can be
 * transliterated in the base system; language-specific overrides can be made
 * for longer Unicode characters. So, the process has limitations; however,
 * since the reason for transliteration is typically to create machine names or
 * file names, this should not really be a problem. After transliteration,
 * other transformation or validation may be necessary, such as converting
 * spaces to another character, removing non-printable characters,
 * lower-casing, etc.
 *
 * Here is a code snippet to transliterate some text:
 * @code
 * // Use the current default interface language.
 * $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
 * // Instantiate the transliteration class.
 * $trans = \Drupal::transliteration();
 * // Use this to transliterate some text.
 * $transformed = $trans->transliterate($string, $langcode);
 * @endcode
 *
 * Drupal Core provides the generic transliteration character tables and
 * overrides for a few common languages; modules can implement
 * hook_transliteration_overrides_alter() to provide further language-specific
 * overrides (including providing transliteration for Unicode characters that
 * are longer than 4 bytes). Modules can also completely override the
 * transliteration classes in \Drupal\Core\CoreServiceProvider.
 */

/**
 * Provide language-specific overrides for transliteration.
 *
 * If the overrides you want to provide are standard for your language, consider
 * providing a patch for the Drupal Core transliteration system instead of using
 * this hook. This hook can be used temporarily until Drupal Core's
 * transliteration tables are fixed, or for sites that want to use a
 * non-standard transliteration system.
 *
 * @param array $overrides
 *   Associative array of language-specific overrides whose keys are integer
 *   Unicode character codes, and whose values are the transliterations of those
 *   characters in the given language, to override default transliterations.
 * @param string $langcode
 *   The code for the language that is being transliterated.
 *
 * @ingroup hooks
 */
function hook_transliteration_overrides_alter(&$overrides, $langcode)
{
    // Provide special overrides for German for a custom site.
    if ($langcode == 'de') {
        // The core-provided transliteration of Ä is Ae, but we want just A.
        $overrides[0xC4] = 'A';
    }
}

/**
 * @} End of "defgroup transliteration".
 */
