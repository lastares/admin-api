<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;
use function strtoupper;

class BaseController extends Controller
{
    const IMG_PATH = '/data/attachment/';
    const FIELD_CODE = 'code';
    const FIELD_MESSAGE = 'msg';
    const FIELD_DATA = 'data';

    private $data = [
        self::FIELD_CODE => 0,
        self::FIELD_MESSAGE => '',
        self::FIELD_DATA => []
    ];

    public function __construct()
    {
        // view()->share('user', $this->user);
    }

    protected function error($msg = '操作失败')
    {
        $this->data[self::FIELD_CODE] = 1;
        $this->data[self::FIELD_MESSAGE] = $msg;
        return response()->json($this->data);
    }

    protected function success($msg = '操作成功', $data = [])
    {
        $this->data[self::FIELD_CODE] = 0;
        $this->data[self::FIELD_MESSAGE] = $msg;
        $this->data[self::FIELD_DATA] = $data;
        return response()->json($this->data);
    }
}