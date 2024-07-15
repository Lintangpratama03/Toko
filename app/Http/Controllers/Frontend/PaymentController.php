<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
	public function notification(Request $request)
	{
		$payload = $request->getContent();
		$notification = json_decode($payload);

		$validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . 'SB-Mid-server-BIK2lfIQpGfaMi3GxG5K9VLX');

		if ($notification->signature_key != $validSignatureKey) {
			return response(['message' => 'Invalid signature'], 403);
		}

		$this->initPaymentGateway();
		$statusCode = null;

		$paymentNotification = new \Midtrans\Notification();
		$order = Order::where('code', $paymentNotification->order_id)->firstOrFail();

		if ($order->isPaid()) {
			return response(['message' => 'Pesanan telah dibayar'], 422);
		}

		$transaction = $paymentNotification->transaction_status;
		$type = $paymentNotification->payment_type;
		$orderId = $paymentNotification->order_id;
		$fraud = $paymentNotification->fraud_status;

		$vaNumber = null;
		$vendorName = null;
		if (!empty($paymentNotification->va_numbers[0])) {
			$vaNumber = $paymentNotification->va_numbers[0]->va_number;
			$vendorName = $paymentNotification->va_numbers[0]->bank;
		}

		$paymentStatus = null;
		if ($transaction == 'capture') {
			if ($type == 'credit_card') {
				if ($fraud == 'challenge') {
					$paymentStatus = Payment::CHALLENGE;
				} else {
					$paymentStatus = Payment::SUCCESS;
				}
			}
		} else if ($transaction == 'settlement') {
			$paymentStatus = Payment::SETTLEMENT;
		} else if ($transaction == 'pending') {
			$paymentStatus = Payment::PENDING;
		} else if ($transaction == 'deny') {
			$paymentStatus = Payment::DENY;
		} else if ($transaction == 'expire') {
			$paymentStatus = Payment::EXPIRE;
		} else if ($transaction == 'cancel') {
			$paymentStatus = Payment::CANCEL;
		}

		$paymentParams = [
			'order_id' => $order->id,
			'number' => Payment::generateCode(),
			'amount' => $paymentNotification->gross_amount,
			'method' => 'midtrans',
			'status' => $paymentStatus,
			'token' => $paymentNotification->transaction_id,
			'payloads' => $payload,
			'payment_type' => $paymentNotification->payment_type,
			'va_number' => $vaNumber,
			'vendor_name' => $vendorName,
			'biller_code' => $paymentNotification->biller_code,
			'bill_key' => $paymentNotification->bill_key,
		];

		$payment = Payment::create($paymentParams);

		if ($paymentStatus && $payment) {
			\DB::transaction(function () use ($order, $payment) {
				if (in_array($payment->status, [Payment::SUCCESS, Payment::SETTLEMENT])) {
					$order->payment_status = Order::PAID;
					$order->status = Order::CONFIRMED;
					$order->save();
				}
			});
		}

		$message = 'Payment status is : ' . $paymentStatus;

		$response = [
			'code' => 200,
			'message' => $message,
		];

		return response($response, 200);
	}

	private function mapPaymentStatus($transaction, $fraud)
	{
		$paymentStatus = Payment::PENDING;
		if ($transaction == 'capture') {
			$paymentStatus = ($fraud == 'challenge') ? Payment::CHALLENGE : Payment::SUCCESS;
		} elseif ($transaction == 'settlement') {
			$paymentStatus = Payment::SETTLEMENT;
		} elseif (in_array($transaction, ['pending', 'deny', 'expire', 'cancel'])) {
			$paymentStatus = Payment::PENDING;
		}
		return $paymentStatus;
	}

	public function completed(Request $request)
	{
		$code = $request->query('order_id');
		$order = Order::where('code', $code)->firstOrFail();

		if ($order->payment_status == Order::UNPAID) {
			return redirect('payments/failed?order_id=' . $code);
		}

		return redirect('orders/received/' . $order->id);
	}

	public function unfinish(Request $request)
	{
		$code = $request->query('order_id');
		$order = Order::where('code', $code)->firstOrFail();

		return redirect('orders/received/' . $order->id);
	}

	public function failed(Request $request)
	{
		$code = $request->query('order_id');
		$order = Order::where('code', $code)->firstOrFail();

		return redirect('orders/received/' . $order->id);
	}

	protected function initPaymentGateway()
	{
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = 'SB-Mid-server-BIK2lfIQpGfaMi3GxG5K9VLX';
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = config('midtrans.isProduction');
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;
	}
}
