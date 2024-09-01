<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Helpers\UploadImage;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Products\StoreRequest;
use App\Http\Requests\Products\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    use ApiResponseHelper;
    use UploadImage;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = App::getLocale();

        // Fetch all cities with translations based on the current locale
        $products = Product::withTranslation($locale)->get();

        // Transform the response to include only the necessary data
        $transformedProducts = $products->map(function ($product) use ($locale) {
            $translatedName = optional($product->translate($locale))->name;
            $translatedDescription = optional($product->translate($locale))->description;

            return [
                'id' => $product->id,
                'name' => $translatedName,
                'description' => $translatedDescription,
                'image' =>  $product->img !== null ? $product->img : null,
                'category_id' => $product->category_id
            ];
        });
        // Return the transformed response
        return $this->setCode(200)->setMessage('Success')->setData($transformedProducts)->send();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $photo =$this->UploadImage($request, 'Products/Images');

        $product = new Product;

        // Set translations for languages
        $product->translateOrNew('en')->name = $request->name_en;
        $product->translateOrNew('ar')->name = $request->name_ar;
        $product->translateOrNew('en')->description = $request->description_en;
        $product->translateOrNew('ar')->description = $request->description_ar;
        $product->img = $photo == null ? null :env('APP_URL') . $photo;
        $product->category_id = $request->category_id;
          // Save the category with translations
        $product->save();


          // Return response
        return $this->setCode(200)->setMessage('Success')->setData($product)->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try{
        $product = Product::withTranslation(App::getLocale())->findOrFail($id);

        // Return the response
        return $this->setCode(200)->setMessage('Success')->setData([
            'id' => $product->id,
            'name' => optional($product->translate(App::getLocale()))->name,
            'description' => optional($product->translate(App::getLocale()))->description,
            'image' =>  $product->img !== null ? $product->img : null,
            'category_id' => $product->category_id
        ])->send();
    } catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Product Not Found')->send();
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try{
        $product = Product::findOrFail($id);

        if($request->hasFile('img')){
            $photo =$this->UploadImage($request, 'Products/Images', $product->img);
            }else{
                $photo = null;
            }
        // Update translations for languages
        if ($request->filled('name_en')) {
            $product->translateOrNew('en')->name = $request->name_en;
        }

        if ($request->filled('name_ar')) {
            $product->translateOrNew('ar')->name = $request->name_ar;
        }
        if ($request->filled('description_en')) {
            $product->translateOrNew('en')->description = $request->description_en;
        }

        if ($request->filled('description_ar')) {
            $product->translateOrNew('ar')->description = $request->description_ar;
        }

        $product->category_id = $request->category_id == null ?$product->category_id: $request->category_id;
        $product->img = $photo == null ? $product->img :env('APP_URL') . $photo;

        // Save the category with translations
        $product->save();

        // Return response
        return $this->setCode(200)->setMessage('Success')->setData($product)->send();
    } catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Product Not Found')->send();
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        // Fetch the category
        $product = Product::findOrFail($id);
        if($product->img)
        {
            if (File::exists(str_replace(env('APP_URL'), "", $product->img))) {
                File::delete(str_replace(env('APP_URL'), "", $product->img));
            }
        }
        // Delete the category with translations
        $product->delete();

        // Return response
        return $this->setCode(200)->setMessage('Success')->send();
    }
    catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Product Not Found')->send();
    }
    }


    public function filter(int $id)
    {
        try{
        $products = Product::where('category_id', $id)->withTranslation(App::getLocale())->get();

        // Transform the response to include only the necessary data
        $transformedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => optional($product->translate(App::getLocale()))->name,
                'description' => optional($product->translate(App::getLocale()))->description,
                'image' =>  $product->img !== null ? $product->img : null,
                'category_id' => $product->category_id
            ];
        });

        // Return the transformed response
        return $this->setCode(200)->setMessage('Success')->setData($transformedProducts)->send();
        } catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Category Not Found')->send();
        }
    }
}
