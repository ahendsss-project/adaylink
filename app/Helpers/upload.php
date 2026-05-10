<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('upload_disk')) {
    /**
     * Get the configured upload disk name.
     */
    function upload_disk(): string
    {
        return config('filesystems.upload_disk', 'public');
    }
}

if (! function_exists('upload_url')) {
    /**
     * Generate a URL for an uploaded file.
     * Handles both uploaded paths (relative) and external URLs (absolute).
     */
    function upload_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // Already an absolute URL (external image or S3 full URL)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk(upload_disk())->url($path);
    }
}

if (! function_exists('upload_store')) {
    /**
     * Store an uploaded file to the configured upload disk.
     */
    function upload_store(string $directory, $file, ?string $disk = null): string
    {
        return $file->store($directory, $disk ?? upload_disk());
    }
}

if (! function_exists('upload_delete')) {
    /**
     * Delete a file from the configured upload disk.
     */
    function upload_delete(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        // Don't try to delete external URLs
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return false;
        }

        return Storage::disk(upload_disk())->delete($path);
    }
}

if (! function_exists('upload_exists')) {
    /**
     * Check if a file exists on the configured upload disk.
     */
    function upload_exists(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return true;
        }

        return Storage::disk(upload_disk())->exists($path);
    }
}
