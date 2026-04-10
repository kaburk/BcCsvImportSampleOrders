<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Command;

use BcCsvImportCore\Command\AbstractGenerateTestCsvCommand;
use BcCsvImportCore\Service\CsvImportServiceInterface;
use BcCsvImportSampleOrders\Service\SampleOrdersCsvImportService;

/**
 * GenerateTestCsvCommand
 *
 * SampleOrders テスト用CSVファイルを生成する CakePHP コンソールコマンド。
 * CSVヘッダは SampleOrdersCsvImportService::getColumnMap() から自動取得します。
 *
 * 使い方（プロジェクトルートから実行）:
 *   bin/cake BcCsvImportSampleOrders.generate_test_csv
 */
class GenerateTestCsvCommand extends AbstractGenerateTestCsvCommand
{

    public static function defaultName(): string
    {
        return 'bc_csv_import_sample_orders.generate_test_csv';
    }

    protected function getCommandDescription(): string
    {
        return 'SampleOrders テスト用CSVファイルを生成します。';
    }

    protected function getService(): CsvImportServiceInterface
    {
        return new SampleOrdersCsvImportService();
    }

    protected function getFilenamePrefix(): string
    {
        return 'import_sample_orders_';
    }

    protected function buildRow(int $i, array $columnKeys): array
    {
        $statuses = ['new', 'processing', 'shipped', 'cancelled'];
        $products = ['テスト商品A', 'テスト商品B', 'テスト商品C', 'テスト商品D', 'テスト商品E'];
        $baseDate = new \DateTimeImmutable('2026-01-01 10:00:00');
        $product = $products[($i - 1) % count($products)];
        $status = $statuses[($i - 1) % count($statuses)];
        $quantity = ($i % 5) + 1;
        $unitPrice = (($i % 10) + 1) * 500;
        $orderedAt = $baseDate->modify('+' . ($i - 1) . ' hours')->format('Y-m-d H:i:s');
        $row = [];
        foreach ($columnKeys as $key) {
            $row[$key] = match ($key) {
                'order_no'       => sprintf('ORD-%08d', $i),
                'customer_name'  => 'テスト顧客' . $i,
                'customer_email' => 'test' . $i . '@example.com',
                'customer_tel'   => sprintf('03-%04d-%04d', ($i % 10000), ($i % 10000) + 1),
                'product_name'   => $product,
                'quantity'       => $quantity,
                'unit_price'     => $unitPrice,
                'total_price'    => $quantity * $unitPrice,
                'status'         => $status,
                'ordered_at'     => $orderedAt,
                default          => '',
            };
        }
        return $row;
    }

    protected function getErrorPatterns(): array
    {
        return [
            '受注番号が空（必須項目エラー）' => function (array $row): array {
                $row['order_no'] = '';
                return $row;
            },
            '顧客名が空（必須項目エラー）' => function (array $row): array {
                $row['customer_name'] = '';
                return $row;
            },
            '単価が負の値（バリデーションエラー）' => function (array $row): array {
                $row['unit_price'] = -100;
                return $row;
            },
            'ステータスが不正値（バリデーションエラー）' => function (array $row): array {
                $row['status'] = 'invalid_status';
                return $row;
            },
            '受注番号が重複（ORD-00000001と同じ）' => function (array $row): array {
                $row['order_no'] = 'ORD-00000001';
                return $row;
            },
        ];
    }
}
