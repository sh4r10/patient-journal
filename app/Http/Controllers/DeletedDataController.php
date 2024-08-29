<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use \App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeletedDataController extends Controller
{

    public function permanentlyDeleteDeletedItems(Request $request)
    {
        // Check if the user is an admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('patients.index')->with('error', 'You do not have permission to delete patients.');
        }
        // Validate the user's password
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }
        // Get all models that use soft deletes
        $models = $this->getSoftDeleteModels();

        $this->permanentlyDeleteFiles();

        foreach ($models as $model) {
            // Get all soft-deleted items
            $deletedItems = $model::onlyTrashed()
                ->where('deleted_at', '<=', Carbon::now()->subDays(30))
                ->get();

            // Permanently delete each item
            foreach ($deletedItems as $item) {
                $item->forceDelete(); // Permanently delete the item
            }
        }

        // Permanently delete all soft-deleted files
        return redirect()->route('profile.edit');
    }

    protected function permanentlyDeleteFiles()
    {
        // Get all files that have been soft-deleted for more than 3 days
        $deletedFiles = File::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30))
            ->get();

        foreach ($deletedFiles as $file) {
            // Check if the file exists in storage before deleting
            if (Storage::disk('minio')->exists($file->path)) {
                // Delete the file from storage
                Storage::disk('minio')->delete($file->path);
            }
            // Permanently delete the file record from the database
            $file->forceDelete();
        }
    }

    public function calculateDeletedItemsSize()
    {
        // Get all models that use soft deletes
        $models = $this->getSoftDeleteModels();
        $totalSize = 0;

        foreach ($models as $model) {
            $deletedItems = $model::onlyTrashed()
                ->where('deleted_at', '<=', Carbon::now()->subDays(30))
                ->get();

            foreach ($deletedItems as $item) {
                $totalSize += $this->getItemDiskSize($item);
            }
        }

        $totalSize +=  $this->calculateDeletedFilesSize();
        return $totalSize;
    }

    protected function calculateDeletedFilesSize()
    {
        $deletedFiles = File::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays(30))
            ->get();

        $totalSize = 0;

        foreach ($deletedFiles as $file) {
            $fileSize = $this->getFileSizeFromMinIO($file->path);
            if ($fileSize !== false) {
                $totalSize += $fileSize;
            }
        }

        return $totalSize;
    }

    protected function getFileSizeFromMinIO($path)
    {
        // Use the Storage facade to get the file size
        if (Storage::disk('minio')->exists($path)) {
            return Storage::disk('minio')->size($path);
        }
        return false; // File does not exist in MinIO
    }

    protected function getSoftDeleteModels()
    {
        return [
            \App\Models\User::class,
            \App\Models\JournalEntry::class,
            \App\Models\Treatment::class,
            \App\Models\Note::class,
            \App\Models\Patient::class,
        ];
    }

    protected function getItemDiskSize(Model $item)
    {
        $size = 0;
        $attributes = $item->getAttributes();

        foreach ($attributes as $key => $value) {
            $columnType = $this->getColumnType($item->getTable(), $key);
            $size += $this->getSizeByType($columnType, $value);
        }

        // Consider the overhead for the row (e.g., for the SQLite header)
        $size += 20; // Rough estimate for SQLite row overhead

        return $size;
    }

    protected function getColumnType($table, $column)
    {
        $result = DB::select("PRAGMA table_info($table)");
        foreach ($result as $row) {
            if ($row->name === $column) {
                return strtolower($row->type); // Get the column type
            }
        }
        return null; // If not found, return null
    }

    protected function getSizeByType($type, $value)
    {
        switch ($type) {
            case 'integer':
                return 8; // 8 bytes for INTEGER
            case 'real':
                return 8; // 8 bytes for REAL
            case 'text':
                return strlen($value); // 1 byte per character
            case 'blob':
                return strlen($value); // Size of BLOB
            case 'boolean':
                return 1; // 1 byte for BOOLEAN
            default:
                return 0; // Unknown type, assume 0 bytes
        }
    }
}
