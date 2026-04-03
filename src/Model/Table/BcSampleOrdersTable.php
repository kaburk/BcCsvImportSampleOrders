<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BcSampleOrdersTable
 */
class BcSampleOrdersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('bc_csv_sample_orders');
        $this->setDisplayField('order_no');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('order_no')
            ->maxLength('order_no', 50)
            ->requirePresence('order_no', 'create')
            ->notEmptyString('order_no');

        $validator
            ->scalar('customer_name')
            ->maxLength('customer_name', 255)
            ->requirePresence('customer_name', 'create')
            ->notEmptyString('customer_name');

        $validator
            ->email('customer_email')
            ->maxLength('customer_email', 255)
            ->allowEmptyString('customer_email');

        $validator
            ->scalar('customer_tel')
            ->maxLength('customer_tel', 30)
            ->allowEmptyString('customer_tel');

        $validator
            ->scalar('product_name')
            ->maxLength('product_name', 255)
            ->requirePresence('product_name', 'create')
            ->notEmptyString('product_name');

        $validator
            ->integer('quantity')
            ->allowEmptyString('quantity');

        $validator
            ->integer('unit_price')
            ->allowEmptyString('unit_price');

        $validator
            ->integer('total_price')
            ->allowEmptyString('total_price');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->allowEmptyString('status');

        $validator
            ->dateTime('ordered_at')
            ->allowEmptyDateTime('ordered_at');

        return $validator;
    }
}
