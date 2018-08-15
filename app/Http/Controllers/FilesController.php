<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 16:44
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class FilesController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file("file");

// 生成上传Token

// 构建 UploadManager 对象
        $uploadMgr = new UploadManager();
        $result = $uploadMgr . putFile($this->getQiniuToken(), $file->getFilename(), $file->getPath());
        return $result;
    }

    public function upload1(Request $request)
    {// 传到本地storage
        $path = $request->file('file')->store('videos');

        return $path;
    }

    private function getQiniuToken()
    {
        $auth = new Auth(env("qiniu_accessKey"), env("qiniu_secretKey"));
        return $auth->uploadToken(env("qiniu_bucket"));
    }

    public function upload12(Request $request)
    {
        if (!$request->hasFile('file')) {
            return $this->failed('file not found');
        }
        $corpId = $request->get('corp_id');
        if (!$this->isValid($corpId)) {
            return $this->failed('corp_id not found');
        }
        $file = $request->file('file');
        $content = fopen($file->getPath() . '/' . $file->getFilename(), 'r');
        $type = $request->type;

        // 导入
        if ($type == 'imp') {
            return $this->import($file);
        }
        $result = NULL;
        try {
            $result = $this->file->upload($corpId, $content, $type);
        } catch (Exception $e) {
        }
        if (null == $result) {
//            return $this->jsonResponse('201', '未成功');
            return response('未获取到图片', 202);
        }
        return [
            "state" => "SUCCESS",          //上传状态，上传成功时必须返回"SUCCESS"
            "url" => $result['url'],            //返回的地址
            "title" => $result['filename'],          //新文件名
            "original" => $result['original_name'],       //原始文件名
            "type" => $result['mime'],           //文件类型
            "size" => $result['size'],           //文件大小
        ];
    }


}