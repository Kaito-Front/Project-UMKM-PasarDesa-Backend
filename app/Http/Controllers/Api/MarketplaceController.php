<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MarketplaceController extends Controller
{
    public function categories(): JsonResponse
    {
        return response()->json(Category::query()->withCount('products')->orderBy('name')->get());
    }

    public function stats(): JsonResponse
    {
        $months = collect(range(5, 0))->map(fn (int $monthsAgo) => now()->startOfMonth()->subMonths($monthsAgo));
        $completedOrders = Order::query()
            ->whereIn('status', ['diambil', 'selesai'])
            ->where('created_at', '>=', $months->first())
            ->get(['total', 'created_at']);
        $monthlyPerformance = $months->map(function ($month) use ($completedOrders) {
            $orders = $completedOrders->filter(fn (Order $order) => $order->created_at?->format('Y-m') === $month->format('Y-m'));

            return [
                'period' => $month->format('Y-m'),
                'transactions' => $orders->count(),
                'turnover' => (float) $orders->sum('total'),
            ];
        })->values();

        return response()->json([
            'totalUmkm' => Shop::query()->where('status', 'aktif')->count(),
            'totalProducts' => Product::query()->where('is_hidden', false)->count(),
            'totalTransactions' => Order::query()->count(),
            'turnoverThisMonth' => (float) Order::query()->whereIn('status', ['diambil', 'selesai'])->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total'),
            'activeKecamatan' => Shop::query()->where('status', 'aktif')->distinct()->count('kecamatan'),
            'monthlyPerformance' => $monthlyPerformance,
        ]);
    }

    public function shops(): JsonResponse
    {
        return response()->json(Shop::query()->withCount('products')->orderBy('name')->get());
    }

    public function orders(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Order::query()->latest();

        if ($user->role === 'admin') {
            // Admin can see all orders
        } elseif (in_array($user->role, ['penjual', 'keduanya'], true) && $user->shop_name) {
            // Seller can see orders for their shop
            $query->where('shop_name', $user->shop_name);
        } else {
            // Buyer can see their own orders based on phone number or name
            $query->where(function ($q) use ($user) {
                $q->where('phone', $user->phone)->orWhere('buyer_name', $user->name);
            });
        }

        return response()->json($query->get()->map(fn (Order $order) => $this->serializeOrder($order)));
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'buyerName' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.productId' => ['required', 'string', Rule::exists('products', 'id')],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.note' => ['nullable', 'string', 'max:1000'],
            'shippingMethod.name' => ['required', 'string', 'max:255'],
            'shippingMethod.price' => ['required', 'numeric', 'min:0'],
            'paymentMethod.name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $productIds = collect($validated['items'])->pluck('productId')->unique();
            $products = Product::query()->with(['category', 'shop'])->whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');
            $items = collect($validated['items'])->map(function (array $item) use ($products) {
                $product = $products->get($item['productId']);
                abort_if($product->is_hidden, 422, 'Produk ini tidak tersedia untuk dipesan.');

                return [
                    'id' => 'cart-'.Str::uuid(),
                    'productId' => $product->id,
                    'product' => $this->serializeProduct($product),
                    'qty' => $item['qty'],
                    'note' => $item['note'] ?? '',
                ];
            });
            $quantities = $items->groupBy('productId')->map(fn ($productItems) => $productItems->sum('qty'));

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);
                abort_if($quantity > $product->stock, 422, 'Jumlah pesanan melebihi stok yang tersedia.');
                $product->decrement('stock', $quantity);
                $product->increment('sold', $quantity);
            }

            $totalItems = $items->sum('qty');
            $subtotal = $items->sum(fn (array $item) => $item['product']['price'] * $item['qty']);
            $shippingCost = (float) $validated['shippingMethod']['price'];
            $firstProduct = $items->first()['product'];

            return Order::create([
                'id' => 'ORD-'.now()->format('Y').'-'.Str::upper(Str::random(6)),
                'product_name' => count($items) === 1 ? $firstProduct['name'] : $firstProduct['name'].' (+'.(count($items) - 1).' produk lainnya)',
                'buyer_name' => $validated['buyerName'],
                'phone' => $validated['phone'],
                'total' => $subtotal + $shippingCost,
                'qty' => $totalItems,
                'status' => 'diproses',
                'shop_name' => $firstProduct['shopName'],
                'kecamatan' => $validated['kecamatan'] ?? $firstProduct['kecamatan'],
                'address' => $validated['address'],
                'items' => $items->all(),
                'shipping_method' => $validated['shippingMethod']['name'],
                'payment_method' => $validated['paymentMethod']['name'],
                'notes' => $validated['notes'] ?? '',
            ]);
        });

        return response()->json($this->serializeOrder($order), 201);
    }

    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $user = $request->user();
        abort_unless(
            $user->role === 'admin' || ($user->shop_name && $order->shop_name === $user->shop_name),
            403,
            'Hanya admin atau pemilik toko yang dapat mengubah status pesanan ini.'
        );
        $validated = $request->validate(['status' => ['required', Rule::in(['diproses', 'diambil', 'dikirim', 'selesai', 'ditolak'])]]);
        $order->update(['status' => $validated['status']]);

        return response()->json($this->serializeOrder($order));
    }

    public function products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'shop'])
            ->when(! ($request->boolean('include_hidden') && $request->user()?->role === 'admin'), fn ($query) => $query->where('is_hidden', false))
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->string('category')))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->get()
            ->map(fn (Product $product) => $this->serializeProduct($product));

        return response()->json($products);
    }

    public function showProduct(Product $product): JsonResponse
    {
        return response()->json($this->serializeProduct($product->load(['category', 'shop'])));
    }

    public function storeProduct(Request $request): JsonResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'penjual', 'keduanya'], true), 403, 'Akun ini tidak memiliki toko penjual.');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'originalPrice' => ['nullable', 'numeric', 'min:0'],
            'category' => ['required', 'string', Rule::exists('categories', 'id')],
            'shopId' => ['required', 'string', Rule::exists('shops', 'id')],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'array'],
            'reviews' => ['nullable', 'array'],
        ]);
        abort_unless($request->user()->role === 'admin' || $validated['shopId'] === $request->user()->shop_id, 403, 'Produk hanya dapat ditambahkan ke toko milik akun ini.');

        $product = Product::create([
            'id' => 'prod-'.Str::uuid(),
            'name' => $validated['name'],
            'price' => $validated['price'],
            'original_price' => $validated['originalPrice'] ?? null,
            'category_id' => $validated['category'],
            'shop_id' => $validated['shopId'],
            'kecamatan' => $validated['kecamatan'] ?? null,
            'stock' => $validated['stock'],
            'images' => $validated['images'] ?? [],
            'description' => $validated['description'] ?? '',
            'specifications' => $validated['specifications'] ?? [],
            'reviews' => $validated['reviews'] ?? [],
            'rating' => 5,
            'sold' => 0,
            'is_new' => true,
        ]);

        return response()->json($this->serializeProduct($product->load(['category', 'shop'])), 201);
    }

    public function updateVisibility(Request $request, Product $product): JsonResponse
    {
        abort_unless($request->user()->role === 'admin' || $product->shop_id === $request->user()->shop_id, 403, 'Produk ini bukan milik toko akun ini.');
        $validated = $request->validate(['isHidden' => ['required', 'boolean']]);
        $product->update(['is_hidden' => $validated['isHidden']]);

        return response()->json($this->serializeProduct($product->load(['category', 'shop'])));
    }

    public function updateProductNumbers(Request $request, Product $product): JsonResponse
    {
        abort_unless($request->user()->role === 'admin' || $product->shop_id === $request->user()->shop_id, 403, 'Produk ini bukan milik toko akun ini.');
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
        $product->update($validated);

        return response()->json($this->serializeProduct($product->load(['category', 'shop'])));
    }

    public function destroyProduct(Request $request, Product $product): JsonResponse
    {
        abort_unless($request->user()->role === 'admin' || $product->shop_id === $request->user()->shop_id, 403, 'Produk ini bukan milik toko akun ini.');
        $product->delete();

        return response()->json(status: 204);
    }

    private function serializeProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'originalPrice' => $product->original_price,
            'category' => $product->category_id,
            'categoryName' => $product->category?->name,
            'shopId' => $product->shop_id,
            'shopName' => $product->shop?->name,
            'kecamatan' => $product->kecamatan ?? $product->shop?->kecamatan,
            'stock' => $product->stock,
            'rating' => $product->rating,
            'sold' => $product->sold,
            'isFeatured' => $product->is_featured,
            'isPopular' => $product->is_popular,
            'isNew' => $product->is_new,
            'isHidden' => $product->is_hidden,
            'images' => $product->images ?? [],
            'description' => $product->description,
            'specifications' => $product->specifications ?? [],
            'reviews' => $product->reviews ?? [],
        ];
    }

    private function serializeOrder(Order $order): array
    {
        return [
            'id' => $order->id,
            'productName' => $order->product_name,
            'buyerName' => $order->buyer_name,
            'phone' => $order->phone,
            'date' => $order->created_at?->format('d M Y'),
            'createdAt' => $order->created_at?->toIso8601String(),
            'total' => $order->total,
            'qty' => $order->qty,
            'status' => $order->status,
            'shopName' => $order->shop_name,
            'kecamatan' => $order->kecamatan,
            'address' => $order->address,
            'items' => $order->items ?? [],
            'shippingMethod' => $order->shipping_method,
            'paymentMethod' => $order->payment_method,
            'notes' => $order->notes,
        ];
    }
}
