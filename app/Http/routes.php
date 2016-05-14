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

Route::get('/exportcsv', function(){
    $page = isset($_GET['p']) && (int) $_GET['p'] > 0 ? (int) $_GET['p'] : 1;
    $csv_s = [
      'Aceh-Main View.csv',
      'Bali-Main View.csv',
      'Banten-Main View.csv',
      'Bengkulu-Main View.csv',
      'Daerah Istimewa Yogyakarta-Main View.csv',
      'DKI Jakarta-Main View.csv',
      'Gorontalo-Main View.csv',
      'Jambi-Main View.csv',
      'Jawa Barat-Main View.csv',
      'Jawa Tengah-Main View.csv',
      'Jawa Timur-Main View.csv',
      'Kalimantan Barat-Main View.csv',
      'Kalimantan Selatan-Main View.csv',
      'Kalimantan Tengah-Main View.csv',
      'Kalimantan Timur-Main View.csv',
      'Kalimantan Utara-Main View.csv',
      'Kepulauan Bangka Belitung-Main View.csv',
      'Kepulauan Riau-Main View.csv',
      'Lampung-Main View.csv',
      'Maluku Utara-Main View.csv',
      'Maluku-Main View.csv',
      'Nusa Tenggara Barat-Main View.csv',
      'Nusa Tenggara Timur-Main View.csv',
      'Papua Barat-Main View.csv',
      'Papua-Main View.csv',
      'Riau-Main View.csv',
      'Sulawesi Barat-Main View.csv',
      'Sulawesi Selatan-Main View.csv',
      'Sulawesi Tengah-Main View.csv',
      'Sulawesi Tenggara-Main View.csv',
      'Sulawesi Utara-Main View.csv',
      'Sumatera Barat-Main View.csv',
      'Sumatera Selatan-Main View.csv',
      'Sumatera Utara-Main View.csv',
    ];   
    $datas = Excel::load("resources/csv/".($csv_s[$page - 1]), 'UTF-8')->get();
    $exported = [];
    foreach($datas as $data){
      $r_data = new \stdClass();
      foreach($data as $item_key => $item_value){
          $r_data->$item_key = $item_value;
      }

      $region = App\Region::where('name', $r_data->kabupaten)->first();

      if($region && !$region->regent_id){
        $s3 = Storage::disk('s3');
        $bupati_id = 0;
        $wakil_bupati_id = 0;
        $bupati = null;
        if($r_data->bupati){
          preg_match_all('/https:\/\/dl.airtable.com\/(.*?).(jpg|png)/', $r_data->attachments, $matches);
          
          $bupati_image = '';
          $wakil_image = '';
          $opts = array(
            'http'=>array(
              'method'=>"GET",
              'header'=>"Accept-language: en\r\n" .
                        "Cookie: foo=bar\r\n"
            )
          );
          $context = stream_context_create($opts);
          if($matches){
              foreach($matches[0] as $match){
                  preg_match('/https:\/\/dl.airtable.com\/(.*)_(.*?).(jpg|png)/', $match, $m);
                  $str = strtolower($m[2]);
                  if($str == 'bupati' || $str == 'walikota'){
                      // Open the file using the HTTP headers set above
                      $content_img = file_get_contents($match, false, $context);
                      $fname = 'img/member/'.date('Y').'/'.date('m').'/'.str_replace('https://dl.airtable.com/', '', $match);
                      $bupati_image = $fname;
                      $s3->put($fname, $content_img);
                  }elseif($str == 'wakil-bupati' || $str == 'wakil-walikota'){
                      $content_img = file_get_contents($match, false, $context);
                      $fname = 'img/member/'.date('Y').'/'.date('m').'/'.str_replace('https://dl.airtable.com/', '', $match);
                      $wakil_image = $fname;
                      $s3->put($fname, $content_img);
                  }elseif($str == 'logo'){
                      $content_img = file_get_contents($match, false, $context);
                      $fname = 'img/region/'.date('Y').'/'.date('m').'/'.str_replace('https://dl.airtable.com/', '', $match);
                      $region->logo = $fname;
                      $s3->put($fname, $content_img);
                  }
              }
          }
          $bupati_password = str_random(6);
          $bupati = new App\Member;
          $bupati->firstname = $r_data->bupati;
          $bupati->lastname = '';
          $bupati->bio = '';
          $bupati->username = 'bupati-'.str_slug($r_data->kabupaten);
          $bupati->password = bcrypt($bupati_password);
          $bupati->pmalias = $bupati_password;
          $bupati->email = $bupati->username.'@alunalun.id';
          $bupati->address = '';
          $bupati->city = '';
          $bupati->region_id = $region->id;
          $bupati->zipcode = '';
          $bupati->phone = '';
          $bupati->whatsapp = '';
          $bupati->bbm = '';
          $bupati->image = $bupati_image;
          $bupati->save();

          $region->regent_id = $bupati->id;

          if($r_data->wakil_bupati){
            $wakil_bupati_password = str_random(6);
            $wakil_bupati = new App\Member;
            $wakil_bupati->firstname = $r_data->wakil_bupati;
            $wakil_bupati->lastname = '';
            $wakil_bupati->bio = '';
            $wakil_bupati->username = 'wbupati-'.str_slug($r_data->kabupaten);
            $wakil_bupati->password = bcrypt($wakil_bupati_password);
            $wakil_bupati->pmalias = $wakil_bupati_password;
            $wakil_bupati->email = $wakil_bupati->username.'@alunalun.id';
            $wakil_bupati->address = '';
            $wakil_bupati->city = '';
            $wakil_bupati->region_id = $region->id;
            $wakil_bupati->zipcode = '';
            $wakil_bupati->phone = '';
            $wakil_bupati->whatsapp = '';
            $wakil_bupati->bbm = '';
            $wakil_bupati->image = $wakil_image;
            $wakil_bupati->save();
            $region->viceregent_id = $wakil_bupati->id;
          }
        }

        $region->description = $r_data->deskripsi;
        $region->area_size = str_replace(",", ".", str_replace([".", " km2"], "", $r_data->luas_wilayah));
        

        preg_match('/^(.+) jiwa/', $r_data->populasi_total, $pop_r);
        if($pop_r){
            $region->population = str_replace(".", "", $pop_r[1]);
        }

        $region->save();
        //,'region-pariwisata','region-sda','region-kuliner'
        $pariwisata = App\Investment::where('slug', 'potensi-pariwisata')->first();
        if($pariwisata){
            $region->investments()->save($pariwisata, ['content' => $r_data->potensi_pariwisata, 'data' => '']);
        }
        $potensi_sda = App\Investment::where('slug', 'potensi-sda')->first();
        if($potensi_sda){
            $region->investments()->save($potensi_sda, ['content' => $r_data->potensi_sda, 'data' => '']);
        }
        $kuliner = App\Investment::where('slug', 'kuliner')->first();
        if($kuliner){
            $region->investments()->save($kuliner, ['content' => $r_data->kuliner_khas, 'data' => '']);
        }

        $exported[] = $region->id;
      }
    }

    return response('exported: ', implode(", ", $exported));    

        
        //$region->
});
