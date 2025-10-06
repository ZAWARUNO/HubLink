<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Component;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'order' => 'required|integer'
            ]);

            $component->update([
                'properties' => $request->properties,
                'order' => $request->order
            ]);

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
}
