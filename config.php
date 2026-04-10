<?php
return [
    'type' => 'Plugin',
    'title' => __d('baser_core', '受注CSVインポート サンプル'),
    'description' => __d('baser_core', 'BcCsvImportCore を使った受注データCSVインポートのサンプルプラグインです。'),
    'author' => 'kaburk',
    'url' => 'https://blog.kaburk.com/',
    'adminLink' => [
        'prefix' => 'Admin',
        'plugin' => 'BcCsvImportSampleOrders',
        'controller' => 'SampleOrdersCsvImports',
        'action' => 'index',
    ],
];
