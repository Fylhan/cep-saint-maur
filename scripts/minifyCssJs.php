<?php

require(__DIR__.'/../src/common.php');

echo '# CSS files: ';
$cssFiles = array(
    'ie',
    'highlight-default',
    'redactor',
    'style'
);
foreach ($cssFiles as $cssFile) {
    if (! is_file(CSS_PATH . '/' . $cssFile . '.min.css')) {
        file_put_contents(CSS_PATH . '/' . $cssFile . '.min.css', minifyCss(file_get_contents(ASSETS_PATH . '/css/' . $cssFile . '.css')));
    }
}
echo 'done'."\n";

echo '# JS files: ';
$jsFiles = array(
    'html5',
    'contact'
);
foreach ($jsFiles as $jsFile) {
    if (! is_file(JS_PATH . '/' . $jsFile . '.min.js')) {
        file_put_contents(JS_PATH . '/' . $jsFile . '.min.js', minifyJs(file_get_contents(ASSETS_PATH . '/js/' . $jsFile . '.js')));
    }
}
echo 'done'."\n";