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
    $R.add('plugin', 'linkExternal', {
        init: function (app) {
            this.toolbar = app.toolbar;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.link_insert,
                observe: 'link',
                api: 'module.link.open'
            };

            let button = this.toolbar.addButton('linkExternal', obj);
            button.setIcon('<i class="re-icon-link"></i>');
        }
    });
})(Redactor);
