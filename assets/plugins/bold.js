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
    $R.add('plugin', 'bold', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.bold_title,
                icon: true,
                tooltip: redactorTranslations.bold_tooltip,
                api: 'module.inline.format',
                args: {
                    tag: 'b'
                }
            };

            this.toolbar.addButton('bold', obj);
        }
    });
})(Redactor);
