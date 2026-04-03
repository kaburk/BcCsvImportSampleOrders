<?php
declare(strict_types=1);

namespace BcCsvImportSampleOrders\Controller\Admin;

use BcCsvImportCore\Controller\Admin\CsvImportsController;
use BcCsvImportCore\Service\CsvImportServiceInterface;
use BcCsvImportSampleOrders\Service\SampleOrdersCsvImportService;

/**
 * SampleOrdersCsvImportsController
 *
 * BcCsvImportCore の CsvImportsController を継承し、
 * 受注テーブル用のサービスを差し込む。
 * 複数のインポートプラグインを同時有効化できるよう、
 * ServiceProvider 経由の DI ではなく createImportService() で直接インスタンス化する。
 */
class SampleOrdersCsvImportsController extends CsvImportsController
{

    /**
     * CSVアップロード画面
     *
     * コアのテンプレートを利用し、タイトルと adminBase のみ差し替える。
     *
     * @return void
     */
    public function index(): void
    {
        parent::index();
        $this->set('pageTitle', __d('baser_core', '受注CSVインポート サンプル'));
        $this->set('adminBase', '/baser/admin/bc-csv-import-sample-orders/sample_orders_csv_imports');
        $this->viewBuilder()->setTemplatePath($this->name);
        $this->viewBuilder()->setTemplate('BcCsvImportCore.Admin/CsvImports/index');
    }

    /**
     * インポートサービスを生成する
     *
     * @return CsvImportServiceInterface
     */
    protected function createImportService(): CsvImportServiceInterface
    {
        return new SampleOrdersCsvImportService();
    }

}
