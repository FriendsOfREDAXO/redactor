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
    $R.add('plugin', 'h2', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.h2_title,
                icon: false,
                tooltip: redactorTranslations.h2_tooltip,
                api: 'module.block.format',
                args: {
                    tag: 'h2'
                }
            };

            this.toolbar.addButton('h2', obj);
        }
    });
})(Redactor);
