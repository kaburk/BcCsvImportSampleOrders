<?php

/**
 * BcCsvImportSampleOrders 設定
 *
 * 独自のメニューキーと独自コントローラーを使用するため、BcCsvImportCore のメニューキーとは
 * 別のキー（BcCsvImportSampleOrders）でメニューを登録する。
 * これにより複数のインポートプラグインを同時有効化しても競合しない。
 */
return [
    'BcApp' => [
        'adminNavigation' => [
            'Contents' => [
                'BcCsvImportSampleOrders' => [
                    'title' => __d('baser_core', '受注CSVインポート サンプル'),
                    'url' => [
                        'Admin' => true,
                        'plugin' => 'BcCsvImportSampleOrders',
                        'controller' => 'sample_orders_csv_imports',
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'BcCsvImportCore' => [
        'showImportStrategySelect' => false,
        'defaultImportStrategy'    => 'append',
        'showDuplicateModeSelect'  => false,
        'defaultDuplicateMode'     => 'skip',
    ],
];
