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
    $R.add('plugin', 'pre', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.pre_title,
                icon: true,
                tooltip: redactorTranslations.pre_tooltip,
                api: 'module.block.format',
                args: {
                    tag: 'pre'
                }
            };

            this.toolbar.addButton('pre', obj);
        }
    });
})(Redactor);
