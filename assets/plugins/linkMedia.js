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
    $R.add('plugin', 'linkMedia', {
        init: function (app) {
            this.app = app;
            this.opts = app.opts;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
            this.selection = app.selection;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.linkMedia_title,
                observe: 'link',
                api: 'plugin.linkMedia.open'
            };

            let button = this.toolbar.addButton('linkMedia', obj);
            button.setIcon('<i class="re-icon-link"></i><i class="rex-icon rex-icon-media" style="margin-left: .1em; vertical-align: top;"></i>');
        },

        open: function () {
            let that = this;
            let mediaPool = openMediaPool('redactor_linkMedia');
            $(mediaPool).on('rex:selectMedia', function (event, filename) {
                event.preventDefault();
                mediaPool.close();
                let options = {
                    url: rex.redactor_rex_url_media+filename,
                    label: filename
                };
                that._insert(options);
            });
        },

        // private
        _insert: function (data) {
            if (this.selection.getText() !== '') {
                data.label = this.selection.getText();
            }
            this.insertion.insertRaw('<a href="' + data.url + '">' + data.label + '</a>');
        }
    });
})(Redactor);
