<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Media;
use App\Models\MediaFolder;
use App\Models\Page;
use App\Traits\RespondsWithError;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileManagerController extends Controller
{
    use RespondsWithError;

    /**
     * Allowed file extensions for upload.
     */
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];

    /**
     * Allowed MIME types for upload.
     */
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * Image MIME types (for thumbnail generation).
     */
    protected array $imageMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Max file size in bytes (10MB).
     */
    protected int $maxFileSize = 10485760;

    /**
     * Display file listing in grid view with folder navigation.
     */
    public function index(Request $request)
    {
        $folderId = $request->input('folder_id');
        $currentFolder = null;
        $breadcrumbs = [];

        if ($folderId) {
            $currentFolder = MediaFolder::findOrFail($folderId);
            $breadcrumbs = $this->buildBreadcrumbs($currentFolder);
        }

        $folders = MediaFolder::where('parent_id', $folderId)
            ->orderBy('name')
            ->get();

        $files = Media::where('folder_id', $folderId)
            ->orderByDesc('created_at')
            ->paginate(24)
            ->withQueryString();

        return view('admin.files.index', compact('folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    /**
     * Upload file with validation (type + size).
     * Generate thumbnail (150x150) for images.
     * Auto-resize oversized images and create WebP variant.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB in KB
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx',
            ],
            'folder_id' => ['nullable', 'exists:media_folders,id'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Additional MIME type validation (anti-spoof — actual content matches extension)
        if (! in_array($mimeType, $this->allowedMimeTypes)) {
            return $this->respondError('Tipe file tidak diizinkan.');
        }

        // Verify image content for image extensions (catches polyglot/spoofed files)
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) && @getimagesize($file->getRealPath()) === false) {
            return $this->respondError('File gambar tidak valid atau rusak.');
        }

        // Additional size validation
        if ($size > $this->maxFileSize) {
            return $this->respondError('Ukuran file melebihi batas maksimal 10MB.');
        }

        // Generate unique filename
        $filename = Str::uuid().'.'.$extension;
        $path = 'media/'.$filename;
        $fullPath = storage_path('app/public/'.$path);

        // For raster images: resize if too big + optimize
        if (in_array($mimeType, $this->imageMimeTypes)) {
            $this->ensureDir(dirname($fullPath));
            $manager = new ImageManager(new Driver);
            $image = $manager->read($file->getRealPath());

            // Cap max dimension at 1920px to keep files reasonable
            if ($image->width() > 1920 || $image->height() > 1920) {
                $image->scaleDown(width: 1920, height: 1920);
            }

            // Save with quality optimization
            if (in_array($extension, ['jpg', 'jpeg'])) {
                $image->toJpeg(quality: 85)->save($fullPath);
            } elseif ($extension === 'png') {
                $image->toPng()->save($fullPath);
            } elseif ($extension === 'webp') {
                $image->toWebp(quality: 85)->save($fullPath);
            } else {
                $image->save($fullPath);
            }

            // Refresh size after optimization
            $size = filesize($fullPath);
        } else {
            // Non-image: store directly
            $file->storeAs('media', $filename, 'public');
        }

        // Generate thumbnail for images (not SVG)
        $thumbnailPath = null;
        $webpPath = null;
        if (in_array($mimeType, $this->imageMimeTypes)) {
            $thumbnailPath = $this->generateThumbnail($fullPath, $filename);

            // Generate WebP variant for non-WebP images for serve via <picture>
            if ($extension !== 'webp') {
                $webpPath = $this->generateWebp($fullPath, $filename);
            }
        }

        // Create media record
        Media::create([
            'folder_id' => $request->input('folder_id'),
            'user_id' => auth()->id(),
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size' => $size,
            'path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'webp_path' => $webpPath,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'File berhasil diunggah.',
            ]);
        }

        return back()->with('success', 'File berhasil diunggah.');
    }

    /**
     * Create folder with name validation and max 5 levels depth check.
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s\-_]+$/'],
            'parent_id' => ['nullable', 'exists:media_folders,id'],
        ], [
            'name.regex' => 'Nama folder hanya boleh mengandung huruf, angka, spasi, strip, dan underscore.',
        ]);

        $parentId = $request->input('parent_id');

        // Check max 5 levels depth
        if ($parentId) {
            $depth = $this->getFolderDepth($parentId);
            if ($depth >= 5) {
                return $this->respondError('Kedalaman folder maksimal 5 level.');
            }
        }

        MediaFolder::create([
            'parent_id' => $parentId,
            'name' => $request->input('name'),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Folder berhasil dibuat.',
            ]);
        }

        return back()->with('success', 'Folder berhasil dibuat.');
    }

    /**
     * Delete a folder (and all its contents recursively).
     */
    public function destroyFolder(Request $request, MediaFolder $folder)
    {
        $this->deleteFolderRecursive($folder);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Folder berhasil dihapus.']);
        }

        return back()->with('success', 'Folder berhasil dihapus.');
    }

    /**
     * Recursively delete a folder and all its files + subfolders.
     */
    protected function deleteFolderRecursive(MediaFolder $folder): void
    {
        // Delete all files in this folder
        $files = Media::where('folder_id', $folder->id)->get();
        foreach ($files as $file) {
            Storage::disk('public')->delete($file->path);
            if ($file->thumbnail_path) {
                Storage::disk('public')->delete($file->thumbnail_path);
            }
            if ($file->webp_path) {
                Storage::disk('public')->delete($file->webp_path);
            }
            $file->delete();
        }

        // Recursively delete subfolders
        $subFolders = MediaFolder::where('parent_id', $folder->id)->get();
        foreach ($subFolders as $sub) {
            $this->deleteFolderRecursive($sub);
        }

        $folder->delete();
    }

    /**
     * Delete file with usage check.
     * If file is used in blog/page content, return warning.
     */
    public function destroy(Request $request, Media $media)
    {
        // Check if file is used in any blog/page content
        $usedIn = $this->checkFileUsage($media);

        if ($usedIn->isNotEmpty() && ! $request->boolean('confirmed')) {
            return response()->json([
                'warning' => true,
                'message' => 'File ini digunakan dalam konten berikut:',
                'usages' => $usedIn,
            ], 409);
        }

        // Delete physical file
        Storage::disk('public')->delete($media->path);

        // Delete thumbnail if exists
        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }

        // Delete WebP variant if exists
        if ($media->webp_path) {
            Storage::disk('public')->delete($media->webp_path);
        }

        // Delete database record
        $media->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus.',
            ]);
        }

        return back()->with('success', 'File berhasil dihapus.');
    }

    /**
     * Update searchable media metadata.
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'original_name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $media->update($validated);

        return redirect()->route('admin.files.index', ['folder_id' => $media->folder_id])
            ->with('success', 'Metadata file berhasil diperbarui.');
    }

    /**
     * Search files by name (min 2 chars).
     * Returns JSON results for AJAX search.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'files' => [],
                'message' => 'Masukkan minimal 2 karakter untuk pencarian.',
            ]);
        }

        $files = Media::where('original_name', 'like', '%'.$query.'%')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'url' => Storage::url($file->path),
                    'thumbnail_url' => $file->thumbnail_path ? Storage::url($file->thumbnail_path) : null,
                    'created_at' => $file->created_at->format('d M Y'),
                ];
            });

        return response()->json(['files' => $files]);
    }

    /**
     * Return file manager in modal mode for TinyMCE integration.
     * Only show images (filter by mime type).
     */
    public function picker(Request $request)
    {
        $folderId = $request->input('folder_id');
        $currentFolder = null;
        $breadcrumbs = [];

        if ($folderId) {
            $currentFolder = MediaFolder::findOrFail($folderId);
            $breadcrumbs = $this->buildBreadcrumbs($currentFolder);
        }

        $folders = MediaFolder::where('parent_id', $folderId)
            ->orderBy('name')
            ->get();

        $files = Media::where('folder_id', $folderId)
            ->where('mime_type', 'like', 'image/%')
            ->orderByDesc('created_at')
            ->paginate(24)
            ->withQueryString();

        return view('admin.files.picker', compact('folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    /**
     * Generate a 300x300 thumbnail for an image file (cover crop).
     * Saved as WebP for size efficiency.
     */
    protected function generateThumbnail(string $sourcePath, string $filename): string
    {
        // Strip extension and use .webp for thumbnails
        $base = pathinfo($filename, PATHINFO_FILENAME);
        $thumbnailPath = 'media/thumbnails/'.$base.'.webp';
        $fullThumbnailPath = storage_path('app/public/'.$thumbnailPath);

        $this->ensureDir(dirname($fullThumbnailPath));

        $manager = new ImageManager(new Driver);
        $image = $manager->read($sourcePath);
        $image->cover(300, 300);
        $image->toWebp(quality: 80)->save($fullThumbnailPath);

        return $thumbnailPath;
    }

    /**
     * Generate a full-size WebP variant for serving via <picture> srcset.
     * Returns the relative path (under storage/app/public).
     */
    protected function generateWebp(string $sourcePath, string $filename): string
    {
        $base = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = 'media/webp/'.$base.'.webp';
        $fullWebpPath = storage_path('app/public/'.$webpPath);

        $this->ensureDir(dirname($fullWebpPath));

        $manager = new ImageManager(new Driver);
        $image = $manager->read($sourcePath);
        $image->toWebp(quality: 82)->save($fullWebpPath);

        return $webpPath;
    }

    /**
     * Ensure a directory exists, recursively creating it if needed.
     */
    protected function ensureDir(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Build breadcrumb path for folder navigation.
     */
    protected function buildBreadcrumbs(?MediaFolder $folder): array
    {
        $breadcrumbs = [];

        while ($folder) {
            array_unshift($breadcrumbs, $folder);
            $folder = $folder->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Get the depth of a folder (how many levels from root).
     */
    protected function getFolderDepth(?int $parentId): int
    {
        $depth = 1; // The new folder will be at depth 1 if parent is root
        $folder = MediaFolder::find($parentId);

        while ($folder) {
            $depth++;
            $folder = $folder->parent;
        }

        return $depth;
    }

    /**
     * Check if a media file is used in any blog or page content.
     */
    protected function checkFileUsage(Media $media): Collection
    {
        $usages = collect();
        $fileUrl = Storage::url($media->path);
        $filename = $media->filename;

        // Check in blog content
        $blogs = Blog::where('content', 'like', '%'.$filename.'%')
            ->orWhere('content', 'like', '%'.$fileUrl.'%')
            ->get(['id', 'title']);

        foreach ($blogs as $blog) {
            $usages->push([
                'type' => 'Blog',
                'title' => $blog->title,
                'id' => $blog->id,
            ]);
        }

        // Check in page content
        $pages = Page::where('content', 'like', '%'.$filename.'%')
            ->orWhere('content', 'like', '%'.$fileUrl.'%')
            ->get(['id', 'title']);

        foreach ($pages as $page) {
            $usages->push([
                'type' => 'Page',
                'title' => $page->title,
                'id' => $page->id,
            ]);
        }

        return $usages;
    }
}
