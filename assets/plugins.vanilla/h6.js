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
    $R.add('plugin', 'h6', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.h6_title,
                icon: false,
                tooltip: redactorTranslations.h6_tooltip,
                api: 'module.block.format',
                args: {
                    tag: 'h6'
                }
            };

            this.toolbar.addButton('h6', obj);
        }
    });
})(Redactor);
