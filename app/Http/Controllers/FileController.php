<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //
    public function upload(Request $request){
        $data = [];
        $files = $request->file('files');

        $extensions = ['png', 'jpg', 'jpeg', 'svg', 'webp', 'doc', 'docx', 'pdf', 'zip'];

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileId = substr(uniqid(), 2, 13);

            if ($file->getSize() > 1024*1024*5){
                $data [] = [
                    "success" => false,
                    "message" => "File size too large. Max file size is 2 MB",
                    "name" => $originalName,
                ];
                continue;
            }

            if (!in_array($extension, $extensions)){
                $data [] = [
                    "success" => false,
                    "message" => "Invalid file extension.",
                    "name" => $originalName,
                ];

                continue;
            }

            $notOrigName = $originalName;
            $folder = 'uploads';
            $fileName = $fileId . '.' . $extension;
            $file->storeAs($folder, $fileName, 'public');

            $exist = File::query()->where('name', $notOrigName)->where('user_id', Auth::id())->exists();
            $number = 1;
            while ($exist){
                $array = explode('.', $originalName);
                $notOrigName = $array[0] . '(' . $number . ').' . $extension;

                $number++;
                $exist = File::query()->where('name', $notOrigName)->where('user_id', Auth::id())->exists();
            }

            $size = $file->getSize()/1024;

            $fileModel = Auth::user()->files()->create([
                'file_id' => $fileId,
                'name' => $notOrigName,
                'size' => "$size Kb",
                'path' => $folder . '/' . $fileName,
            ]);

            $data [] = [
                "success" => true,
                "message" => "Success",
                "name" => $notOrigName,
                "author" => $fileModel->user,
                "size" => $fileModel->size,
                "url" => $fileModel->url,
                "file_id" => $fileModel->file_id,
            ];
        }
        return response()->json($data, 201);
    }

    public function download(File $file){
        return response()->download('storage/' . $file->path, $file->name);
    }

    public function edit(Request $request, File $file)
    {
        $request->validate([
            'name' => [
                'required',
                function (string $attribute, mixed $value, $fail) use ($file): void {
                    if (File::query()
                        ->where('name', $value)
                        ->where('user_id', Auth::id())
                        ->where('id', '!=', $file->id)
                        ->exists()) {
                        $fail('Введите уникальное имя');
                    }
                },
            ]
        ]);

        $file->name = $request->name;

        $file->save();
        return response()->json(
            [
                "success" => true,
                "new_name" => $file->name,
                "message" => "Renamed",
            ]
        );
    }
    public function delete(File $file){
        $filePath = $file->path;

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $file->delete();
        return response()->json([
            "success" => true,
            "message" => "File already deleted",
        ]);
    }

    public function showMyFiles()
    {
        $data = [];
        $files = Auth::user()->files;

        foreach ($files as $file) {
            $data [] = [
                'file_id' => $file->file_id,
                'name' => $file->name,
                'size' => $file->size,
                'path' => $file->path,
                'author' => $file->user,
                'url' => route('download', $file),
                'access' => $file->getAccessArray(),
            ];
        }
        return $data;
    }
}
