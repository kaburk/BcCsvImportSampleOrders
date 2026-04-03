<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Model\Entity;

use Cake\ORM\Entity;

/**
 * BcSampleOrder Entity
 *
 * @property int $id
 * @property string $order_no
 * @property string $customer_name
 * @property string|null $customer_email
 * @property string|null $customer_tel
 * @property string $product_name
 * @property int|null $quantity
 * @property int|null $unit_price
 * @property int|null $total_price
 * @property string|null $status
 * @property \Cake\I18n\DateTime|null $ordered_at
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class BcSampleOrder extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'order_no' => true,
        'customer_name' => true,
        'customer_email' => true,
        'customer_tel' => true,
        'product_name' => true,
        'quantity' => true,
        'unit_price' => true,
        'total_price' => true,
        'status' => true,
        'ordered_at' => true,
        'created' => true,
        'modified' => true,
    ];
}