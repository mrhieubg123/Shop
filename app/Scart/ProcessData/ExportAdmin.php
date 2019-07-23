<?php

namespace App\Scart\ProcessData;

use App\Models\ShopCurrency;
use App\User;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportAdmin extends AbstractExporter
{
    public $filename;
    public $sheetname;
    public $title;
    public $action;

    public function __construct(string $action = null, array $options = [])
    {
        $this->filename  = $options['filename'] ?? $this->filename;
        $this->sheetname = $options['sheetname'] ?? $this->sheetname;
        $this->title     = $options['title'] ?? $this->title;
        $this->action    = $action;

    }

    public function export()
    {
        if ($this->action == null) {
            $dataExport = $this->getData();
        } else {
            $dataExport = $this->{$this->action}();
        }

        $spreadsheet = new Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setTitle($this->sheetname);
        $row = empty($this->title) ? 1 : 2;
        if ($row == 2) {
            $worksheet->getCell('A1')->setValue($this->title);
        }
        $worksheet->fromArray($dataExport, $nullValue = null, $startCell = 'A' . $row);
        $writer = IOFactory::createWriter($spreadsheet, "Xls");
        // $writer->save('write.xls');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $this->filename . '.xls"');
        header('Cache-Control: max-age=0');
        $writer->save("php://output");
    }

    public function actionExportOrder()
    {
        $data     = $this->getData();
        $dataRows = [];
        foreach ($data as $key => $value) {
            $dataRows[] = [
                '#' . $value['id'],
                number_format((int) $value['subtotal']),
                number_format((int) $value['shipping']),
                number_format((int) $value['discount']),
                number_format((int) $value['total']),
                $value['currency'],
                $value['exchange_rate'],
                ($value['user_id']) ? $value['user_id'] : 'N/A',
                !empty($value['email']) ? $value['email'] : 'N/A',
                $value['toname'],
                $value['address1'] . ' ' . $value['address2'],
                $value['phone'],
                $value['comment'],
                $value['created_at'],

            ];
        }
        $arrTitle = [
            'ID',
            trans('order.sub_total'),
            trans('order.shipping_price'),
            trans('order.discount'),
            trans('order.order_total'),
            trans('order.currency'),
            trans('order.exchange_rate'),
            trans('order.customer'),
            trans('order.email'),
            trans('order.customer_name'),
            trans('order.shipping_address'),
            trans('order.shipping_phone'),
            trans('order.note'),
            trans('order.date')];

        return array_merge([$arrTitle], $dataRows);
    }

    public function actionExportCustomer()
    {
        $data     = $this->getData();
        $dataRows = [];
        foreach ($data as $key => $value) {
            $dataRows[] = [
                $value['id'],
                $value['name'],
                $value['email'],
                $value['address1'] . ' ' . $value['address2'],
                $value['phone'],
                $value['created_at'],
            ];
        }

        $arrTitle = [
            trans('customer.id'),
            trans('customer.name'),
            trans('customer.email'),
            trans('customer.address'),
            trans('customer.phone'),
            trans('customer.date'),
        ];
        return array_merge([$arrTitle], $dataRows);
    }

    public function actionExportReportCustomer()
    {
        $arrCurrency = ShopCurrency::pluck('code', 'id')->all();
        $data        = $this->getData();
        $dataRows    = [];
        foreach ($data as $key => $value) {
            $ordersGroup  = User::find($value['id'])->orders->groupBy('currency');
            $total_order  = [];
            $total_amount = [];
            foreach ($arrCurrency as $key => $currency) {
                $total_order[$currency]  = isset($ordersGroup[$currency]) ? count($ordersGroup[$currency]) : 0;
                $total_amount[$currency] = 0;
                if (isset($ordersGroup[$currency]) && $ordersGroup[$currency]) {
                    foreach ($ordersGroup[$currency] as $key => $order) {
                        $total_amount[$currency] += $order['total'];
                    }
                }
            }
            $row   = [];
            $row[] = $value['id'];
            $row[] = $value['name'];
            $row[] = $value['email'];
            $row[] = $value['address1'] . ' ' . $value['address2'];
            $row[] = $value['phone'];
            foreach ($arrCurrency as $key => $currency) {
                $row[] = $total_order[$currency];
                $row[] = number_format($total_amount[$currency]);
            }
            $row[]      = $value['created_at'];
            $dataRows[] = $row;

        }
        $arrTitle   = [];
        $arrTitle[] = trans('customer.id');
        $arrTitle[] = trans('customer.name');
        $arrTitle[] = trans('customer.email');
        $arrTitle[] = trans('customer.address');
        $arrTitle[] = trans('customer.phone');
        foreach ($arrCurrency as $key => $currency) {
            $arrTitle[] = trans('customer.total_order') . '(' . $currency . ')';
            $arrTitle[] = trans('customer.total_amount') . '(' . $currency . ')';
        }
        $arrTitle[] = trans('customer.date');
        return array_merge([$arrTitle], $dataRows);
    }

    public function actionExportReportProduct()
    {
        $data     = $this->getData();
        $dataRows = [];
        foreach ($data as $key => $value) {
            $row        = [];
            $row[]      = $value['id'];
            $row[]      = $value['name'];
            $row[]      = $value['price'];
            $row[]      = $value['stock'];
            $row[]      = $value['sold'];
            $row[]      = $value['view'];
            $dataRows[] = $row;

        }
        $arrTitle   = [];
        $arrTitle[] = 'ID';
        $arrTitle[] = trans('product.sku');
        $arrTitle[] = trans('product.name');
        $arrTitle[] = trans('product.price');
        $arrTitle[] = trans('product.stock');
        $arrTitle[] = trans('product.sold');
        $arrTitle[] = trans('product.view');
        return array_merge([$arrTitle], $dataRows);
    }

}
