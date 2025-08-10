<?php

namespace App\Http\Controllers;

use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    protected BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display the branches index page
     */
    public function index(): View
    {
        $branchesResult = $this->branchService->getAllBranches();
        $statsResult = $this->branchService->getBranchStatistics();

        $branches = $branchesResult['success'] ? $branchesResult['data'] : collect();
        $stats = $statsResult['success'] ? $statsResult['data'] : [];

        return view('master-data.branches.index', compact('branches', 'stats'));
    }

    /**
     * Get branches data for AJAX requests
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $paginate = $request->boolean('paginate', false);
            $perPage = $request->get('per_page', 15);

            if ($search) {
                $result = $this->branchService->searchBranches($search);
            } else {
                $result = $this->branchService->getAllBranches($paginate, $perPage);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created branch
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->only(['name', 'location', 'is_active']);
            $result = $this->branchService->create($data);

            return response()->json($result, $result['status'] ?? 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified branch
     */
    public function show(string $id): JsonResponse
    {
        try {
            $result = $this->branchService->find((int)$id);
            return response()->json($result, $result['status'] ?? 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified branch
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $data = $request->only(['name', 'location', 'is_active']);
            $result = $this->branchService->update((int)$id, $data);

            return response()->json($result, $result['status'] ?? 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified branch
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $result = $this->branchService->delete((int)$id);
            return response()->json($result, $result['status'] ?? 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle branch status
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $result = $this->branchService->toggleBranchStatus((int)$id);
            return response()->json($result, $result['status'] ?? 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of active branches
     */
    public function getActiveList(): JsonResponse
    {
        try {
            $result = $this->branchService->getActiveBranches();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }
}
