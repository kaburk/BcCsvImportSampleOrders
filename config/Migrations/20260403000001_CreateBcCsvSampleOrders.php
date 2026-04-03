<?php
declare(strict_types=1);

use BaserCore\Database\Migration\BcMigration;

class CreateBcCsvSampleOrders extends BcMigration
{
    public function up()
    {
        $this->table('bc_csv_sample_orders', ['collation' => 'utf8mb4_general_ci'])
            ->addColumn('order_no', 'string', ['limit' => 50, 'null' => false, 'comment' => '受注番号'])
            ->addColumn('customer_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '顧客名'])
            ->addColumn('customer_email', 'string', ['limit' => 255, 'null' => true, 'default' => null, 'comment' => 'メールアドレス'])
            ->addColumn('customer_tel', 'string', ['limit' => 30, 'null' => true, 'default' => null, 'comment' => '電話番号'])
            ->addColumn('product_name', 'string', ['limit' => 255, 'null' => false, 'comment' => '商品名'])
            ->addColumn('quantity', 'integer', ['null' => true, 'default' => null, 'comment' => '数量'])
            ->addColumn('unit_price', 'integer', ['null' => true, 'default' => null, 'comment' => '単価'])
            ->addColumn('total_price', 'integer', ['null' => true, 'default' => null, 'comment' => '合計金額'])
            ->addColumn('status', 'string', ['limit' => 30, 'null' => true, 'default' => 'new', 'comment' => 'ステータス'])
            ->addColumn('ordered_at', 'datetime', ['null' => true, 'default' => null, 'comment' => '受注日時'])
            ->addColumn('created', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('modified', 'datetime', ['null' => true, 'default' => null])
            ->addIndex(['order_no'], ['unique' => true])
            ->create();
    }

    public function down()
    {
        $this->table('bc_csv_sample_orders')->drop()->save();
    }
}
