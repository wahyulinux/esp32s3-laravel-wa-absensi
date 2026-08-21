<?php

namespace App\Http\Controllers;

use App\Models\CamImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Menerima foto dari ESP32-CAM dan menyimpannya.
     * POST /api/v1/images
     * Body: { "device_id": "...", "image_data": "<base64>" }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id'  => 'required|string|max:100',
            'image_data' => 'required|string',
        ]);

        $binaryImage = base64_decode($validated['image_data'], strict: true);

        if ($binaryImage === false) {
            return response()->json([
                'success' => false,
                'message' => 'Format base64 tidak valid',
            ], 422);
        }

        $filename = Str::uuid() . '.jpg';
        $storagePath = 'cam-images/' . $filename;

        Storage::disk('public')->put($storagePath, $binaryImage);

        $image = CamImage::create([
            'device_id' => $validated['device_id'],
            'filename'  => $filename,
            'path'      => $storagePath,
            'size'      => strlen($binaryImage),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil disimpan',
            'data'    => $this->formatImage($image),
        ], 201);
    }

    /**
     * Daftar semua foto yang tersimpan.
     * GET /api/v1/images?device_id=ESP32-S3-WROOM-CAM&per_page=20
     */
    public function index(Request $request): JsonResponse
    {
        $query = CamImage::query()->latest();

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        $images = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $images->map(fn (CamImage $img) => $this->formatImage($img)),
            'meta'    => [
                'current_page' => $images->currentPage(),
                'last_page'    => $images->lastPage(),
                'per_page'     => $images->perPage(),
                'total'        => $images->total(),
            ],
        ]);
    }

    /**
     * Detail satu foto berdasarkan ID.
     * GET /api/v1/images/{id}
     */
    public function show(CamImage $image): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->formatImage($image),
        ]);
    }

    /**
     * Hapus foto beserta file-nya.
     * DELETE /api/v1/images/{id}
     */
    public function destroy(CamImage $image): JsonResponse
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ]);
    }

    private function formatImage(CamImage $image): array
    {
        return [
            'id'         => $image->id,
            'device_id'  => $image->device_id,
            'filename'   => $image->filename,
            'size_bytes' => $image->size,
            'url'        => Storage::disk('public')->url($image->path),
            'created_at' => $image->created_at->toIso8601String(),
        ];
    }
}
