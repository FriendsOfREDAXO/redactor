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
    $R.add('plugin', 'style', {
        init: function (app) {
            this.opts = app.opts;
            this.toolbar = app.toolbar;

            this.listsopts = ['mark', 'code', 'var', 'kbd', 'sup', 'sub'];

            if ('redaxo' in this.opts && 'style' in this.opts.redaxo) {
                this.listsopts = app.opts.redaxo.style;
            }
        },

        // public
        start: function () {

            let dropdown = {};

            for (let key in this.listsopts) {
                let element = this.listsopts[key];
                let title = 'style_'+element+'_title';
                dropdown[element] = {
                    title: redactorTranslations[title],
                    api: 'module.inline.format',
                    args: element
                }
            }

            let obj = {
                title: redactorTranslations.style_title,
                icon: true,
            };

            let button = this.toolbar.addButton('style', obj);
            button.setIcon('<i class="re-icon-inline"></i>');
            button.setDropdown(dropdown);
        }
    });
})(Redactor);
