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
    $R.add('plugin', 'image', {
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
                title: redactorTranslations.image_title,
                observe: 'link',
                api: 'plugin.image.open'
            };

            let button = this.toolbar.addButton('image', obj);
            button.setIcon('<i class="rex-icon rex-icon-media"></i>');
        },

        open: function () {
            let params = 'redactor_image';
            if ('redactor_rex_media_getImageTypes' in rex) {
                params += '&args[types]='+rex.redactor_rex_media_getImageTypes.join(',');
            }
            let that = this;
            let mediaPool = openMediaPool(params);
            $(mediaPool).on('rex:selectMedia', function (event, filename) {
                event.preventDefault();
                mediaPool.close();
                let options = {
                    url: rex.redactor_imageUrlPath+filename,
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
            this.insertion.insertRaw('<img src="'+data.url+'" alt="" />');
        }
    });
})(Redactor);
