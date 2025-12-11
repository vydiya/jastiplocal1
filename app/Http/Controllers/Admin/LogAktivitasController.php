<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = LogAktivitas::with('user')->orderBy('waktu', 'desc');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('aksi', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%")
                  ->orWhereHas('user', function ($u) use ($q) {
                      $u->where('name', 'like', "%{$q}%");
                  });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.log-aktivitas.index', compact('logs', 'q'));
    }

    public function show(LogAktivitas $log)
    {
        return view('admin.log-aktivitas.show', compact('log'));
    }

    /**
     * Destroy: hapus 1 log
     */
    public function destroy(LogAktivitas $log)
    {
        $log->delete();

        return redirect()->route('admin.log-aktivitas.index')
                         ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Bulk destroy (opsional): hapus beberapa log sekaligus.
     * Mengharapkan input request 'ids' => array of integers.
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:log_aktivitas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        LogAktivitas::whereIn('id', $request->input('ids'))->delete();

        return redirect()->route('admin.log-aktivitas.index')
                         ->with('success', 'Log aktivitas terpilih berhasil dihapus.');
    }

    /**
     * Export CSV (opsional) — akan meng-STREAM file CSV ke browser.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'log_aktivitas_' . now()->format('Ymd_His') . '.csv';

        $query = LogAktivitas::with('user')->orderBy('waktu', 'desc');

        if ($q = $request->query('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('aksi', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%")
                  ->orWhereHas('user', function ($u) use ($q) {
                      $u->where('name', 'like', "%{$q}%");
                  });
            });
        }

        $logs = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Aksi', 'Deskripsi', 'Waktu']);
            foreach ($logs as $l) {
                fputcsv($handle, [
                    $l->id,
                    $l->user?->name ?? '',
                    $l->aksi,
                    $l->deskripsi,
                    $l->waktu?->toDateTimeString() ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
