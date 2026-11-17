<?php

namespace App\Http\Controllers;

use App\Models\Media;

class MediaController extends Controller
{
    public function __invoke(int $id)
    {
        $m = Media::find($id);
        abort_unless($m, 404);

        return response(base64_decode($m->data), 200, [
            'Content-Type' => $m->mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
