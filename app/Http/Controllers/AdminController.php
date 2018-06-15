<?php

namespace App\Http\Controllers;

use function app;
use App\Http\Models\Admin;
use function explode;
use function file_put_contents;
use function implode;
use Redis;
use Request;
use function serialize;


class AdminController extends BaseController
{
    public function createUser(Admin $admin)
    {
        $data = request()->except('token');
        if (!$admin->addAdmin($data)) {
            return $this->error();
        }

        return $this->success();
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        $targetDir = 'data/attachment/' . date('Y-m-d');
        $file = $request->file('avatar');
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }
        $originFilename = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $file_mime = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        if (in_array($file_mime, array('jpg', 'gif', 'png'))) {
            if ($fileSize > 1024 * 2 * 1024) {
                return response()->json(['code' => -1, 'msg' => '文件大小必须小于2M', 'data' => []]);
            }
        }
        $file->move($targetDir, $fileName);
        $message = ['code' => 0, 'msg' => '上传成功', 'url' => self::IMG_PATH . date('Y-m-d') . '/' . $fileName];
        return $this->success('上传成功', $message);
    }

    public function adminList(Admin $admin)
    {
        $where = request()->all();
        if(!empty($where)) {
            foreach($where as $k => &$v) {
                if($v == 'null') {
                    $v = '';
                }
            }
        }
        app('redis')->set('data', serialize($where));
        $adminList = $admin->adminList($where);
        return $this->success('ok', $adminList);
    }

    public function getUser(Admin $admin)
    {
        $user_id = request()->input('user_id', 0);
        if($user_id == 0) {
            return $this->error('参数错误');
        }
        $user = $admin->getUser($user_id);
        if(empty($user)) {
            return $this->error('该用户不存在');
        }

        return $this->success('ok', $user);

    }

    public function editUser(Admin $admin)
    {
        $data = request()->all();
//        $user_id = request()->only('user_id');
        $result = $admin->editUser($data['id'], $data);

        if(!$result) {
            return $this->error();
        }

        return $this->success();
    }


}