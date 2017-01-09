# Contributing
Bug reports, feature requests, and code contributions are encouraged and welcome!

## Bug reports and feature requests
If you find a bug or have a feature request, please search for 
[already reported problems](https://github.com/openillink-project/openillink/issues) 
before submitting a new issue.

## Code contributions
We follow typical [GitHub flow](https://guides.github.com/introduction/flow/index.html).

1. Fork this repository into your personal space.
2. Start a new topical branch for any contribution.  Name it sensibly,
   say ``improve-fix-order-form-autocomplete``.
3. Test your branch on a local site.
4. Create logically separate commits for logically separate things.
5. Please add any ``(closes #123)`` or ``(addresses #123)`` directives
   in your commit log message if your pull request closes or addresses
   an open issue.
6. Issue a pull request.

## Coding guidelines

Take into consideration the following when contributing:

1. **Make things easily configurable and reusable by others.**
   * Use configuration variables ``CFG_*`` in ``config.php``.
   * Make sure new features would not break existing behaviour functionality used by
     other production sites.
   * Write for general use cases rather than site-specific context.
	 
2. **Code with security in mind**
   * Sanitize all your input arguments. Use for eg. helper function
     ``toolkit.isValidInput()``.
   * Escape all your HTML output using ``htmlspecialchars()`` to avoid XSS.
   * Run SQL queries using helper function ``connexion.dbquery()``. Supply 
      arguments as parameters to protect against SQL injections.
   * Avoid using ``eval()``.
   
3. **Publish your code under GNU General Public License.**

4. **Create logically separate commits for logically separate things.**
   * Maintain separate commits for separate features and bug fixes.
   * Squash together commits when appropriate (use ``rebase -i master``).
   * Commit early, commit often.

5. **Use meaningful commit messages and stamp them ticket directives.**
   * Adopt the following commit log format:     
     * Start your commit message with a short headline formatted in the style: `component/feature: short description`
     * empty line
     * commit message body with a detailed description of what the patch does, 
       formatted as a bullet list, with one empty line between items (using present tense).
     
     Example:
	 ```
	 OpenURL: support for version 1.0
	 
     * Adds support for OpenURL protocol version 1.0

     * Adds configuration variable CFG_OPENURL_VERSION. 
	 ```
   * If the patch closes a ticket, use ``(closes #123)`` ticket
     directive.  If the patch only addresses the issue, use
     ``(addresses #123)`` ticket directive.

6. **Submit pull requests for production-ready code only.**
   * Rebase your branch against latest master, and test before 
     submitting your pull request.

## Coding style
We recommend the adoption of the [PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/) 
when writing code that you want to request for integration.

## Etiquette
Please adhere to the [CODE-OF-CONDUCT](CODE-OF-CONDUCT.md) 
when contributing to this project.

