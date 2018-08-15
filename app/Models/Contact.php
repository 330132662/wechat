<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-09-26
 * Time: 15:55
 */

namespace App\Models;


class Contact extends BaseModel
{


    protected $primaryKey = 'contact_id';

    protected $autoUpdateContacts = false;

    protected $autoUserId = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'user_id',
        'corp_id',
        'name',
        'code',
        'mobile',
        'email',
        'status',// 状态，0粉丝1联系人
        'is_admin',
        'is_sub_admin',
        'avatar'
    ];

    /**
     * Get the avatar and return the default avatar if the avatar is null.
     *
     * @param string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return !empty($value) ? $value : config('xxh.default_avatar');
    }

}