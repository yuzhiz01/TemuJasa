<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function pengguna(Request $request)
    {
        $query = User::query()->where('role', '!=', 'admin');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($request->filled('role') && in_array($request->role, ['pelanggan', 'penyedia'], true)) {
            $query->where('role', $request->role);
        }

        return view('admin.pengguna', [
            'users' => $query->latest()->get(),
            'totalCustomers' => User::where('role', 'pelanggan')->count(),
            'totalProviders' => User::where('role', 'penyedia')->count(),
            'filters' => $request->only(['q', 'role']),
        ]);
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:pelanggan,penyedia'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $request->input('phone'),
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->role === 'admin', 403);
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function konten(Request $request)
    {
        $categories = Category::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%' . $request->q . '%'))
            ->latest()
            ->get();

        $services = Service::with(['provider', 'category'])
            ->when($request->filled('qs'), fn ($query) => $query->where('title', 'like', '%' . $request->qs . '%'))
            ->latest()
            ->get();

        return view('admin.konten', compact('categories', 'services'));
    }

    public function storeService(Request $request)
    {
        $data = $request->validate([
            'provider_id' => ['required', 'exists:users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'shop_name'   => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return back()->with('success', 'Jasa berhasil ditambahkan.');
    }

    public function updateService(Request $request, Service $service)
    {
        $data = $request->validate([
            'provider_id' => ['required', 'exists:users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'shop_name'   => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return back()->with('success', 'Jasa berhasil diperbarui.');
    }

    public function toggleService(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', 'Status jasa diperbarui.');
    }

    public function destroyService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Jasa berhasil dihapus.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ]);

        Category::create($data);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
        ]);

        $category->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
