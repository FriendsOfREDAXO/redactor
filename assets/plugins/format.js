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
    $R.add('plugin', 'format', {
        init: function (app) {
            this.opts = app.opts;
            this.block = app.block;
            this.selection = app.selection;
            this.toolbar = app.toolbar;

            let opts = this.opts.formatting;
            if ('redaxo' in this.opts && 'format' in this.opts.redaxo) {
                opts = app.opts.redaxo.format;
            }

            $.each(opts, function (i, data) {
                if (typeof data === 'string') {
                    opts[i] = [data, data];
                }
            });

            this.formatopts = opts;
        },

        // public
        start: function () {
            let dropdown = {};

            $.each(this.formatopts, function (i, data) {
                let title = data[0];
                let tag = data[0];
                let cssClass = '';

                if (data.length >= 2) {
                    let params = data[1].split('.');
                    tag = params[0];

                    if (params.length === 2) {
                        cssClass = params[1];
                    }
                }

                if (cssClass !== '') {
                    title = '<span class="' + cssClass + '">' + title + '</span>';
                }

                dropdown[i] = {
                    title: title,
                    api: 'plugin.format.set',
                    args: {
                        tag: tag,
                        cssClass: cssClass
                    }
                };
            });

            let obj = {
                title: redactorTranslations.format_title,
                icon: true,
                tooltip: redactorTranslations.format_tooltip
            };
            // Don't use name like format. This has a conflict with the vendor.
            let button = this.toolbar.addButton('for-format', obj);
            button.setIcon('<i class="re-icon-format"></i>');
            button.setDropdown(dropdown);
        },

        set: function (data) {
            let args = {
                'tag': data.tag,
                'type': 'toggle'
            };

            if (data.cssClass !== '') {
                args.class = data.cssClass;
            }
            let block = this.selection.getBlock();
            let element = $R.dom(block);
            element.removeAttr('class');
            this.block.format(args);
        }
    });
})(Redactor);
