<?php
/**
 * Created by PhpStorm.
 * User: HTMC
 * Date: 2017/4/1
 * Time: 9:38
 */

namespace App\Models;


//use App\Models\Interfaces\BaseModelEventsInterface;
//use App\Models\Traits\BaseModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{

//    use BaseModelEvents;

    /**
     * Indicates if the model should be auto set user_id.
     *
     * @var bool
     */
    protected $autoUserId = false;// 默认关闭

    /**
     * Indicates if the model should be auto set corp_id.
     *
     * @var bool
     */
    protected $autoCorpId = true;

    /**
     * Indicates if the model should be recorded contacts.
     *
     * @var bool
     */
    protected $autoUpdateContacts = false;
    public $timestamps = true;

    /**
     * Get current auth user
     *
     * @return User|null
     */
    public function getAuthUser()
    {
        $user = null;
        if (\Auth::check()) {
            $user = \Auth::user();
        }
        return $user;
    }

    /**
     * Get current auth user_id
     *
     * @return mixed|null
     */
    public function getAuthUserId()
    {
        $user_id = null;
        $user = $this->getAuthUser();
        if ($user) {
            $user_id = $user->getAuthIdentifier();
        }
        return $user_id;
    }

    /**  封装 数据存在性验证函数  2018年3月12日10:07:03 李建飞
     * 禁止用于api
     * @param $table 表名
     * @param $condition  条件
     * @return mixed
     */
    protected function in_table($table, $condition)
    {
        return DB::table($table)->where($condition)->first();
    }

    /**
     * Get current auth user
     *
     * @return User|null
     */
    public function getAuthCorp()
    {
        $corp = null;
        if (\XXH::check()) {
            $corp = \XXH::corp();
        }
        return $corp;
    }

    public function getAuthCorpId()
    {
        $corp_id = null;
        $corp = $this->getAuthCorp();
        if ($corp) {
            $corp_id = $corp->getKey();
        }
        return $corp_id;
    }


    /**
     * Get current auth contact
     *
     * @return Contact|null
     */
    public function getAuthContact()
    {
        $contact = null;

        $user = $this->getAuthUser();
        if ($user) {
            $contact = $user->contacts()->where('contacts.corp_id', \XXH::id())->first();
        }


        return $contact;
    }

    public function getAuthContactId()
    {
        $contact_id = null;
        $contact = $this->getAuthContact();
        if ($contact) {
            $contact_id = $contact->getKey();
        }
        return $contact_id;

    }


    /**
     * Get current model's user_id
     *
     * @return mixed|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Update the creation and update by contacts.
     *
     * @return void
     */
    protected function updateContacts()
    {
        $contact_id = $this->getAuthContactId();
        if (!($contact_id > 0)) {
            return;
        }

        if (!$this->isDirty('updated_by')) {
            $this->updated_by = $contact_id;
        }

        if (!$this->exists && !$this->isDirty('created_by')) {
            $this->created_by = $contact_id;
        }
    }

    /**
     * @return bool
     */
    public function isAuthUserOwner()
    {
        return $this->getAuthUserId() == $this->getUserId();
    }

    /**
     * @param \Request $request
     * @return bool
     */
    public function isEdit($request)
    {

        if ($request->route()->getActionMethod() == 'edit') {
            return true;
        }
        return false;
    }

    /*
     *  快速判断参数是否有效
     */
    protected function isValid($string)
    {
        if (null == $string || '' == $string) {

            return false;
        } else {
            return true;
        }
    }
}