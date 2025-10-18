<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(): JsonResponse
    {
        $departments = $this->departmentService->getAllDepartments();

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
        ]);

        $department = $this->departmentService->createDepartment($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully',
            'data' => $department,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $department = $this->departmentService->getDepartmentById($id);

        return response()->json([
            'success' => true,
            'data' => $department,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
        ]);

        $department = $this->departmentService->updateDepartment($id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully',
            'data' => $department,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->departmentService->deleteDepartment($id);

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully',
        ]);
    }

    public function members(string $id): JsonResponse
    {
        $members = $this->departmentService->getDepartmentMembers($id);

        return response()->json([
            'success' => true,
            'data' => $members,
        ]);
    }

    public function performance(): JsonResponse
    {
        $performance = $this->departmentService->getAllDepartmentsPerformance();

        return response()->json([
            'success' => true,
            'data' => $performance,
        ]);
    }
}
