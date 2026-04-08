# BcCsvImportSampleOrders

`BcCsvImportSampleOrders` は、`BcCsvImportCore` を使った受注CSVインポートのサンプルプラグインです。
1行1受注の単純な受注データを `bc_csv_sample_orders` テーブルへ取り込む実装例を提供します。

## 用途

- 受注系インポートの基礎実装サンプル
- `order_no` を使った重複処理の確認
- 実案件向けの受注インポートプラグイン作成時の土台

## 前提

- `BcCsvImportCore` を有効化済みであること
- 本プラグインを有効化すると、サンプル用のテーブルの migration が実行されます。
- 実際に使うときは不要な部分ですが、サンプル動作確認の為に入れています。

実運用では、`bc_csv_sample_orders` テーブルは受注管理側プラグインが持つ想定です。
この migration はあくまで動作確認用です。


## 管理画面

- メニュー名: `受注CSVインポート サンプル`
- URL: `/baser/admin/bc-csv-import-sample-orders/sample_orders_csv_imports/index`

画面構成自体は `BcCsvImportCore` の共通UIです。

## 対象テーブル

- Model alias: `BcCsvImportSampleOrders.BcSampleOrders`
- 物理テーブル: `bc_csv_sample_orders`
- 重複キー: `order_no`

## CSVフォーマット

テンプレートCSVのヘッダは次の通りです。

```csv
受注番号,顧客名,メールアドレス,電話番号,商品名,数量,単価,合計金額,ステータス,受注日時
```

## 固定設定

- インポート方式: `append` 固定
- 重複処理: `skip` 固定

用途をサンプルに絞るため、管理画面ではこれらの選択UIを非表示にしています。  
設定は `BcCsvImportSampleOrders/config/setting.php` の `BcCsvImportSampleOrders.*` キーで管理しています。

## 実装の見どころ

- サービス実装: `src/Service/SampleOrdersCsvImportService.php`
- Table / Entity: `src/Model/Table/BcSampleOrdersTable.php`, `src/Model/Entity/BcSampleOrder.php`
- 専用コントローラー: `src/Controller/Admin/SampleOrdersCsvImportsController.php`
- 画面テンプレート: `BcCsvImportCore` の共通テンプレート `Admin/CsvImports/index` を再利用

将来的に 1受注+複数明細のような構成へ発展させる場合の出発点として使えます。

## テストデータ生成

大量件数で挙動確認したい場合は、CakePHP コンソールコマンドでテスト用 CSV を生成できます。

```bash
bin/cake BcCsvImportSampleOrders.generate_test_csv
```

CSVヘッダは `SampleOrdersCsvImportService::getColumnMap()` から自動取得するため、
カラム定義を変更しても常にインポート仕様と一致します。

生成ファイル名は `import_orders_*.csv` です。
例: `--sizes=10k --errors=5` の場合は `import_orders_10k_err5pct.csv` が生成されます。

主なオプション:

- `--output=/path/to/dir` 出力先ディレクトリを変更（デフォルト: `tmp/csv/`）
- `--sizes=10k,100k` 生成件数をカンマ区切りで指定（デフォルト: `10k` / `k`・`m` サフィックス対応）
- `--errors=5` エラー行を約 5% 含める（デフォルト: `0`）

エラー行は一定間隔で差し込まれ、必須項目欠落・不正ステータス・重複受注番号などのパターンを確認できます。

ヘルプを表示するには:

```bash
bin/cake BcCsvImportSampleOrders.generate_test_csv --help
```

## ライセンス

MIT License. 詳細は `LICENSE.txt` を参照してください。
