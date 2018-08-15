<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-09-26
 * Time: 15:00
 */

namespace App\Models;


class Corporation extends BaseModel
{

    protected $primaryKey = 'corp_id';

    protected $autoCorpId = false;
    protected $autoUpdateContacts = false;

    protected $fillable = [
        'corp_id',
        'name',
        'tel',
        'code',
        'logo',
        'email',
        'introduce',
        'province',
        'city',
        'type',
        'level',
        'role_id',
        'user_id'
    ];

    public function permanentCode()
    {
        return $this->hasOne('App\Models\CorporationPermanentCode', 'corp_id', 'corp_id');
    }


}