var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('src/ciberConnect/shopBundle/Resources/public/js/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('outputJs', './src/ciberConnect/shopBundle/Resources/public/js/main.js')

    .addStyleEntry('outputCss', './src/ciberConnect/shopBundle/Resources/public/styles/main.scss')
    .addStyleEntry('prueba', './src/ciberConnect/shopBundle/Resources/public/styles/prueba.scss')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    .enableBuildNotifications()

    .enableSassLoader()

    .autoProvidejQuery()
    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
