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
    $R.add('plugin', 'blockquote', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.blockquote_title,
                icon: false,
                tooltip: redactorTranslations.blockquote_tooltip,
                api: 'module.block.format',
                args: {
                    tag: 'blockquote'
                }
            };

            this.toolbar.addButton('blockquote', obj);
        }
    });
})(Redactor);
