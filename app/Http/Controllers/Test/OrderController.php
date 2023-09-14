<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudPayments\Manager;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function test_add_order(Request $request){
        // Replace with your CloudPayments API credentials
        $publicId =   env('CLOUDPAYMENTS_PUBLIC_KEY');
        $apiPassword =   env('CLOUDPAYMENTS_API_PASSWORD');

        // Prepare the payment data
        $amount = 10;
        $currency = 'RUB'; // Set the currency code according to your requirements

        $paymentData = [
            'Amount' => $amount,
            'Currency' => $currency,
            'AccountId' => 1, // Set the account ID or user ID
            'Description' => 'Payment for services', // Set the payment description
            'CardCryptogramPacket' => 'eyJUeXBlIjoiQ2xvdWRDYXJkIiwiQnJvd3NlckluZm9CYXNlNjQiOiJleUpCWTJObGNIUklaV0ZrWlhJaU9pSXFMeW9pTENKS1lYWmhSVzVoWW14bFpDSTZabUZzYzJVc0lrcGhkbUZUWTNKcGNIUkZibUZpYkdWa0lqcDBjblZsTENKTVlXNW5kV0ZuWlNJNkluSjFMVkpWSWl3aVEyOXNiM0pFWlhCMGFDSTZJakkwSWl3aVNHVnBaMmgwSWpvaU9UQXdJaXdpVjJsa2RHZ2lPaUl4TmpBd0lpd2lWR2x0WlZwdmJtVWlPaUl0TWpRd0lpd2lWWE5sY2tGblpXNTBJam9pVFc5NmFXeHNZUzgxTGpBZ0tGZHBibVJ2ZDNNZ1RsUWdNVEF1TURzZ1YybHVOalE3SUhnMk5Da2dRWEJ3YkdWWFpXSkxhWFF2TlRNM0xqTTJJQ2hMU0ZSTlRDd2diR2xyWlNCSFpXTnJieWtnUTJoeWIyMWxMekV4TXk0d0xqQXVNQ0JUWVdaaGNta3ZOVE0zTGpNMkluMD0iLCJGb3JtYXQiOjEsIkNhcmRJbmZvIjp7IkZpcnN0U2l4RGlnaXRzIjoiNDA4MzA2IiwiTGFzdEZvdXJEaWdpdHMiOiI4MTcwIiwiRXhwRGF0ZVllYXIiOiIyNCIsIkV4cERhdGVNb250aCI6IjAyIn0sIktleVZlcnNpb24iOiIyIiwiVmFsdWUiOiJKYzViMEpYOHE0OHJKczRBZ0xVNFBDV0NCWTJzVkFWSWczNDlCMUU5TXQ4Y0lQVXlTd0NwUS9GMDcwZWp5a1Bqc0FrNEk3Q29Xd2F4QkNQSEN0YUlyQjd3ekJ2ODhTK0xSSVR5b1hzUzR0OGRrQms3OEdtTEVPdzBMOGUvZlVNa1ZYcC92aGJiU096MGEzV1JWQXJXcmJiaHNVeDJEWW1yc1JhSzlOMlA5UTRrcE8reUdLOTljQk1NZWJrbHdXTkRqREYyWjBZY1hDK1h0cmJDeVMxUi85dlpuODZaRzJlcm5NalVaVVR3RklrdFoyeDVrNjJ6bFpxLy9hUVRqMytaWUxENVlEOUhyNEhqQmFWK0dLU1JXZk9sMUVRbmQxUU1zd1RKSGxWQWprOXlFK29TSGdOZFRsbFFEM1RMOStDQ0J2VzN4UTh3b25JN3JxZHhXOXp0SkE9PSJ9'
            // Add other optional parameters as needed
        ];

        // Make API request to create a payment
        $response = Http::withBasicAuth($publicId, $apiPassword)
            ->post('https://api.cloudpayments.ru/payments/cards/charge', $paymentData);

        // Process the API response
        if ($response->successful()) {
            // Payment creation successful
            $paymentResult = $response->json();
            dd($paymentResult );
            // Handle the payment result, e.g., save payment details, generate payment form, etc.
            // Redirect the user to the payment form or show a success message
        } else {
            // Payment creation failed
            $errorResponse = $response->json();
            // Handle the error response, e.g., display an error message
            // Redirect the user to the payment form with an error message
        }
    }
}
