<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    // API upload video
    public function uploadVideo(Request $request)
    {
        try {
//        return response()->json([
//            'success' => true,
//            'message' => 'Video uploaded successfully.',
//        ], 201);
//            dd($request->all());
            $request->validate([
                'title' => 'required|string|max:255',
                'video' => 'required|mimes:mp4,mov,avi,flv|max:102400', // 100MB
            ]);

            // Upload video vào storage/public/videos
            $videoPath = $request->file('video')->store('videos', 'public');

            // Lưu thông tin video vào database
            $video = Video::query()->create([
                'title' => $request->title,
                'path' => $videoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully.',
                'video' => $video,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            return response()->json([
                'success' => false,
                'error' => $errors,
            ], 400);
//            $field = array_key_first($errors); // Lấy field lỗi đầu tiên
//            throw new \App\Exceptions\ValidationException($errors[$field][0], $field);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while uploading the video.',
            ], 500);
        }
    }

    public function streamVideo($id)
    {
        $video = Video::findOrFail($id);

        // Lấy đường dẫn đầy đủ của video
        $videoPath = Storage::disk('public')->path($video->path);

        if (!file_exists($videoPath)) {
            return response()->json([
                'error' => 'Video not found.'
            ], 404);
        }

        // Lấy kích thước file video
        $fileSize = filesize($videoPath);
        $start = 0;
        $end = $fileSize - 1;

        // Xử lý HTTP Range (nếu có)
        $headers = [
            'Content-Type' => 'video/mp4',
            'Accept-Ranges' => 'bytes',
        ];

        if (request()->hasHeader('Range')) {
            $range = request()->header('Range');
            [$start, $end] = sscanf($range, 'bytes=%d-%d');
            $end = $end ?? $fileSize - 1;
            $headers['Content-Range'] = "bytes $start-$end/$fileSize";
            $headers['Content-Length'] = $end - $start + 1;
            http_response_code(206);
        } else {
            $headers['Content-Length'] = $fileSize;
        }

        // Streaming video
        return response()->stream(function () use ($videoPath, $start, $end) {
            $handle = fopen($videoPath, 'rb');
            fseek($handle, $start);
            $chunkSize = 8192; // 8KB
            while (!feof($handle) && ($pos = ftell($handle)) <= $end) {
                if ($pos + $chunkSize > $end) {
                    $chunkSize = $end - $pos + 1;
                }
                echo fread($handle, $chunkSize);
                flush();
            }
            fclose($handle);
        }, http_response_code(), $headers);
    }

}

