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
    $R.add('plugin', 'alignment', {
        init: function (app) {
            this.app = app;
            this.opts = app.opts;
            this.lang = app.lang;
            this.block = app.block;
            this.toolbar = app.toolbar;
        },
        // public
        start: function () {
            let dropdown = {};

            dropdown.left = {
                title: redactorTranslations.alignment_left_title,
                api: 'plugin.alignment.set',
                args: 'left'
            };
            dropdown.center = {
                title: redactorTranslations.alignment_center_title,
                api: 'plugin.alignment.set',
                args: 'center'
            };
            dropdown.right = {
                title: redactorTranslations.alignment_right_title,
                api: 'plugin.alignment.set',
                args: 'right'
            };
            dropdown.justify = {
                title: redactorTranslations.alignment_justify_title,
                api: 'plugin.alignment.set',
                args: 'justify'
            };

            var button = this.toolbar.addButton('alignment', {
                title: redactorTranslations.alignment_title
            });
            button.setIcon('<i class="re-icon-alignment"></i>');
            button.setDropdown(dropdown);
        },
        set: function (type) {
            if (type === 'left' && this.opts.direction === 'ltr') {
                return this._remove();
            }

            var args = {
                style: {'text-align': type}
            };

            this.block.toggle(args);
        },

        // private
        _remove: function () {
            this.block.remove({style: 'text-align'});
        }
    });
})(Redactor);
