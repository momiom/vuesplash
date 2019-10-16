<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index', 'download', 'show']);
    }

    /**
     * 写真一覧
     */
    public function index()
    {
        $photos = Photo::with(['owner'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
        \Log::info('START');

        $extension = $request->photo->extension();
        $photo = new Photo();
        $photo->filename = $photo->id . '.' . $extension;
        // Storage::cloud()->putFileAs('', $request->photo, $photo->filename, 'public');
        Storage::disk('public')->putFileAs('', $request->photo, $photo->filename, 'public');

        DB::beginTransaction();
        try {
            Auth::user()->photos()->save($photo);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // Storage::cloud()->delete($photo->filename);
            Storage::disk('public')->delete($photo->filename);
            throw $exception;
        }

        \Log::info('END');
        return response($photo, 201);
    }

    /**
     * 写真ダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
        \Log::info('START');

        // 写真の存在チェック
        // if (!Storage::cloud()->exists($photo->filename)) {
        if (!Storage::disk('public')->exists($photo->filename)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $photo->filename . '"',
        ];

        \Log::info('END');
        return response(Storage::disk('public')->get($photo->filename), 200, $headers);
    }

    /**
     * 写真詳細
     * @param string $id
     * @return Photo
     */
    public function show(string $id)
    {
        \Log::info('START');
        $photo = Photo::where('id', $id)->with(['owner'])->first();

        \Log::info('END');
        return $photo ?? abort(404);
    }
}
