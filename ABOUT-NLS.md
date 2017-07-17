# OpenILLink Native Language Support

## About

This document describes the Native Language Support (NLS) in OpenILLink

## Native Language Support information for administrators

OpenILLink is available in the following languages (varying coverage):
* English (en)
* French (fr)
* German (de)
* Italian (it)
* Spanish (es)

You can configure the following variables in `config.php` (see details next to config definition):
* `langautodetect`
* `configdefaultlang`

## Native Language Support information for translators

If you want to contribute to translating OpenILLink, please check the following:

* check if your language exists among the *.po files within the
  `includes\locale\default\LC_MESSAGES` directory. If such a file does not exist,
  then you can create one by copying the default `en.po` and name it using
  your language shortcode (ISO 639).

* update the .po file corresponding to your language using your
  preferred PO editor, such as Emacs, Poedit, etc. Review and edit lines
  marked as "fuzzy", and add translations to "untranslated" strings. Make
  sure to remove "fuzzy" and "untranslated" marks to the line you have reviewed.

* validate the file using your editor or a validation tool. Pay attention to
  `%` characters which will be replaced with values at runtime.

* if you have access to a running installation of OpenILLink, drop your
  updated file into the `includes\locale\default\LC_MESSAGES` directory
  of your OpenILLink installation. If you do not have access to such test
  installation, please reach out to the developer team by creating an issue on
  GitHub with your updated po file.

* some translations are located outside PO files, in the database.
  Please reach out to the developer team if you are not comfortable
  contributing to corresponding sql source files.
  The relevant columns in the database are:
    * In `status` table (file `data/openillink_data_status.sql`): columns `title*` and `help*`
    * In `unit` table (file `data/openillink_data_units.sql`): columns `name*`
    * In `libraries` table (file `data/openillink_data_libraries.sql`): columns `name*`
    * In `localizations` table (file `data/openillink_data_localizations.sql`): columns `name*`

## Native Language Support information for developers

OpenILLink uses standard gettext localization techniques.

Use function "`__()`" (double underscore) in your code to localize some string. For example:
```
require_once("translations.php"); # This would probably already be included via config.php

function add(x, y):
    echo __("Adding two numbers");
    echo sprintf(__("The sum of %d and %d is %d"), x, y, x + y);
```

### Tips
Here are some recommendations when localizing string within your code:

* Do not concatenate pieces of sentences or try to reuse standalone translated words:
```
    Don't: __("The %s has been deleted") where %s would be "order" or "library"
    Do: __("The order has been deleted") and __("The library has been deleted")
```
```
    Don't: `_("The") " " .  __("order") . " " . __("has been deleted")
    Do: __("The order has been deleted")
```
* Use string formatting with `sprintf` and placeholders such as `%i`, `%d`, etc.:
```
    Do: sprintf(__("The ISBN %s has been updated"), $isbn);
```
If you need multiple placeholders, use named parameters with function `format_string()` to ensure
that parameters are correctly matched in case a language requires to have them reversed in the
sentence:
```
    Do: format_string(__("The order %order_number for customer '%customer_name' has been deleted"),
                       array('order_number' => $order_num, 'customer_name' => $cname))
```

* Do not mix HTML markup with your strings. If you need to use some HTML
  tags, use placeholders that will be replaced at runtime. To ease the
  translation, name the placeholders with an `x_` prefix:
```
    Don't: format_string(__("Go to the <a href="%url">order</a> page"), array('url'=>$url))
    Do: format_string(__("Go to the %x_url_startorder%x_url_end page"), array('x_url_start' => '<a href="'.$url.'">', 'x_url_end' => '</a>')
```

* Remove unncessary leading and trailing whitespace in your translatable strings.
  They should be added in the code if necessary:
```
    Don't: __(" status")
    Do: ' ' . __("status")
```

* Use English as default language:
```
    Don't: __("Libellé")
    Do: __("Label")
```

### Adding a new language

To add a new language the following steps must be followed:

* create a new PO file named after the language ISO 639 code.
* add the language code to the `available_langs` array variable in `translation.php`.
* update the database model to add additional columns to the necessary tables (see above).
  Reflect the changes in the code to take into account the new language.
* update the translations messages defined in config.php (**Note: this
  configuration technique should be improved or care must be taken to
  ensure that installations that have not updated their config with the new language
  will still work**).

