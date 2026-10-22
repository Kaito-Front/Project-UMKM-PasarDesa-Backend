<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['belanja', 'jualan', 'keduanya'])],
            'shopName' => ['nullable', 'required_if:role,jualan,keduanya', 'string', 'max:255'],
            'shopCategory' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
        ]);

        $role = ['belanja' => 'pembeli', 'jualan' => 'penjual', 'keduanya' => 'keduanya'][$data['role']];
        $email = $data['phone'].'@pasardesa.id';
        $shop = null;

        if (in_array($data['role'], ['jualan', 'keduanya'], true)) {
            $category = Category::query()->where('name', $data['shopCategory'] ?? '')->first();
            $shop = Shop::create([
                'id' => 'toko-'.Str::uuid(), 'name' => $data['shopName'], 'owner' => $data['name'],
                'category' => $category?->name ?? 'UMKM Desa', 'category_slug' => $category?->id ?? 'kuliner',
                'kecamatan' => $data['kecamatan'] ?? '', 'address' => $data['kecamatan'] ?? '',
                'phone' => $data['phone'], 'whatsapp' => preg_replace('/\D+/', '', $data['phone']),
                'rating' => 0, 'review_count' => 0, 'join_date' => now()->translatedFormat('F Y'),
                'operating_hours' => '08.00 - 17.00 WIB', 'description' => '', 'status' => 'menunggu', 'verified' => false,
            ]);
        }

        $user = User::create([
            'name' => $data['name'], 'email' => $email, 'phone' => $data['phone'], 'password' => $data['password'],
            'role' => $role, 'shop_id' => $shop?->id, 'shop_name' => $shop?->name, 'kecamatan' => $data['kecamatan'] ?? null,
        ]);

        return $this->tokenResponse($user, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate(['identifier' => ['required', 'string'], 'password' => ['required', 'string']]);
        $user = User::query()->where('email', $data['identifier'])->orWhere('phone', $data['identifier'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Nomor HP/email atau kata sandi tidak cocok.'], 422);
        }

        return $this->tokenResponse($user);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($this->serializeUser($request->user()));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar.']);
    }

    private function tokenResponse(User $user, int $status = 200): JsonResponse
    {
        return response()->json([
            'token' => $user->createToken('frontend')->plainTextToken,
            'user' => $this->serializeUser($user),
        ], $status);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => (string) $user->id, 'name' => $user->name, 'phone' => $user->phone, 'email' => $user->email,
            'role' => $user->role, 'shopId' => $user->shop_id, 'shopName' => $user->shop_name,
            'kecamatan' => $user->kecamatan,
        ];
    }
}
