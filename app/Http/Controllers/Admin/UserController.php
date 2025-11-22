<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter khusus untuk menampilkan user yang minta reset
        if ($request->has('filter_reset')) {
            $query->whereNotNull('password_reset_requested_at');
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Hitung jumlah permintaan reset untuk badge notifikasi admin
        $resetCount = User::whereNotNull('password_reset_requested_at')->count();

        return view('admin.users.index', compact('users', 'resetCount'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'password' => Hash::make('password123'),
            'password_reset_requested_at' => null
        ]);

        return back()->with('success', 'Password direset & permintaan reset dihapus.');
    }
}
