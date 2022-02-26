<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();
        return view('admin.product.index', compact('product'));
    }

    public function add()
    { 
        $category = Category::all();
        return view('admin.product.add', compact('category'));
    }


    public function insert(Request $request)
    {
        $product = new Product();
        $product->category_id = $request->input('category_id'); 
        $product->naziv_proizvoda = $request->input('naziv_proizvoda');
        $product->opis = $request->input('opis');
        $product->cijena = $request->input('cijena');
        $product->kolicina = $request->input('kolicina');

        if ($request->hasFile('slika')) {
    
            $file = $request->file('slika');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/uploads/products', $filename);
            $product->slika = $filename;
        }
    
        $product->save();
        return redirect('/products')->with('status', "Proizvod uspješno dodan");

    }


    public function destroy($id)
    {
        $product=Product::findorfail($id);
        $path = 'assets/uploads/products'.$product->image;
        if (File::exists($path))
        {
            File::delete($path);
        }
        $product->delete();
        return redirect('/products')->with('status', "Proizvod uspješno obrisan");

    }

    public function edit($id)
    {
        $product=Product::find($id);
        $category=Category::all();
        return view('admin.product.edit')->with(['product'=>$product, 'category'=>$category]);

    }

    public function update(Request $request, $id)
    {
        $product=Product::find($id);
        $product->category_id = $request->input('category_id'); 
        $product->naziv_proizvoda = $request->input('naziv_proizvoda');
        $product->opis = $request->input('opis');
        $product->cijena = $request->input('cijena');
        $product->kolicina = $request->input('kolicina');

        if ($request->hasFile('slika')) 
        {
            $path = 'assets/uploads/products/'.$product->slika;
            if(File::exists($path))
            {
                File::delete($path);
            }
            $file = $request->file('slika');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/uploads/products', $filename);
            $product->slika = $filename;
        }
    
        $product->update();
        return redirect('/products')->with('status', "Proizvod uspješno ažuriran");


    }
}