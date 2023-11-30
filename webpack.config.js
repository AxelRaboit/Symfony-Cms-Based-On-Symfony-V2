const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('frontend', './assets/frontend/app.js')
    .addEntry('backend', './assets/backend/app.js')
    .addEntry('shared', './assets/shared/app.js')

    // Backend - Templates

    // dashboard - dashboard
    .addEntry('backend/admin/dashboard', './assets/backend/js/templates/backend/admin/dashboard/dashboard.js')

    // dashboard - content
    .addEntry('backend/admin/dashboard/content/page/list', './assets/backend/js/templates/backend/admin/dashboard/content/page/list/list.js')
    .addEntry('backend/admin/dashboard/content/page/create', './assets/backend/js/templates/backend/admin/dashboard/content/page/create/create.js')
    .addEntry('backend/admin/dashboard/content/page/edit', './assets/backend/js/templates/backend/admin/dashboard/content/page/edit/edit.js')
    .addEntry('backend/admin/dashboard/content/page/modal/galleryMedia', './assets/backend/js/templates/backend/admin/dashboard/content/page/modal/gallery-media-modal.js')

    .addEntry('backend/admin/dashboard/content/media/list', './assets/backend/js/templates/backend/admin/dashboard/content/media/list.js')
    .addEntry('backend/admin/dashboard/content/media/modal/importMedia', './assets/backend/js/templates/backend/admin/dashboard/content/media/modal/import-media-modal.js')

    // dashboard - advancedData
    .addEntry('backend/admin/dashboard/advancedData/dataEnum/list', './assets/backend/js/templates/backend/admin/dashboard/advancedData/dataEnum/list.js')
    .addEntry('backend/admin/dashboard/advancedData/dataEnum/edit', './assets/backend/js/templates/backend/admin/dashboard/advancedData/dataEnum/edit.js')
    .addEntry('backend/admin/dashboard/advancedData/dataEnum/create', './assets/backend/js/templates/backend/admin/dashboard/advancedData/dataEnum/create.js')

    .addEntry('backend/admin/dashboard/advancedData/userBackend/list', './assets/backend/js/templates/backend/admin/dashboard/advancedData/userBackend/list.js')
    .addEntry('backend/admin/dashboard/advancedData/userBackend/create', './assets/backend/js/templates/backend/admin/dashboard/advancedData/userBackend/create.js')
    .addEntry('backend/admin/dashboard/advancedData/userBackend/edit', './assets/backend/js/templates/backend/admin/dashboard/advancedData/userBackend/edit.js')


    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: './postcss.config.js',
        };
    })

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
