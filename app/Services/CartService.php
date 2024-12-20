<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\CartItemRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartService
{
    protected $cartRepository;
    protected $cartItemRepository;
    protected $productRepository;

    public function __construct(CartRepository $cartRepository, CartItemRepository $cartItemRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
    }

    public function getCart($id): JsonResponse
    {
        if (!$this->cartRepository->exists($id)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $cart = $this->cartRepository->getById($id);

            return response()->json(['success' => true, 'data' => $cart], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function createCart($data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $cart = $this->cartRepository->store($data);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create cart: ' . $e->getMessage());

            throw new \RuntimeException('Unable to create cart');
        }
        DB::commit();

        return $cart;
    }

    public function checkStockAvailability(int $productId, int $quantity): bool
    {
        $product = $this->productRepository->getById($productId);

        return $product->stock >= $quantity;
    }

    public function addItemToCart($cartId, $data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->cartRepository->exists($cartId)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        if ($this->cartItemRepository->itemExists($cartId, $data['product_id'])) {
            return response()->json(['success' => false, 'message' => 'Product already in cart'], Response::HTTP_CONFLICT);
        }

        if (!$this->checkStockAvailability($data['product_id'], $data['quantity'])) {
            return response()->json(['success' => false, 'message' => 'Insufficient stock'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $item = $this->cartItemRepository->addItem($cartId, $data);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        DB::commit();

        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function removeItemFromCart($cartId, $productId): JsonResponse
    {
        if (!$this->cartRepository->exists($cartId)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->cartItemRepository->itemExists($cartId, $productId)) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart'], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $this->cartItemRepository->removeItem($cartId, $productId);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        DB::commit();

        return response()->json(['success' => true, 'message' => 'Item removed successfully'], Response::HTTP_OK);
    }

    public function updateItemQuantity($cartId, $productId, $quantity): JsonResponse
    {
        if (!$this->cartRepository->exists($cartId)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->cartItemRepository->itemExists($cartId, $productId)) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart'], Response::HTTP_NOT_FOUND);
        }

        if ($quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Quantity must be at least 1'], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->checkStockAvailability($productId, $quantity)) {
            return response()->json(['success' => false, 'message' => 'Insufficient stock'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $item = $this->cartItemRepository->updateQuantity($cartId, $productId, $quantity);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        DB::commit();

        return response()->json(['success' => true, 'data' => $item], Response::HTTP_OK);
    }

    public function clearCart($cartId): JsonResponse
    {
        if (!$this->cartRepository->exists($cartId)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $cartItems = $this->cartItemRepository->getItems($cartId);

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart is already empty'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $this->cartItemRepository->clearCart($cartId);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        DB::commit();

        return response()->json(['success' => true, 'message' => 'Cart cleared successfully'], Response::HTTP_OK);
    }
}
