<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row['point'] = str_replace(',', '.', $row['point']);
        return new User([
            'name' => $row['ten_hoi_vien'],
            'birthday' => date('Y-m-d', strtotime($row['ngay_sinh'])),
            'phone' => $this->phone($row['dien_thoai']),
            'point' => (float) $row['point'],
            'is_admin' => 0,
            'role_id' => 3,

        ]);

    }

    public function phone($str)
    {
        $str = '0' . substr($str, 2, strlen($str) - 2);
        $check = substr($str, 0, 4);

        switch ($check) {
            case '0162':
                $str = '032' . substr($str, 4, strlen($str) - 4);
                break;
            case '0163':
                $str = '033' . substr($str, 4, strlen($str) - 4);
                break;
            case '0164':
                $str = '034' . substr($str, 4, strlen($str) - 4);
                break;
            case '0165':
                $str = '035' . substr($str, 4, strlen($str) - 4);
                break;
            case '0166':
                $str = '036' . substr($str, 4, strlen($str) - 4);
                break;
            case '0167':
                $str = '037' . substr($str, 4, strlen($str) - 4);
                break;
            case '0168':
                $str = '038' . substr($str, 4, strlen($str) - 4);
                break;
            case '0169':
                $str = '039' . substr($str, 4, strlen($str) - 4);
                break;
            case '0120':
                $str = '070' . substr($str, 4, strlen($str) - 4);
                break;
            case '0121':
                $str = '079' . substr($str, 4, strlen($str) - 4);
                break;
            case '0122':
                $str = '077' . substr($str, 4, strlen($str) - 4);
                break;
            case '0126':
                $str = '076' . substr($str, 4, strlen($str) - 4);
                break;
            case '0128':
                $str = '078' . substr($str, 4, strlen($str) - 4);
                break;
            case '0123':
                $str = '083' . substr($str, 4, strlen($str) - 4);
                break;
            case '0124':
                $str = '084' . substr($str, 4, strlen($str) - 4);
                break;
            case '0125':
                $str = '085' . substr($str, 4, strlen($str) - 4);
                break;
            case '0127':
                $str = '081' . substr($str, 4, strlen($str) - 4);
                break;
            case '0129':
                $str = '082' . substr($str, 4, strlen($str) - 4);
                break;
            case '0186':
                $str = '056' . substr($str, 4, strlen($str) - 4);
                break;
            case '0188':
                $str = '058' . substr($str, 4, strlen($str) - 4);
                break;
            case '0199':
                $str = '059' . substr($str, 4, strlen($str) - 4);
                break;
            default:
                break;
        }
        return $str;
    }
}
