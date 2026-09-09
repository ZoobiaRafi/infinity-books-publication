<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Voyager's image field stores two very different kinds of path in the
 * same column depending on how the value got there:
 *
 * - A git-committed seed image (e.g. "assets/images/book-fiction.jpg")
 *   lives directly under public/ and just needs the site URL prepended,
 *   via asset().
 * - An image actually uploaded through the admin (e.g.
 *   "services/September2026/xyz.webp") lives under storage/app/public/
 *   and is only reachable through the public disk's /storage/ symlink,
 *   via Storage::disk('public')->url().
 *
 * asset() alone is wrong for the second case — it produces a URL missing
 * the /storage/ segment entirely, which is why every image uploaded
 * through Voyager rendered as a broken <img> until this existed.
 */
trait ResolvesUploadedImageUrl
{
    protected function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : asset($path);
    }
}
