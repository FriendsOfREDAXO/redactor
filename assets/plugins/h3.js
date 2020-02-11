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
    $R.add('plugin', 'h3', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.h3_title,
                icon: false,
                tooltip: redactorTranslations.h3_tooltip,
                api: 'module.block.format',
                args: {
                    tag: 'h3'
                }
            };

            this.toolbar.addButton('h3', obj);
        }
    });
})(Redactor);
