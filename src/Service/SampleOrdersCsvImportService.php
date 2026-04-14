<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Service;

use BcCsvImportCore\Service\CsvImportService;
use BcCsvImportCore\Service\CsvImportServiceInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * SampleOrdersCsvImportService
 *
 * 受注テーブル（orders）へのCSVインポートサービス。
 * BcCsvImportCore を使った独自テーブルへのインポート実装サンプル。
 *
 * CSVフォーマット（1行1受注）:
 * 受注番号, 顧客名, メールアドレス, 電話番号, 商品名, 数量, 単価, 合計金額, ステータス, 受注日時
 */
class SampleOrdersCsvImportService extends CsvImportService implements CsvImportServiceInterface
{

    /**
     * インポート対象のテーブル名
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'BcCsvImportSampleOrders.BcCsvSampleOrders';
    }

    /**
     * CSVカラムマップ
     * key: テーブルのカラム名, label: CSV上の表示名, required: 必須かどうか, sample: サンプル値
     *
     * @return array
     */
    public function getColumnMap(): array
    {
        return [
            'order_no' => [
                'label' => '受注番号',
                'required' => true,
                'sample' => 'ORD-20260401-001',
            ],
            'customer_name' => [
                'label' => '顧客名',
                'required' => true,
                'sample' => '山田 太郎',
            ],
            'customer_email' => [
                'label' => 'メールアドレス',
                'required' => false,
                'sample' => 'taro@example.com',
            ],
            'customer_tel' => [
                'label' => '電話番号',
                'required' => false,
                'sample' => '03-1234-5678',
            ],
            'product_name' => [
                'label' => '商品名',
                'required' => true,
                'sample' => 'サンプル商品A',
            ],
            'quantity' => [
                'label' => '数量',
                'required' => false,
                'sample' => '2',
            ],
            'unit_price' => [
                'label' => '単価',
                'required' => false,
                'sample' => '1500',
            ],
            'total_price' => [
                'label' => '合計金額',
                'required' => false,
                'sample' => '3000',
            ],
            'status' => [
                'label' => 'ステータス',
                'required' => false,
                'sample' => 'new',
            ],
            'ordered_at' => [
                'label' => '受注日時',
                'required' => false,
                'sample' => '2026-04-01 10:00:00',
            ],
        ];
    }

    /**
     * 重複チェックに使うカラム名
     *
     * 受注番号が一致するレコードを既存データとみなす。
     *
     * @return string
     */
    public function getDuplicateKey(): string
    {
        return 'order_no';
    }

    /**
     * CSV1行（連想配列）からEntityを生成する
     *
     * @param array $row CSVの1行データ（キーはカラム名）
     * @return \Cake\Datasource\EntityInterface
     */
    public function buildEntity(array $row): EntityInterface
    {
        $table = TableRegistry::getTableLocator()->get($this->getTableName());

        $nullIfEmpty = fn(?string $v): ?string => ($v !== null && $v !== '') ? trim($v) : null;
        $intIfNotEmpty = fn(?string $v): ?int => ($v !== null && $v !== '') ? (int)$v : null;

        $data = [
            'order_no'       => $nullIfEmpty($row['order_no'] ?? null),
            'customer_name'  => $nullIfEmpty($row['customer_name'] ?? null),
            'customer_email' => $nullIfEmpty($row['customer_email'] ?? null),
            'customer_tel'   => $nullIfEmpty($row['customer_tel'] ?? null),
            'product_name'   => $nullIfEmpty($row['product_name'] ?? null),
            'quantity'       => $intIfNotEmpty($row['quantity'] ?? null),
            'unit_price'     => $intIfNotEmpty($row['unit_price'] ?? null),
            'total_price'    => $intIfNotEmpty($row['total_price'] ?? null),
            'status'         => $nullIfEmpty($row['status'] ?? null) ?? 'new',
            'ordered_at'     => $nullIfEmpty($row['ordered_at'] ?? null),
        ];

        return $table->newEntity($data);
    }

}
