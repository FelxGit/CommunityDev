<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Traits\UploadTrait;

class Upload
{
    use UploadTrait;

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const DISK_LOCAL = 'local';
    const DISK_DEFAULT = 'public'; // default
    const DISK_S3 = 's3';

    const TEMP_DIR_DATE_FORMAT = 'Y-m-d';
    const PNG_IMGTYPE = 'png';

    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * Upload::saveTemp($file, $path, $filename, $disk)
     * Upload file
     * sample method call:
     *      $file = $request->image;
     *      $path = Upload::getCustomPath('noticeThumbnail', 1);
     *      $name = Upload::getCustomName('noticeThumbnail');
     *      $saved = Upload::save($file, $path, $name, $disk);
     *
     * @param Illuminate\Http\File $file
     * @param String $path - destination path to be save
     * @param App\Helpers\Upload::DISK_/String $disk - disk type to be save
     * @return Bool/String $rtn - return false or path/url
     */
    public static function saveTemp($file, string $fileType = null, string $disk = null)
    {
        $rtn = false;

        if (!empty($file)) {
            try {
                self::tmpResourcesDump();
                $user = auth()->guard('api')->user();

                $path = 'tmp/' . Carbon::now()->format(self::TEMP_DIR_DATE_FORMAT) . '/' . $user->id . '/' . $fileType;
                $disk = $disk? : self::DISK_DEFAULT;

                $saved = Storage::disk($disk)->put($path, $file);

                if ($saved) {
                    $rtn = Storage::disk($disk)->url($saved);
                }
            } catch (\Error $e) {
                \Log::error($e->getMessage());
            }
        }

        return $rtn;
    }

    /**
     * Upload::saveFromUrl($content, $filename, $path)
     * Upload file
     * sample method call:
     *      $txt = $request->content;
     *      $saved = Upload::saveFromUrl($txt);
     *
     * @param String $content
     * @param String $filename
     * @param String $path
     * @return array $rtn - return the uploaded Url
     */
    public static function saveFromUrl(String $content, String $filename = null, String $path = null, String $disk = null)
    {
        $rtn = [];

        $imageUrls = [];
        preg_match_all('~src="([^"]+)~', $content, $imageUrls);

        foreach ($imageUrls as $url) {

            try {
                $url = str_replace('src="', '', $url[0]);

                $isValidUrlPublic = filter_var($url, FILTER_VALIDATE_URL)
                    && parse_url($url)['host'] == parse_url(config('app.url'))['host']
                    && Storage::disk(self::DISK_DEFAULT)->has(explode('/storage', $url)[1]);

                if ($isValidUrlPublic) {
                    self::tmpResourcesDump();
                    $tmpfullPath = explode('/storage', $url)[1];
                    $tmpFile = new File('storage/' . $tmpfullPath);
                    $file = new UploadedFile(
                        $tmpFile->getPathname(),
                        $tmpFile->getFilename(),
                        $tmpFile->getMimeType(),
                        0
                    );
                    $filename = $file->getClientOriginalName();
                    $rtn[] = Upload::save($file, $filename, $path, $disk);
                }
            } catch (\Error $e) {
                \Log::error($e->getMessage());
            }
        }

        return $rtn;
    }

    /**
     * Upload::save($file)
     * This function returns image url
     * sample method call:
     *      $file = $request->get('file');
     *      $filename = 'image.png'
     *      $path = 'users/images';
     *      $saved = Upload::save($file, $filename, $path, $disk);
     *
     * @param String $content
     * @return array $rtn - return the uploaded Url
     */
    public static function save($file, String $filename = null, String $path = null, $disk = null)
    {
        $rtn = [];
        $user = auth()->guard('api')->user();

        if (is_null($filename))
            $filename = (Carbon::now()->timestamp + rand(2, 100)) . '.' . $file->getClientOriginalExtension();

        if (is_null($path))
            $path = Carbon::now()->format(self::TEMP_DIR_DATE_FORMAT) . '/' . $user->id;

        if (is_null($disk))
            $disk = self::DISK_DEFAULT;

        if ($file instanceof UploadedFile) {
            $path = $path . '/' . explode('/', $file->getMimeType())[0];
            $rtn = Storage::disk($disk)->putFileAs($path, $file, $filename);
        }

        return $rtn;
    }

    /*======================================================================
     * PRIVATE STATIC METHODS
     *======================================================================*/

    /**
     * Upload::tmpResourcesDump($disk)
     * Dump all temporary folder which is 2 days old and above
     *
     * @param App\Helpers\Upload::DISK_/String $disk - disk type of the folder
     * @return void
     */
    private static function tmpResourcesDump($disk = null)
    {
        if (is_null($disk)) {
            $disk = self::DISK_DEFAULT;
        }

        $dirs = Storage::disk($disk)->directories('tmp');

        // check to see if more than 2 temporary directory exist means:
        // (today will not be deleted, yesterday will not be deleted, then before yesterday will be deleted)
        if (count($dirs) > 2) {
            $validDir = [
                Carbon::now()->format(self::TEMP_DIR_DATE_FORMAT),
                Carbon::now()->addDays(-1)->format(self::TEMP_DIR_DATE_FORMAT)
            ];

            foreach ($dirs as $dir) {
                if (!in_array(explode('tmp/', $dir)[1], $validDir)) {
                    \Log::info('Temporary storage folder "' . $dir . '" is deleted. Note: folders 2 days old and above will be deleted.');
                    Storage::disk($disk)->deleteDirectory($dir);
                }
            }
        }
    }
}
