<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Component;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuilderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $domains = $user->domains; // Get all domains for the user

        return view('cms.pages.builder-index', compact('domains'));
    }

    public function show($domainId)
    {
        $user = Auth::user();
        $domain = $user->domains()->findOrFail($domainId);

        // Get published components for preview
        $publishedComponents = $domain->components()
            ->where('is_published', true)
            ->orderBy('order')
            ->get();

        // Get all components for builder
        $components = $domain->components()->orderBy('order')->get();

        return view('cms.pages.builder', compact('domain', 'components', 'publishedComponents'));
    }

    public function storeComponent(Request $request, $domainId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);

            $request->validate([
                'type' => 'required|string',
                'properties' => 'required|array',
                'order' => 'required|integer'
            ]);

            $component = $domain->components()->create([
                'type' => $request->type,
                'properties' => $request->properties,
                'order' => $request->order,
                'is_published' => false
            ]);

            return response()->json($component);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create component: ' . $e->getMessage()], 500);
        }
    }

    public function updateComponent(Request $request, $domainId, $componentId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);
            $component = $domain->components()->findOrFail($componentId);

            $request->validate([
                'properties' => 'required|array',
                'order' => 'required|integer',
                'digital_product_path' => 'nullable|string'
            ]);

            // Prepare update data
            $updateData = [
                'properties' => $request->properties,
                'order' => $request->order
            ];

            // Handle digital_product_path separately
            if ($request->has('digital_product_path')) {
                $updateData['digital_product_path'] = $request->digital_product_path;
            }

            $component->update($updateData);

            return response()->json($component);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update component: ' . $e->getMessage()], 500);
        }
    }

    public function deleteComponent(Request $request, $domainId, $componentId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);
            $component = $domain->components()->findOrFail($componentId);

            $component->delete();

            // Reorder remaining components
            $this->reorderComponentsHelper($domain);

            return response()->json(['message' => 'Component deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete component: ' . $e->getMessage()], 500);
        }
    }

    public function reorderComponents(Request $request, $domainId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);

            $request->validate([
                'components' => 'required|array'
            ]);

            foreach ($request->components as $index => $componentData) {
                $component = $domain->components()->find($componentData['id']);
                if ($component) {
                    $component->update(['order' => $index]);
                }
            }

            return response()->json(['message' => 'Components reordered successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reorder components: ' . $e->getMessage()], 500);
        }
    }

    public function publishComponents(Request $request, $domainId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);

            // Set all components to published
            $domain->components()->update(['is_published' => true]);

            return response()->json(['message' => 'Components published successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to publish components: ' . $e->getMessage()], 500);
        }
    }

    private function reorderComponentsHelper($domain)
    {
        $components = $domain->components()->orderBy('order')->get();
        foreach ($components as $index => $component) {
            $component->update(['order' => $index]);
        }
    }
    
    public function uploadImage(Request $request, $domainId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);

            // Validate the request
            $validator = \Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed: ' . $validator->errors()->first()], 400);
            }

            // Check if file was uploaded
            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'No image file provided'], 400);
            }

            // Check if file is valid
            if (!$request->file('image')->isValid()) {
                return response()->json(['error' => 'Invalid image file'], 400);
            }

            // Store the image in the public disk
            $imagePath = $request->file('image')->store('builder-images', 'public');

            // Check if storage was successful
            if (!$imagePath) {
                return response()->json(['error' => 'Failed to store image'], 500);
            }

            // Return the URL of the uploaded image
            $imageUrl = Storage::url($imagePath);
            
            return response()->json(['url' => $imageUrl]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload image: ' . $e->getMessage()], 500);
        }
    }

    public function uploadDigitalProduct(Request $request, $domainId)
    {
        try {
            $user = Auth::user();
            $domain = $user->domains()->findOrFail($domainId);

            // Validate the request
            $validator = \Validator::make($request->all(), [
                'file' => 'required|file|max:10240|mimes:pdf,zip,doc,docx,xls,xlsx,jpg,jpeg,png,gif', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed: ' . $validator->errors()->first()], 400);
            }

            // Check if file was uploaded
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No file provided'], 400);
            }

            // Check if file is valid
            if (!$request->file('file')->isValid()) {
                return response()->json(['error' => 'Invalid file'], 400);
            }

            // Store the file in local disk (storage/app/digital-products)
            $filePath = $request->file('file')->store('digital-products', 'local');

            // Check if storage was successful
            if (!$filePath) {
                return response()->json(['error' => 'Failed to store file'], 500);
            }

            // Return the file path and original name
            return response()->json([
                'path' => $filePath,
                'originalName' => $request->file('file')->getClientOriginalName(),
                'fileType' => $request->file('file')->getClientMimeType(),
                'fileSize' => $request->file('file')->getSize()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload file: ' . $e->getMessage()], 500);
        }
    }
}
