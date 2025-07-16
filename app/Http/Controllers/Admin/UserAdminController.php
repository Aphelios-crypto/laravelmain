<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Delete the specified user (admins cannot delete themselves).
     */
    public function destroy(User $user)
    {
        // ✅ Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()
                ->back()
                ->with('error', 'You cannot delete your own account.');
        }

        // ✅ Prevent accidental deletion of other admins (optional, uncomment if needed)
        // if ($user->is_admin) {
        //     return redirect()
        //         ->back()
        //         ->with('error', 'You cannot delete another admin.');
        // }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
