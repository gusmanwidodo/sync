<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
  return view('welcome');
});

Route::get('/company', function() {

    DB::beginTransaction();

    $indo = DB::connection('indonetwork');

    $indo_companies = $indo->table('companies')->where('imported', 0)->orderBy('id', 'asc')->paginate(50);

    if (count($indo_companies) == 0) return 'data empty';

   	foreach ($indo_companies as $key => $value) {

    	$company = new App\Company;

   		$region = App\Region::where('name', 'like', '%'. strtoupper($value->city) .'%')->first();

   		$region_id = (count($region) > 0) ? $region->id : 0;

   		$company->id = $value->id;
   		$company->region_id = $region_id;

      $company->name = ($value->name != '')?$value->name:'anonymous';
      $company->tagline = $value->tagline;
      $company->description = $value->description;
      $company->slug = $value->slug;
      $company->email = $value->email;
      $company->address = $value->address;
      $company->city = $value->city;
      $company->province = $value->province;
      $company->zipcode = $value->zipcode;
      $company->phone = $value->phone .' / '. $value->contact_person;
      $company->bank_name = $value->bank_name;
      $company->bank_number = $value->bank_number;
      $company->bank_owner = $value->bank_owner;
      $company->bank_address = $value->bank_address;
      $company->bank_city = $value->bank_city;
      $company->shippings = $value->shippings;
      $company->facebook = $value->facebook;
      $company->twitter = $value->twitter;
      $company->instagram = $value->instagram;
      $company->website = $value->website;
      $company->logo = $value->logo;
      $company->banner = $value->banner;
      $company->active = 1;

   		$company->save();

      $indo->table('companies')->where('id', $value->id)->update(['imported' => 1]);

   	}

   	DB::commit();

   	return 'success';

});

Route::get('/product', function() {

    DB::beginTransaction();

    $indo = DB::connection('indonetwork');

    $indo_products = $indo->table('products')->where('imported', 0)->orderBy('id', 'asc')->paginate(50);

    if (count($indo_products) == 0) return 'data empty';

    foreach ($indo_products as $key => $value) {

      $product = new App\Product;

      $company = $indo->table('companies')->where('link', 'http:'. $value->company_url)->first();

      $company_id = (count($company) > 0) ? $company->id : 0;

      $product->id = $value->id;

      $product->company_id = $company_id;

      $product->name = ($value->name!='')?$value->name:'no title';

      $product->description = $value->description;
      $product->price = $value->price;
      $product->min_qty = $value->min_qty;
      $product->stock = $value->stock;
      $product->status = 1;
      $product->publish = 1;

      $product->save();

      $product->categories()->attach($value->category_id);

      $images = App\Image::create(['file' => '', 'path' => $value->image]);

      $product->images()->attach($images);

      $indo->table('products')->where('id', $value->id)->update(['imported' => 1]);

    }

    DB::statement("UPDATE images SET path = REPLACE(path, '40x40', '343x343')");

    DB::commit();

    return 'success';

});
