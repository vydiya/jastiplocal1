<?php
namespace App\Observers;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    protected function log($model, $action)
    {
        $user = Auth::user();
        $modelName = class_basename($model);
        $id = $model->id ?? null;
        $desc = sprintf("%s #%s — perubahan: %s", $modelName, $id ?? '-', $action);

        LogAktivitas::create([
            'user_id' => $user?->id ?? 0,
            'aksi' => strtolower($action),
            'deskripsi' => $desc,
            'waktu' => now(),
        ]);
    }

    public function created($model) { $this->log($model, 'Created'); }
    public function updated($model) { $this->log($model, 'Updated'); }
    public function deleted($model) { $this->log($model, 'Deleted'); }
    // optional: restored, forceDeleted
}
