<?php

namespace App\Http\Controllers;

use App\Services\CompanySettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class CompanyController extends Controller
{
    protected $companySettingsService;

    /**
     * Constructor
     */
    public function __construct(CompanySettingsService $companySettingsService)
    {
        $this->companySettingsService = $companySettingsService;
    }

    /**
     * Display company settings page
     */
    public function settings(): View
    {
        try {
            $settings = $this->companySettingsService->getSettings();
            return view('settings.company', compact('settings'));
        } catch (Exception $e) {
            $settings = null;
            return view('settings.company', compact('settings'))->with('error', $e->getMessage());
        }
    }

    /**
     * Update company settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        try {
            // Debug: Log request data
            Log::info('Company Settings Request Data:', $request->all());

            // Validation rules
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_name_en' => 'nullable|string|max:255',
                'commercial_number' => 'nullable|string|max:50',
                'tax_number' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'address' => 'nullable|string|max:1000',
                'currency' => 'required|in:SAR,USD,EUR',
                'date_format' => 'required|in:d/m/Y,m/d/Y,Y-m-d',
                'decimal_places' => 'required|integer|min:0|max:4',
                'enable_notifications' => 'nullable|boolean',
            ]);

            // Handle checkbox values properly
            $validated['enable_notifications'] = $request->input('enable_notifications', 0) == '1' ||
                $request->input('enable_notifications') === true;

            $settings = $this->companySettingsService->updateSettings($validated);

            return response()->json([
                'success' => true,
                'message' => __('app.settings_saved_successfully'),
                'data' => $settings
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.validation_error'),
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload company logo
     */
    public function uploadLogo(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $logoPath = $this->companySettingsService->uploadLogo($request->file('logo'));

            return response()->json([
                'success' => true,
                'message' => __('app.logo_uploaded_successfully'),
                'logo_path' => asset('storage/' . $logoPath)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete company logo
     */
    public function deleteLogo(): JsonResponse
    {
        try {
            $deleted = $this->companySettingsService->deleteLogo();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => __('app.logo_deleted_successfully')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('app.no_logo_found')
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get company settings as JSON
     */
    public function getSettings(): JsonResponse
    {
        try {
            $settings = $this->companySettingsService->getSettings();

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reset settings to default values
     */
    public function resetSettings(): JsonResponse
    {
        try {
            $settings = $this->companySettingsService->resetToDefaults();

            return response()->json([
                'success' => true,
                'message' => __('app.settings_reset_successfully'),
                'data' => $settings
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Format number with company decimal places setting
     */
    public function formatNumber(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'number' => 'required|numeric'
            ]);

            $formattedNumber = $this->companySettingsService->formatNumber(
                $request->input('number'),
                $request->input('decimal_places')
            );

            return response()->json([
                'success' => true,
                'formatted_number' => $formattedNumber
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Format currency with company settings
     */
    public function formatCurrency(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|numeric'
            ]);

            $formattedCurrency = $this->companySettingsService->formatCurrency(
                $request->input('amount'),
                $request->input('decimal_places')
            );

            return response()->json([
                'success' => true,
                'formatted_currency' => $formattedCurrency
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
