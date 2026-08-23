<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// ── PUBLIC PAGES ──────────────────────────────────────────
Route::get('/', function () {
    return view('home', [
        'categories' => \App\Models\Category::orderBy('name')->get(),
        'providers'  => \App\Models\User::where('role', 'penyedia')
            ->withCount('orders')
            ->latest()
            ->take(4)
            ->get(),
    ]);
})->name('home');

// Profil publik penyedia jasa
Route::get('/penyedia-profil/{user}', function (User $user) {
    abort_unless($user->role === 'penyedia', 404);

    $services = \App\Models\Service::where('provider_id', $user->id)
        ->where('is_active', true)
        ->with('category')
        ->latest()
        ->get();

    $rating = \App\Models\Review::where('provider_id', $user->id)
        ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
        ->first();

    return view('profil-penyedia', [
        'provider' => $user,
        'services' => $services,
        'avgRating' => round((float) ($rating->avg_rating ?? 0), 1),
        'totalReview' => (int) ($rating->total ?? 0),
        'totalPesanan' => \App\Models\Order::where('provider_id', $user->id)->count(),
    ]);
})->name('penyedia.profil-publik');

// Simpan koordinat GPS pengguna ke session (dipakai fitur "Terdekat")
Route::post('/simpan-lokasi', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'lat' => ['required', 'numeric', 'between:-90,90'],
        'lng' => ['required', 'numeric', 'between:-180,180'],
    ]);
    session(['geo.lat' => round((float) $data['lat'], 7), 'geo.lng' => round((float) $data['lng'], 7)]);
    return response()->json(['ok' => true]);
})->name('lokasi.simpan');

// ── AUTH ──────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::get('/register/role', [AuthController::class, 'showRegister'])
        ->name('register.role');

    Route::get('/register/pelanggan', [AuthController::class, 'showCustomerRegister'])
        ->name('register.pelanggan');

    Route::get('/register/penyedia', [AuthController::class, 'showProviderRegister'])
        ->name('register.penyedia');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── HALAMAN JASA (SEMUA USER LOGIN: PELANGGAN & PENYEDIA) ──
Route::middleware(['auth'])
    ->prefix('jasa')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/cari', function (\Illuminate\Http\Request $request) {
            $q = trim($request->query('q', ''));
            $categoryId = $request->query('category');
            $lokasi = trim($request->query('lokasi', ''));
            $urutkan = $request->query('urutkan', 'relevan');
            $userLat = $request->query('lat') ?? session('geo.lat');
            $userLng = $request->query('lng') ?? session('geo.lng');

            $services = \App\Models\Service::query()
                ->where('is_active', true)
                ->with(['category', 'provider']);

            // jarak pengguna ke tiap jasa (rumus Haversine, km)
            $hasCoords = is_numeric($userLat) && is_numeric($userLng);
            if ($hasCoords) {
                $services->addSelect('services.*')->addSelect(\Illuminate\Support\Facades\DB::raw(
                    '(6371 * acos(cos(radians(' . (float) $userLat . ')) * cos(radians(latitude))' .
                    ' * cos(radians(longitude) - radians(' . (float) $userLng . '))' .
                    ' + sin(radians(' . (float) $userLat . ')) * sin(radians(latitude)))) AS distance'
                ));
            }

            if ($q !== '') {
                $services->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('shop_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('provider', fn ($p) => $p->where('name', 'like', "%{$q}%"));
                });
            }

            if ($categoryId) {
                $services->where('category_id', $categoryId);
            }

            if ($lokasi !== '') {
                $services->where('location', 'like', "%{$lokasi}%");
            }

            match (true) {
                $urutkan === 'terdekat' && $hasCoords => $services->orderBy('distance'),
                $urutkan === 'harga-rendah'           => $services->orderBy('price'),
                $urutkan === 'harga-tinggi'           => $services->orderByDesc('price'),
                default                               => $services->latest(),
            };

            return view('pelanggan.cari-jasa', [
                'categories'      => \App\Models\Category::orderBy('name')->get(),
                'services'        => $services->get(),
                'selectedCategory' => (int) $categoryId,
                'q'               => $q,
                'lokasi'          => $lokasi,
                'urutkan'         => $urutkan,
                'userLat'         => $hasCoords ? (float) $userLat : null,
                'userLng'         => $hasCoords ? (float) $userLng : null,
                // daftar lokasi diambil nyata dari database, bukan hardcoded
                'locations'       => \App\Models\Service::whereNotNull('location')
                    ->where('location', '!=', '')
                    ->where('is_active', true)
                    ->distinct()->orderBy('location')->pluck('location'),
            ]);
        })->name('cari-jasa');

        Route::get('/detail/{id}', function ($id) {
            $service = \App\Models\Service::with(['category', 'provider', 'options'])->find($id);
            $reviews = $service
                ? \App\Models\Review::with('customer')->where('provider_id', $service->provider_id)->latest()->get()
                : collect();
            return view('pelanggan.detail-jasa', compact('service', 'reviews'));
        })->name('detail-jasa');

        Route::get('/pesan/{id}', function ($id) {
            $service = \App\Models\Service::with('options')->find($id);
            return view('pelanggan.pesan-jasa', compact('service'));
        })->name('pesan-jasa');

        Route::get('/pesanan-sukses/{order}', [\App\Http\Controllers\OrderController::class, 'success']
        )->name('pesanan.sukses');
    });

// ── PELANGGAN DASHBOARD ───────────────────────────────────
Route::middleware(['auth', 'role.pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $user = Auth::user();

            $myOrders = Order::where('customer_id', $user->id);

            return view('pelanggan.dashboard', [
                'orders' => (clone $myOrders)->latest()->take(5)->get(),
                'statAktif'    => (clone $myOrders)->whereIn('status', ['Menunggu', 'Berjalan'])->count(),
                'statSelesai'  => (clone $myOrders)->where('status', 'Selesai')->count(),
                'statReview'   => \App\Models\Review::where('customer_id', $user->id)->count(),
                'statBelanja'  => (clone $myOrders)->where('status', 'Selesai')->sum('total'),
                // rekomendasi diambil nyata dari tabel services (jasa aktif terbaru)
                'recommendations' => \App\Models\Service::where('is_active', true)
                    ->with('category')
                    ->latest()
                    ->take(4)
                    ->get(),
            ]);
        })->name('dashboard');

        Route::get('/pesanan', [OrderController::class, 'customerIndex'])->name('pesanan');
        Route::post('/pesanan', [OrderController::class, 'store'])->name('pesanan.store');
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');
        Route::post('/chat/tandai-dibaca', [ChatController::class, 'markRead'])->name('chat.read');
        Route::get('/review', [ReviewController::class, 'index'])->name('review');
        Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
        Route::get('/profil', [AccountController::class, 'edit'])->name('profil');
        Route::put('/profil', [AccountController::class, 'update'])->name('profil.update');
        Route::put('/password', [AccountController::class, 'password'])->name('password.update');
    });

// ── PENYEDIA JASA DASHBOARD ───────────────────────────────
Route::middleware(['auth', 'role.penyedia'])
    ->prefix('penyedia')
    ->name('penyedia.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $user = Auth::user();
            return view('penyedia.dashboard', [
                'orders' => Order::where('provider_id', $user->id)->latest()->take(5)->get(),
                'topServices' => Order::where('provider_id', $user->id)
                    ->select('service_name', DB::raw('count(*) as total'))
                    ->groupBy('service_name')
                    ->orderByDesc('total')
                    ->take(5)
                    ->get(),
            ]);
        })->name('dashboard');

        Route::get('/jasa-saya', [\App\Http\Controllers\ServiceController::class, 'index'])->name('jasa-saya');
        Route::post('/jasa-saya', [\App\Http\Controllers\ServiceController::class, 'store'])->name('jasa-saya.store');
        Route::put('/jasa-saya/{service}', [\App\Http\Controllers\ServiceController::class, 'update'])->name('jasa-saya.update');
        Route::patch('/jasa-saya/{service}/toggle', [\App\Http\Controllers\ServiceController::class, 'toggleActive'])->name('jasa-saya.toggle');
        Route::delete('/jasa-saya/{service}', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('jasa-saya.destroy');
        Route::post('/jasa-saya/{service}/opsi', [\App\Http\Controllers\ServiceController::class, 'storeOption'])->name('jasa-saya.opsi.store');
        Route::put('/jasa-saya/{service}/opsi/{option}', [\App\Http\Controllers\ServiceController::class, 'updateOption'])->name('jasa-saya.opsi.update');
        Route::delete('/jasa-saya/{service}/opsi/{option}', [\App\Http\Controllers\ServiceController::class, 'destroyOption'])->name('jasa-saya.opsi.destroy');
        Route::get('/pesanan', [OrderController::class, 'providerIndex'])->name('pesanan');
        Route::put('/pesanan/{order}', [OrderController::class, 'updateStatus'])->name('pesanan.status');
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');
        Route::post('/chat/tandai-dibaca', [ChatController::class, 'markRead'])->name('chat.read');
        Route::get('/profil', [AccountController::class, 'edit'])->name('profil');
        Route::put('/profil', [AccountController::class, 'update'])->name('profil.update');
        Route::put('/password', [AccountController::class, 'password'])->name('password.update');
    });

// ── ADMIN DASHBOARD ───────────────────────────────────────
Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $user = Auth::user();
            return view('admin.dashboard', [
                'totalUsers'        => User::whereIn('role', ['pelanggan', 'penyedia'])->count(),
                'totalProviders'    => User::where('role', 'penyedia')->count(),
                'newUsersThisMonth' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'revenueThisMonth'  => Order::where('status', 'Selesai')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total'),
                'ordersThisMonth'   => Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'pendingOrders'     => Order::where('status', 'Menunggu')->count(),
                'activeOrders'      => Order::where('status', 'Berjalan')->count(),
                'completedOrders'   => Order::where('status', 'Selesai')->count(),
                'userGrowth'        => User::select(DB::raw("DATE_FORMAT(created_at, '%M') as bulan"), DB::raw('count(*) as total'))
                    ->where('created_at', '>=', now()->subMonths(6))
                    ->groupBy('bulan')
                    ->orderBy('created_at')
                    ->get(),
            ]);
        })->name('dashboard');

        Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
        Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('pengguna.store');
        Route::delete('/pengguna/{user}', [AdminController::class, 'destroyUser'])->name('pengguna.destroy');
        Route::get('/konten', [AdminController::class, 'konten'])->name('konten');
        Route::post('/kategori', [AdminController::class, 'storeCategory'])->name('kategori.store');
        Route::put('/kategori/{category}', [AdminController::class, 'updateCategory'])->name('kategori.update');
        Route::delete('/kategori/{category}', [AdminController::class, 'destroyCategory'])->name('kategori.destroy');
        Route::post('/jasa', [AdminController::class, 'storeService'])->name('jasa.store');
        Route::put('/jasa/{service}', [AdminController::class, 'updateService'])->name('jasa.update');
        Route::patch('/jasa/{service}/toggle', [AdminController::class, 'toggleService'])->name('jasa.toggle');
        Route::delete('/jasa/{service}', [AdminController::class, 'destroyService'])->name('jasa.destroy');
        Route::get('/pesanan', fn() => view('admin.pesanan'))->name('pesanan');
        Route::get('/review', fn() => view('admin.review'))->name('review');
    });


