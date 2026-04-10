<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Test\TestCase\Service;

use BaserCore\TestSuite\BcTestCase;
use BcCsvImportSampleOrders\Service\SampleOrdersCsvImportService;

/**
 * SampleOrdersCsvImportServiceTest
 *
 * SampleOrdersCsvImportService のカラムマップ・エンティティ構築・重複キー等を検証する。
 */
class SampleOrdersCsvImportServiceTest extends BcTestCase
{
    /** @var SampleOrdersCsvImportService */
    private SampleOrdersCsvImportService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SampleOrdersCsvImportService();
    }

    // ─────────────────────────────────────────────────────────────
    // getColumnMap
    // ─────────────────────────────────────────────────────────────

    public function testGetColumnMapReturnsExpectedKeys(): void
    {
        $map = $this->service->getColumnMap();

        $expectedKeys = [
            'order_no', 'customer_name', 'customer_email', 'customer_tel',
            'product_name', 'quantity', 'unit_price', 'total_price', 'status', 'ordered_at',
        ];
        $this->assertSame($expectedKeys, array_keys($map));
    }

    public function testGetColumnMapRequiredFields(): void
    {
        $map = $this->service->getColumnMap();

        $this->assertTrue($map['order_no']['required'] ?? false, "'order_no' は required であるべき");
        $this->assertTrue($map['customer_name']['required'] ?? false, "'customer_name' は required であるべき");
        $this->assertTrue($map['product_name']['required'] ?? false, "'product_name' は required であるべき");
    }

    // ─────────────────────────────────────────────────────────────
    // getDuplicateKey
    // ─────────────────────────────────────────────────────────────

    public function testGetDuplicateKeyReturnsOrderNo(): void
    {
        $this->assertSame('order_no', $this->service->getDuplicateKey());
    }

    // ─────────────────────────────────────────────────────────────
    // buildEntity
    // ─────────────────────────────────────────────────────────────

    public function testBuildEntityCreatesValidEntity(): void
    {
        $entity = $this->service->buildEntity([
            'order_no'       => 'ORD-001',
            'customer_name'  => '山田 太郎',
            'customer_email' => 'taro@example.com',
            'customer_tel'   => '03-1234-5678',
            'product_name'   => '商品A',
            'quantity'       => '2',
            'unit_price'     => '1500',
            'total_price'    => '3000',
            'status'         => 'new',
            'ordered_at'     => '2026-04-01 10:00:00',
        ]);

        $this->assertSame('ORD-001', $entity->get('order_no'));
        $this->assertSame('山田 太郎', $entity->get('customer_name'));
        $this->assertSame(2, $entity->get('quantity'));
        $this->assertSame(1500, $entity->get('unit_price'));
        $this->assertFalse($entity->hasErrors(), 'バリデーションエラーがないこと');
    }

    public function testBuildEntityHasErrorWhenOrderNoIsEmpty(): void
    {
        $entity = $this->service->buildEntity([
            'order_no'       => '',
            'customer_name'  => '山田 太郎',
            'customer_email' => '',
            'customer_tel'   => '',
            'product_name'   => '商品A',
            'quantity'       => '',
            'unit_price'     => '',
            'total_price'    => '',
            'status'         => 'new',
            'ordered_at'     => '',
        ]);

        $this->assertTrue($entity->hasErrors(), '受注番号が空の場合はバリデーションエラーになること');
        $this->assertArrayHasKey('order_no', $entity->getErrors());
    }

    public function testBuildEntityHandlesNullableFields(): void
    {
        $entity = $this->service->buildEntity([
            'order_no'       => 'ORD-002',
            'customer_name'  => '鈴木 花子',
            'customer_email' => '',
            'customer_tel'   => '',
            'product_name'   => '商品B',
            'quantity'       => '',
            'unit_price'     => '',
            'total_price'    => '',
            'status'         => '',
            'ordered_at'     => '',
        ]);

        $this->assertNull($entity->get('customer_email'));
        $this->assertNull($entity->get('quantity'));
        $this->assertFalse($entity->hasErrors(), 'オプション項目が空でもエラーにならないこと');
    }

    // ─────────────────────────────────────────────────────────────
    // buildTemplateCsv
    // ─────────────────────────────────────────────────────────────

    public function testBuildTemplateCsvContainsAllLabels(): void
    {
        $csv    = $this->service->buildTemplateCsv();
        $lines  = array_filter(explode("\n", trim($csv)));
        $header = str_getcsv(array_values($lines)[0]);

        $expectedLabels = array_values(array_map(fn($v) => $v['label'], $this->service->getColumnMap()));
        $this->assertSame($expectedLabels, $header);
    }
}
