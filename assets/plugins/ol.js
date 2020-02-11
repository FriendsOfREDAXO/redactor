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
    $R.add('plugin', 'ol', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.ol_title,
                icon: true,
                api: 'module.list.toggle',
                observe: 'list',
                args: 'ol'
            };

            this.toolbar.addButton('ol', obj);
        }
    });
})(Redactor);
