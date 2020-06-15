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
    $R.add('plugin', 'cleaner', {
        init: function (app) {
            this.app = app;
            this.block = app.block;
            this.inline = app.inline;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
            this.selection = app.selection;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.cleaner_title,
                icon: true,
                tooltip: redactorTranslations.cleaner_tooltip,
                api: 'plugin.cleaner.format'
            };

            let button = this.toolbar.addButton('cleaner', obj);
            button.setIcon('<i class="fa fa-eraser"></i>');
        },

        format: function () {
            if (this.selection.is()) {
                this.inline.clearFormat();
                this.inline.clearAttr();
                this.inline.clearClass();
                this.inline.clearStyle();

                // get the current selection
                // let html = this.selection.getHtml();

                // Strip out html
                // html = html.replace(/(<([^>]+)>)/ig, "");
                //
                // this.insertion.set(html);
            }
        }
    });
})(Redactor);
