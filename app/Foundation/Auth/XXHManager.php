<?php
/**
 * Created by PhpStorm.
 * User: HTMC
 * Date: 2017/3/31
 * Time: 17:23
 */

namespace App\Foundation\Auth;


use App\Models\Corporation;
use App\Repositories\CorporationRepository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class XXHManager
{

    protected $corp = null;

    protected $session;
    protected $request;

    protected $repository;


    /**
     * XxhManager constructor.
     * @param  \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->session = $app->make(Session::class);
        $this->request = $app->make(Request::class);
        $this->repository = $app->make(CorporationRepository::class);

    }

    /**
     * @return bool
     */
    public function check()
    {
        return !is_null($this->corp());
    }

    /**
     * @return mixed|null|Corporation
     */
    public function corp()
    {

        if (!is_null($this->corp)) {
            return $this->corp;
        }
        $id = $this->getId();
        $corp = null;
        if (!is_null($id)) {
            try {
                $corp = $this->repository->find($id);
            } catch (ModelNotFoundException $exception) {
                $corp = null;
            }
        }

        return $this->corp = $corp;
    }

    public function getId()
    {
        if ($this->session->isStarted()) {
            $id = $this->session->get($this->getName());
        } else {
            $id = $this->request->header('CorpId');
        }

        return $id;

    }

    protected function getName()
    {
        return 'login_corp_' . sha1(static::class);
    }

    public function getCorp()
    {
        return $this->corp;
    }

    public function setCorp(Corporation $corp)
    {
        $this->corp = $corp;

        return $this;
    }

    public function id()
    {
        return $this->corp()
            ? $this->corp()->getKey()
            : $this->getId();
    }

    public function entryCorp(Corporation $corp)
    {
        $this->session->put($this->getName(), $corp->getKey());

        $this->setCorp($corp);

    }


}