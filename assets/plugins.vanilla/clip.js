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
    $R.add('plugin', 'clip', {
        init: function (app) {
            this.opts = app.opts;
            this.insertion = app.insertion;
            this.toolbar = app.toolbar;

            if ('redaxo' in this.opts && 'clip' in this.opts.redaxo) {
                this.clipopts = app.opts.redaxo.clip;
            } else {
                return false;
            }
        },

        // public
        start: function () {
            let dropdown = {};

            $.each(this.clipopts, function (i, data) {
                let title = data[0];
                let clip = data[1];

                dropdown[i] = {
                    title: title,
                    api: 'plugin.clip.set',
                    args: clip
                };
            });

            let obj = {
                title: redactorTranslations.clip_title,
                icon: true,
                tooltip: redactorTranslations.clip_tooltip
            };
            // Don't use name like clip. This has a conflict with the vendor.
            let button = this.toolbar.addButton('for-clip', obj);
            button.setIcon('<i class="re-icon-clips"></i>');
            button.setDropdown(dropdown);
        },

        set: function (data) {
            this.insertion.insertHtml(data);
        }
    });
})(Redactor);
