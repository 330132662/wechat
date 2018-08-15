<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/25
 * Time: 11:30
 */

namespace App\Support\excel;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class FormExport implements FromCollection
{
    private $aid;

    public function __construct()
    {
//        $this->aid = $aid;

    }


    /**
     * @return Builder
     */
    /*    public function query()
        {
            // TODO: Implement query() method.

            $ids [] = '45';
            $ids [] = '46';
            $ids [] = '47';
            $ids[] = '48';

    //        return CompValues::query()->whereIn('id', $ids);
        }*/

    /**
     * @return Collection
     */
    public function collection()
    {

        return (new Collection([[1, 2, 3], [1, 2, 3]]));
        // TODO: Implement collection() method.
    }
}