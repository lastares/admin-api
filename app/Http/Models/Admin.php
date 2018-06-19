<?php

namespace App\Http\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: songyaofeng
 * Date: 2018/6/11
 * Time: 13:33
 */
class Admin extends Model
{
    public $timestamps = true;
    protected $table = 'ng_admin';
    protected $dateFormat = 'U';
    protected $fillable = ['username', 'realname', 'status', 'mobile', 'avatar', 'profile'];

    public function addAdmin(array $data)
    {
        return self::create($data);
    }


    public function adminList($where = [])
    {
        $data['data'] = $this->search($where)->orderBy('id', 'desc')->get();
        $data['total'] = $this->search($where)->count();
        foreach($data['data'] as $k => &$v) {
            $v->image_path = env('IMAGE_HOST') . $v->avatar;
        }
        return $data;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

//    public function getAvatarAttribute($avatar)
//    {
//        return env('IMAGE_HOST') . $avatar;
//    }

    public function search($data)
    {
        $query = $this;
        if(!empty($data['realname'])) {
            $query = $query->where('realname', 'like', '%' . $data['realname'] . '%');
        }

        if(!empty($data['username'])) {
            $query = $query->where('username', 'like', '%' . $data['username'] . '%');
        }

        if(!empty($data['mobile'])) {
            $query = $query->where('mobile', $data['mobile']);
        }

        if(!empty($data['status'])) {
            if($data['status'] == 1) {
                $query = $query->where('status', 1);
            } else {
                $query = $query->where('status', 2);
            }
        }

        return $query;
    }

    public function getUser(int $id)
    {
        return self::find($id);
    }

    public function editUser(int $id, array $data)
    {
        return $this->where('id', intval($id))->update($data);
    }

    public function deleteUser(int $id)
    {
        $user = Admin::find($id);
        return $user->delete();
    }
}