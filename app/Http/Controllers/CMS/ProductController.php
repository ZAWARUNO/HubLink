<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $domains = $user->domains;

        // Get all template components across all domains
        $products = Component::where('type', 'template')
            ->whereIn('domain_id', $domains->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cms.products.index', compact('products', 'domains'));
    }

    public function create()
    {
        $user = Auth::user();
        $domains = $user->domains;

        return view('cms.products.create', compact('domains'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'domain_id' => 'required|exists:domains,id,user_id,' . $user->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'digital_product' => 'nullable|file|max:10240|mimes:pdf,zip,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        try {
            // Verify domain belongs to user
            $domain = Domain::where('id', $request->domain_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Handle image upload
            $imagePath = 'https://placehold.co/400x300';
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product-images', 'public');
                $imagePath = Storage::url($imagePath);
            }

            // Handle digital product upload
            $digitalProduct = null;
            if ($request->hasFile('digital_product')) {
                $filePath = $request->file('digital_product')->store('digital-products', 'local');
                $digitalProduct = [
                    'path' => $filePath,
                    'originalName' => $request->file('digital_product')->getClientOriginalName(),
                    'fileType' => $request->file('digital_product')->getClientMimeType(),
                    'fileSize' => $request->file('digital_product')->getSize()
                ];
            }

            // Create template component
            $component = $domain->components()->create([
                'type' => 'template',
                'properties' => [
                    'image' => $imagePath,
                    'title' => $request->title,
                    'description' => $request->description ?? '',
                    'price' => $request->price,
                    'buttonText' => 'Buy Now',
                    'digitalProduct' => $digitalProduct ?? [
                        'path' => '',
                        'originalName' => '',
                        'fileType' => '',
                        'fileSize' => 0
                    ]
                ],
                'digital_product_path' => $digitalProduct['path'] ?? null,
                'order' => 0,
                'is_published' => false
            ]);

            return redirect()->route('cms.products.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $component = Component::where('type', 'template')
            ->whereIn('domain_id', $user->domains->pluck('id'))
            ->findOrFail($id);

        $domains = $user->domains;

        return view('cms.products.edit', compact('component', 'domains'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $component = Component::where('type', 'template')
            ->whereIn('domain_id', $user->domains->pluck('id'))
            ->findOrFail($id);

        $request->validate([
            'domain_id' => 'required|exists:domains,id,user_id,' . $user->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'digital_product' => 'nullable|file|max:10240|mimes:pdf,zip,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
        ]);

        try {
            // Verify domain belongs to user
            $domain = Domain::where('id', $request->domain_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Handle image upload
            $imagePath = $component->properties['image'] ?? 'https://placehold.co/400x300';
            if ($request->hasFile('image')) {
                // Delete old image if it's not a placeholder
                if ($imagePath && !str_contains($imagePath, 'placehold.co')) {
                    $oldImagePath = str_replace('/storage/', '', $imagePath);
                    Storage::disk('public')->delete($oldImagePath);
                }

                $imagePath = $request->file('image')->store('product-images', 'public');
                $imagePath = Storage::url($imagePath);
            }

            // Handle digital product upload
            $digitalProduct = $component->properties['digitalProduct'] ?? [
                'path' => '',
                'originalName' => '',
                'fileType' => '',
                'fileSize' => 0
            ];

            if ($request->hasFile('digital_product')) {
                // Delete old digital product
                if (!empty($digitalProduct['path'])) {
                    Storage::disk('local')->delete($digitalProduct['path']);
                }

                $filePath = $request->file('digital_product')->store('digital-products', 'local');
                $digitalProduct = [
                    'path' => $filePath,
                    'originalName' => $request->file('digital_product')->getClientOriginalName(),
                    'fileType' => $request->file('digital_product')->getClientMimeType(),
                    'fileSize' => $request->file('digital_product')->getSize()
                ];
            }

            // Update component
            $component->update([
                'domain_id' => $request->domain_id,
                'properties' => [
                    'image' => $imagePath,
                    'title' => $request->title,
                    'description' => $request->description ?? '',
                    'price' => $request->price,
                    'buttonText' => 'Buy Now',
                    'digitalProduct' => $digitalProduct
                ],
                'digital_product_path' => $digitalProduct['path'] ?? null,
            ]);

            return redirect()->route('cms.products.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $component = Component::where('type', 'template')
            ->whereIn('domain_id', $user->domains->pluck('id'))
            ->findOrFail($id);

        try {
            // Delete associated files
            if (!empty($component->properties['image']) && !str_contains($component->properties['image'], 'placehold.co')) {
                $imagePath = str_replace('/storage/', '', $component->properties['image']);
                Storage::disk('public')->delete($imagePath);
            }

            if (!empty($component->properties['digitalProduct']['path'])) {
                Storage::disk('local')->delete($component->properties['digitalProduct']['path']);
            }

            $component->delete();

            return redirect()->route('cms.products.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
