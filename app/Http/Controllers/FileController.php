<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\NewsArticle;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'TechNetChronicles';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'post' => ['mp3', 'mp4', 'gif', 'png', 'jpg', 'jpeg'],
    ];

    private static function getDefaultExtension(string $type)
    {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(string $type, string $extension)
    {
        $allowedExtensions = self::$systemTypes[$type];

        return in_array(strtolower($extension), $allowedExtensions);
    }

    private static function isValidType(string $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(string $type)
    {
        return asset($type . '/' . self::$default);
    }

    private static function getFileName(string $type, $id, string $extension = null)
    {

        $fileName = null;
        switch ($type) {
            case 'profile':
                $fileName = User::find($id)->profile_image;
                break;
            case 'post':
                $fileName = NewsArticle::find($id)->article_image;
                break;
            default:
                return null;
        }

        return $fileName;
    }

    private static function delete(string $type, $id)
    {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . $existingFileName);

            switch ($type) {
                case 'profile':
                    User::find($id)->profile_image = null;
                    break;
                case 'post':
                    NewsArticle::find($id)->article_image = null;
                    break;
            }
        }
    }

    public function upload(Request $request)
    {

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'File not found'], 400);
        }

        if (!$this->isValidType($request->type)) {
            return response()->json(['error' => 'Unsupported upload type'], 400);
        }

        $file = $request->file('file');
        $type = $request->type;
        $extension = $file->extension();
        if (!$this->isValidExtension($type, $extension)) {
            return response()->json(['error' => 'Unsupported upload extension'], 400);
        }

        $this->delete($type, $request->id);

        $fileName = $file->hashName();

        $error = null;
        switch ($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->profile_image = $fileName;
                    $user->save();
                } else {
                    $error = "unknown user";
                }
                break;

            case 'post':
                $post = NewsArticle::findOrFail($request->id);
                if ($post) {
                    $post->article_image = $fileName;
                    $post->save();
                } else {
                    $error = "unknown article";
                }
                break;

            default:
                return response()->json(['error' => 'Unsupported upload object'], 400);
        }

        if ($error) {
            return response()->json(['error' => "Error: {$error}"], 400);
        }

        $file->storeAs($type, $fileName, self::$diskName);
        $filePath = Storage::disk(self::$diskName)->url($type . '/' . $fileName);
        return response()->json(['message' => 'Uploaded image successfully', 'filePath' => $filePath]);
    }

    static function get(string $type, $userId)
    {

        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }

        return self::defaultAsset($type);
    }
}
