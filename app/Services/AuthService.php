<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data, $isWeb = false)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['role'] = 1;

        if ($validator->fails()) {
            if ($isWeb) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($isWeb) {
            $data['role'] = 0;
        }

        DB::beginTransaction();
        try {
            $user = $this->userRepository->register($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new Exception('Unable to register user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::commit();

        if ($isWeb) {
            Auth::login($user);
            return redirect()->route('admin.dashboard')->with('success', 'Registration successful');
        }

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }


    public function login(array $data, $isWeb = false)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            if ($isWeb) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->getByEmail($data['email']);

        if (!$user || !Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $message = ['email' => 'Invalid credentials'];

            if ($isWeb) {
                return redirect()->back()->withErrors($message)->withInput();
            }

            return response()->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($isWeb) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function logout($user, $isWeb = false)
    {
        if ($isWeb) {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Logged out successfully');
        }

        if ($user->role = 1) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully',
        ], Response::HTTP_OK);
    }
}
