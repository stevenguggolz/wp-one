const path = require('path');
let mix = require('laravel-mix');
let fs = require('fs');
let files = fs.readdirSync('assets/styles/widgets/');

mix.js('assets/scripts/elementor.js', 'dist/scripts/theme.min.js');
mix.sass('assets/styles/theme.scss', 'dist/styles/theme.min.css', {
});

files.forEach(file => {
    if(file.substr(0,1) !== '_' && file.substr(file.length-5, 5) === '.scss'){
        mix.sass('assets/styles/widgets/'+file, 'dist/styles/widgets/');
    }
});

mix.browserSync({

    // DEV URL
    proxy: 'https://ostern-neu-erleben.de/',
    files: ['dist/**/*.css', 'dist/**/*.js'],
    rewriteRules: [
        {
            // Pfad zum dist-Ordner auf DEV
            match: new RegExp('/wp-content/themes/wp-one/dist/','g'),
            fn: function() {
                return '/';
            },
        },
        {
            // Pfad zur scripts theme.min.js auf DEV
            match: new RegExp(
                '/wp-content/themes/wp-one/dist/scripts/theme.min.js'),
            fn: function() {
                return '/scripts/theme.min.js';
            },
        },
    ],
    serveStatic: ['dist'],
});

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map',
    }).sourceMaps();
}
