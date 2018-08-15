<?php

use Maatwebsite\Excel\Facades\Excel;

//导出数据  本文件仅作为样例ljf
public
function daochu()
{

    $cellData = ['编号', '省份', '计划人数', '签到人数', '完成率(%)'];

    $data = Provinceplan::where('provinces', '!=', '')->select('id', 'provinces', 'provincePlan', 'actualNum')->get()->toArray();
    // dd($data);
    $this->export('完成率表', $cellData, $data);

}

public
function export($title, $cellData, $data)
{
    // dd($data);
    Excel::create($title, function ($excel) use ($title, $cellData, $data) {
        $excel->sheet($title, function ($sheet) use ($cellData, $data) {
            $sheet->prependRow(1, $cellData);  //第一行标题
            //$sheet->getColumnDimension('A')->setWidth(15);
            $sheet->setWidth([
                'A' => '20',
                'B' => '20',
                'C' => '20',
                'D' => '20',
                'E' => '20'

            ]);
            $sheet->setAutoFilter('A1:E1');

            // dd($data);
            $count = count($data);
            // dd($count);
            for ($i = 0; $i < $count; $i++) {
                if (($data[$i]['provincePlan']) == 0) {
                    $data[$i]['num'] = 0;
                } else {
                    $data[$i]['num'] = ($data[$i]['actualNum'] / $data[$i]['provincePlan'] * 100);
                }

                $sheet->row($i + 2, [
                    $data[$i]['id'],
                    $data[$i]['provinces'],
                    $data[$i]['provincePlan'],
                    $data[$i]['actualNum'],
                    $data[$i]['num']

                ]);
            }
        });

        $cellData1 = ['编号', '用户名', '手机号', '省份', '机构'];
        $info = HomeUser::where('telephone', '!=', '')->select('id', 'province', 'name', 'telephone', 'mechanism')->get()->toArray();
        // dd($info);
        $excel->sheet('用户表', function ($sheet1) use ($cellData1, $info) {
            $sheet1->prependRow(1, $cellData1);  //第一行标题
            //$sheet->getColumnDimension('A')->setWidth(15);
            $sheet1->setWidth([
                'A' => '20',
                'B' => '20',
                'C' => '20',
                'D' => '20',
                'E' => '20'

            ]);
            $sheet1->setAutoFilter('A1:E1');

            // dd($data);
            $count1 = count($info);
            // dd($count);
            for ($i = 0; $i < $count1; $i++) {
                $sheet1->row($i + 2, [
                    $info[$i]['id'],
                    $info[$i]['name'],
                    $info[$i]['telephone'],
                    $info[$i]['province'],
                    $info[$i]['mechanism']
                ]);
            }
        });

    })->export('xls');
}


public
function doUser()
{
    $cellData = ['编号', '用户名', '手机号', '省份', '机构'];
    $info = HomeUser::where('telephone', '!=', '')->select('id', 'province', 'name', 'telephone', 'mechanism')->get()->toArray();
    $title = "用户表";
    Excel::create($title, function ($excel) use ($title, $cellData, $info) {

        $excel->sheet('用户表', function ($sheet) use ($cellData, $info) {
            $sheet->prependRow(1, $cellData);  //第一行标题

            $sheet->setWidth([
                'A' => '20',
                'B' => '20',
                'C' => '20',
                'D' => '20',
                'E' => '20'

            ]);
            $sheet->setAutoFilter('A1:E1');

            // dd($data);
            $count = count($info);
            // dd($count);
            for ($i = 0; $i < $count; $i++) {
                $sheet->row($i + 2, [
                    $info[$i]['id'],
                    $info[$i]['name'],
                    $info[$i]['telephone'],
                    $info[$i]['province'],
                    $info[$i]['mechanism']
                ]);
            }
        });

    })->export('xls');
}
    	
	
	