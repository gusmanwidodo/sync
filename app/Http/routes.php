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

    $indo_companies = $indo->table('companies')->where('imported', 0)->orderBy('id', 'asc')->paginate(10);

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

    $indo_products = $indo->table('products')->where('imported', 0)->orderBy('id', 'asc')->paginate(10);

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

Route::get('/recheck_product', function() {

  DB::beginTransaction();
  
  $products = App\Product::where('company_id', 0)->paginate(10);

  $indo = DB::connection('indonetwork');

  foreach ($products as $product) {

    $indo_product = $indo->table('products')
      ->where('name', $product->name)
      ->where('price', $product->price)
      ->where('min_qty', $product->min_qty)
      ->where('stock', $product->stock)
      ->first();

    $company = $indo->table('companies')->where('link', 'http:'. $indo_product->company_url)->first();

    $company_id = (count($company) > 0) ? $company->id : 0;
    
    $product->company_id = $company_id;
    $product->save();

  }

  $delete_product = App\Product::where('name', 'no title')->delete();
  $delete_company = App\Company::where('name', 'anonymous')->delete();

  DB::commit();

  return 'success';

});

Route::get('/retrieveimg', function(){
  $images = App\Image::where('path', 'like', 'http://img.indonetwork.co.id%')->paginate(25);
  $opts = array(
    'http'=>array(
      'method'=>"GET",
      'header'=>"Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
    )
  );
  
    $s3 = Storage::disk('s3');
    $i = 0;
  foreach($images as $image){
    $xfile = explode("/", $image->path);
    $context = stream_context_create($opts);
    $content_img = file_get_contents($image->path, false, $context);
    $fname = 'img/product/'.date('Y').'/'.date('m').'/'.end($xfile);
    if($s3->put($fname, $content_img)){
      $image->path = $fname;
      $image->save();
      $i++;
    }
  }
  echo $i.' retrieve';
});
