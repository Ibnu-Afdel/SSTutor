<?php

namespace App\Traits;

use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;

trait CloudinaryUpload
{
    /**
     * Upload a file to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $options
     * @return array
     */
    public function uploadToCloudinary(UploadedFile $file, string $folder = 'uploads', array $options = []): array
    {
        // Merge default options with passed options
        $options = array_merge([
            'folder' => $folder,
            'resource_type' => 'auto', // Automatically detect resource type
        ], $options);

        // Upload file to Cloudinary
        $uploadApi = new UploadApi();
        $result = $uploadApi->upload($file->getRealPath(), $options);

        return [
            'public_id' => $result['public_id'],
            'url' => $result['secure_url'],
            'width' => $result['width'] ?? null,
            'height' => $result['height'] ?? null,
            'format' => $result['format'] ?? null,
            'resource_type' => $result['resource_type'] ?? null,
            'created_at' => $result['created_at'] ?? null,
        ];
    }

    /**
     * Delete an image from Cloudinary
     * 
     * @param string $publicId
     * @return bool
     */
    public function deleteFromCloudinary(string $publicId): bool
    {
        try {
            $uploadApi = new UploadApi();
            $result = $uploadApi->destroy($publicId);
            
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Cloudinary delete error: ' . $e->getMessage());
            return false;
        }
    }
} 