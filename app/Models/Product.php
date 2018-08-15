<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 10:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Product extends Model
{
    protected $table = 'products';

    function store(Request $request)
    {
        $this->name = $request['name'];
        $this->price = $request['price'];
        $this->desc = $request['desc'];
        $this->weid = $request['weid'];
        return $this->save();
    }

    function index($request)
    {
        $weid = $request->weid;
        $products = Product::where('weid', $weid)->paginate(10);
        return $products;
    }

    function show($id)
    {
        $product = Product::find($id);
        return $product;

    }


}