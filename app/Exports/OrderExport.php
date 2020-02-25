<?php

namespace App\Exports;
use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements WithHeadings, FromArray, ShouldAutoSize, WithMapping
{
  
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Mã hàng',
            'Tên hàng',
            'Số lượng',
            'Giá',
            'Tổng tiền'
        ];
    }

    public function map($row): array
    {
        return [
            $row['product_code'],
            $row['product_name'],
            $row['quantity'],
            $row['price'],
            $row['total_price']
        ];
    }
}
