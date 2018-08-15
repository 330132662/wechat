<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-09-04
 * Time: 17:47
 */

namespace App\Models;


class CorporationPermanentCode extends BaseModel
{

    protected $primaryKey = 'corp_id';
    protected $autoCorpId = false;
    protected $autoUpdateContacts = false;

}