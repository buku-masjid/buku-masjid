<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Lecturing;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', new User);

        $userQuery = User::query();
        $userQuery->where('name', 'like', '%'.request('q').'%');
        $users = $userQuery->paginate(50);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', new User);

        $roles = $this->getRoles();
        $statuses = $this->getStatuses();

        return view('users.create', compact('roles', 'statuses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', new User);

        $newUser = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'between:5,15', 'max:255'],
            'role_id' => ['required', Rule::in(User::getConstants('ROLE'))],
        ]);
        $password = $newUser['password'] ?: config('auth.passwords.default');
        $newUser['password'] = bcrypt($password);
        $newUser['api_token'] = Str::random(24);
        $newUser['is_active'] = 1;

        $user = User::create($newUser);

        return redirect()->route('users.show', $user);
    }
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = $this->getRoles();
        $statuses = $this->getStatuses();
        $transactionsCount = 0;
        $categoriesCount = 0;
        $booksCount = 0;
        $lecturingsCount = 0;
        $isDeleteable = false;
        if (request('action') == 'delete') {
            $transactionsCount = Transaction::where('creator_id', $user->id)->count();
            $categoriesCount = Category::where('creator_id', $user->id)->count();
            $booksCount = Book::where('creator_id', $user->id)->count();
            $lecturingsCount = Lecturing::where('creator_id', $user->id)->count();
            $isDeleteable = !$transactionsCount && !$categoriesCount && !$booksCount && !$lecturingsCount;
        }

        return view('users.edit', compact(
            'user', 'roles', 'statuses', 'transactionsCount', 'categoriesCount',
            'booksCount', 'lecturingsCount', 'isDeleteable'
        ));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $userData = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'between:5,15', 'max:255'],
            'role_id' => ['required', Rule::in(User::getConstants('ROLE'))],
            'is_active' => ['required', 'bool'],
        ]);
        if ($userData['password'] == null) {
            unset($userData['password']);
        } else {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);

        return redirect()->route('users.show', $user);
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        $request->validate(['user_id' => 'required']);

        if ($request->get('user_id') == $user->id && $user->delete()) {
            return redirect()->route('users.index');
        }

        return back();
    }

    private function getRoles(): array
    {
        $roles = array_map(function ($role) {
            return __('user.'.strtolower($role));
        }, array_flip(User::getConstants('ROLE')));

        return $roles;
    }

    private function getStatuses(): array
    {
        return [
            1 => __('app.active'),
            0 => __('app.inactive'),
        ];
    }
}
