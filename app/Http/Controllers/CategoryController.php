<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\App;
use App\Http\Requests\Categories\StoreRequest;
use App\Http\Requests\Categories\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    use ApiResponseHelper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = App::getLocale();

    // Fetch all cities with translations based on the current locale
    $categories = Category::withTranslation($locale)->get();

    // Transform the response to include only the necessary data
    $transformedCategories = $categories->map(function ($category) use ($locale) {
        $translatedName = optional($category->translate($locale))->name;

        return [
            'id' => $category->id,
            'name' => $translatedName,
        ];
    });
    // Return the transformed response
    return $this->setCode(200)->setMessage('Success')->setData($transformedCategories)->send();
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
        $category = new Category;

      // Set translations for languages
        $category->translateOrNew('en')->name = $request->name_en;
        $category->translateOrNew('ar')->name = $request->name_ar;

        // Save the category with translations
        $category->save();


        // Return response
        return $this->setCode(200)->setMessage('Success')->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the category with translations based on the current locale
        try{
        $category = Category::withTranslation(App::getLocale())->findOrFail($id);

        // Return the response
        return $this->setCode(200)->setMessage('Success')->setData([
            'id' => $category->id,
            'name' => optional($category->translate(App::getLocale()))->name,
        ])->send();
    } catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Category Not Found')->send();
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

        // dd($request->all());
        // Fetch the category with translations based on the current locale
        try{
        $category = Category::findOrFail($id);

        // Update translations for languages
        if ($request->filled('name_en')) {
            $category->translateOrNew('en')->name = $request->name_en;
        }

        if ($request->filled('name_ar')) {
            $category->translateOrNew('ar')->name = $request->name_ar;
        }

        // Save the category with translations
        $category->save();

        // Return response
        return $this->setCode(200)->setMessage('Success')->send();
    } catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Category Not Found')->send();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
        // Fetch the category
        $category = Category::findOrFail($id);

        // Delete the category with translations
        $category->delete();

        // Return response
        return $this->setCode(200)->setMessage('Success')->send();
    }
    catch(ModelNotFoundException $e){
        return $this->setCode(404)->setMessage('Category Not Found')->send();
    }
    }
}
