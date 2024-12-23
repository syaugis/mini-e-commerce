<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Client\Request;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function midtransCallback(Request $request)
    {
        try {
            $this->midtransService->handleNotification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification payload: ' . $e->getMessage()], 400);
        }

        if ($this->midtransService->isSignatureKeyVerified()) {
            try {
                $order = $this->midtransService->getOrder();
                $transactionStatus = $this->midtransService->getStatus();

                switch ($transactionStatus) {
                    case 'success':
                        $order->update(['status' => 1]); // Paid
                        $lastPayment = $order->payments()->latest()->first();
                        $lastPayment->update([
                            'status' => 'PAID',
                            'paid_at' => now(),
                        ]);
                        break;

                    case 'pending':
                        $order->update(['status' => 0]);
                        break;

                    case 'expire':
                    case 'cancel':
                    case 'failed':
                        $order->update(['status' => 4]); // Canceled
                        break;

                    default:
                        \Log::info('Status not recognized', ['order_id' => $order->order_id]);
                        break;
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Notication received',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'API Midtrans Error: ' . $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
