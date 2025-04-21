/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function ($R) {
    $R.add('plugin', 'subscript', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.subscript_title,
                icon: true,
                tooltip: redactorTranslations.subscript_tooltip,
                api: 'module.inline.format',
                args: {
                    tag: 'sub'
                }
            };

            this.toolbar.addButton('subscript', obj);
        }
    });
})(Redactor);
