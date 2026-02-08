<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Validation\Rule;

use Spatie\Permission\PermissionRegistrar;

app(PermissionRegistrar::class)->forgetCachedPermissions();


class AccountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Account::select(['id','code','name','type','is_system','is_active','created_at']);

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('type', fn($a) =>
                    '<span class="badge bg-info">'.$a->type.'</span>'
                )
                ->addColumn('is_active', fn($a) =>
                    $a->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Non-aktif</span>'
                )
                ->addColumn('action', fn($a) =>
                    view('accounts._column_action', compact('a'))->render()
                )
                ->rawColumns(['type','is_active','action'])
                ->toJson();
        }

        return view('accounts.index');
    }

    public function create()
    {
        return view('accounts.form', ['account' => new Account()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|max:20|unique:accounts,code',
            'name' => 'required|max:150',
            'type' => 'required',
            'is_active' => 'boolean'
        ]);

        Account::create($data);

        return redirect()
            ->route('accounts.index')
            ->with('success','Akun berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('accounts.form', [
            'account' => Account::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $data = $request->validate([
            'code' => ['required','max:20',Rule::unique('accounts','code')->ignore($account->id)],
            'name' => 'required|max:150',
            'type' => 'required',
            'is_active' => 'boolean'
        ]);

        $account->update($data);

        return redirect()
            ->route('accounts.index')
            ->with('success','Akun berhasil diperbarui');
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success','Akun berhasil dihapus');
    }
}
