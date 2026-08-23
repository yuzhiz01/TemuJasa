<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceOption;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::where('provider_id', $request->user()->id)
            ->with('options')
            ->latest()->get();
        $categories = Category::orderBy('name')->get();

        return view('penyedia.jasa-saya', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'shop_name'   => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $data['provider_id'] = $request->user()->id;

        Service::create($data);

        return back()->with('success', 'Jasa berhasil ditambahkan.');
    }

    public function update(Request $request, Service $service)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'shop_name'   => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:0'],
            'location'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return back()->with('success', 'Jasa berhasil diperbarui.');
    }

    public function toggleActive(Service $service, Request $request)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);
        $service->update(['is_active' => !$service->is_active]);

        return back()->with('success', 'Status jasa diperbarui.');
    }

    public function destroy(Service $service, Request $request)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);
        $service->delete();

        return back()->with('success', 'Jasa berhasil dihapus.');
    }

    public function storeOption(Request $request, Service $service)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $service->options()->create($data);

        return back()->with('success', 'Opsi berhasil ditambahkan.');
    }

    public function updateOption(Request $request, Service $service, ServiceOption $option)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);
        abort_if($option->service_id !== $service->id, 403);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $option->update($data);

        return back()->with('success', 'Opsi berhasil diperbarui.');
    }

    public function destroyOption(Service $service, ServiceOption $option, Request $request)
    {
        abort_if($service->provider_id !== $request->user()->id, 403);
        abort_if($option->service_id !== $service->id, 403);
        $option->delete();

        return back()->with('success', 'Opsi berhasil dihapus.');
    }
}
