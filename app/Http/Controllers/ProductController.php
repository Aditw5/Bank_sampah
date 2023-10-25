<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $categorys = Category::all();

        return view('admin.product.product', compact('categorys'));
    }

    public function api() 
    {
        $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'name')
            ->orderBy('id')->get();

            return datatables()->of($products)
            ->addIndexColumn()
            ->addColumn('buy_price', function ($product) {
                return format_uang($product->buy_price);
            })
            ->addColumn('select_all', function ($product) {
                return '<input type="checkbox" name="id[]" value="'. $product->id .'">';
            })
            ->addColumn('foto', function ($product) {
                return '<a href="' . route('products.showImage', $product->foto) . '" target="_blank">View Image</a>';
            })
            ->rawColumns(['select_all', 'foto'])
            ->make(true);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $product_id) {
            $product = Product::find($product_id);
            $product->delete();
        }

        return response(null, 204);
    }

    public function PrintBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id as $product_id) {
            $product = Product::find($product_id);
            $dataproduk[] = $product;
        }
    
        $no  = 1;
        $pdf = Pdf::loadView('admin.product.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::latest()->first();
        $request['product_code'] = 'P' . add_nol((int)$product->id + 1, 5);
        
        $this->validate($request, [
            'category_id' => ['required'],
            'product_code' => ['required'],
            'description' => ['required'],
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Batasan tipe dan ukuran gambar
            'product_name' => ['required'],
            'buy_price' => ['required'],
        ]);

        // Mengelola unggahan gambar
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img'), $imageName); // Simpan gambar di direktori public/img

            $product = new Product($request->all());
            $product->foto = $imageName; // Simpan nama file gambar dalam basis data
            $product->save();
        }

        return redirect('categorys');
    }


    /**
     * Display the specified resource.
     */
    public function showImage($filename)
    {
        $path = public_path('img/' . $filename);
        
        if (file_exists($path)) {
            return response()->file($path);
        } else {
            // Handle gambar tidak ditemukan
            return response('Gambar tidak ditemukan', 404);
        }
    }
    
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'category_id' => ['required'],
            'description' => ['required'],
            'foto' => ['required'],
            'product_name' => ['required'],
            'buy_price' => ['required'],
        ]);

        $product->update($request->all());

        return redirect('categorys');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
