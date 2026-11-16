<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;

/**
 * Images uploaded from the admin panel, kept in the database so they survive
 * on hosts without a persistent disk (Vercel). Served by MediaController.
 */
class Media extends Model
{
    protected $table = 'media';

    protected $guarded = ['id'];

    protected $hidden = ['data'];

    /** Create the table if it is missing, so uploads work without re-running the installer. */
    public static function ensureTable(): void
    {
        if (Schema::hasTable('media')) {
            return;
        }
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('mime', 50);
            $table->unsignedInteger('size');
            $table->longText('data'); // base64
            $table->timestamps();
        });
    }

    /** Store an uploaded image (downscaled to max 1600px wide when GD is available). */
    public static function fromUpload(UploadedFile $file): self
    {
        static::ensureTable();

        $bytes = (string) file_get_contents($file->getRealPath());
        $mime = $file->getMimeType() ?: 'image/jpeg';

        if (function_exists('imagecreatefromstring') && $mime !== 'image/gif' && ($img = @imagecreatefromstring($bytes))) {
            $w = imagesx($img);
            $h = imagesy($img);
            if ($w > 1600) {
                $nh = (int) round($h * 1600 / $w);
                $dst = imagecreatetruecolor(1600, $nh);
                imagecopyresampled($dst, $img, 0, 0, 0, 0, 1600, $nh, $w, $h);
                $img = $dst;
            }
            if ($mime === 'image/png' || $mime === 'image/webp') {
                // keep transparency-capable formats as-is unless they were resized
                if ($w > 1600) {
                    ob_start();
                    imagewebp($img, null, 82);
                    $bytes = (string) ob_get_clean();
                    $mime = 'image/webp';
                }
            } else {
                ob_start();
                imageinterlace($img, true);
                imagejpeg($img, null, 82);
                $bytes = (string) ob_get_clean();
                $mime = 'image/jpeg';
            }
        }

        return static::create(['mime' => $mime, 'size' => strlen($bytes), 'data' => base64_encode($bytes)]);
    }

    /** Remove an uploaded image given its /media/{id} URL (other URLs are ignored). */
    public static function deleteByUrl(?string $url): void
    {
        if ($url && preg_match('#^/media/(\d+)$#', $url, $m) && Schema::hasTable('media')) {
            static::whereKey((int) $m[1])->delete();
        }
    }

    public function getUrlAttribute(): string
    {
        return '/media/'.$this->id;
    }
}
