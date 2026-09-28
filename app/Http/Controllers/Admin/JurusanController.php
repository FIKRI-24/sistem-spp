<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJurusanRequest;
use App\Http\Requests\Admin\UpdateJurusanRequest;
use App\Models\Jurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JurusanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $jurusanList = Jurusan::withCount('kelas')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalJurusan = Jurusan::count();

        return view('admin.jurusan.index', compact('jurusanList', 'totalJurusan', 'search'));
    }

    public function store(StoreJurusanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Jurusan::create($data);

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Jurusan {$data['name']} ({$data['code']}) berhasil ditambahkan.");
    }

    public function update(UpdateJurusanRequest $request, Jurusan $jurusan): RedirectResponse
    {
        $data = $request->validated();
        $jurusan->update($data);

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Data jurusan {$jurusan->name} berhasil diperbarui.");
    }

    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        if ($jurusan->kelas()->exists()) {
            return redirect()->route('admin.jurusan.index')
                ->with('error', "Jurusan {$jurusan->name} tidak dapat dihapus karena masih digunakan oleh {$jurusan->kelas()->count()} kelas.");
        }

        $name = $jurusan->name;
        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Jurusan {$name} berhasil dihapus.");
    }
}
