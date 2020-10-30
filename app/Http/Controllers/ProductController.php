<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use Image;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;

class ProductController extends Controller
{
    
    public function viewProducts(Request $request)
    {
        $products = Product::get();
        $products = json_decode(json_encode($products));
        foreach($products as $key => $val){
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        return view('admin.products.view_products')->with(compact('products'));
    }
    
    
    
    public function addProduct(Request $request)
    {
       if($request->isMethod('post')){
           $data = $request->all();
           if(empty($data['category_id'])){
            return redirect()->back()->withInput()->with('flash_message_error','Under Category is missing!');

           }
           $product = new Product;
           $product->category_id = $data['category_id'];
           $product->product_name = $data['product_name'];
           $product->product_code = $data['product_code'];
           $product->product_color = $data['product_color'];
           if(!empty($data['description'])){
            $product->description = $data['description'];
           }else{
              $product->description = '';
           }
           
           $product->price = $data['price'];
           
           if($request->hasFile('image')){
               $image_tmp = $request->file('image');
               if($image_tmp->isValid()){
                   $extension = $image_tmp->getClientOriginalExtension();
                   $filename = rand(111,99999).'.'.$extension;
                   $large_image_path = 'images/backend_images/products/large/'.$filename;
                   $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                   $small_image_path = 'images/backend_images/products/small/'.$filename;
                   $thumb_image_path = 'images/backend_images/products/thumb/'.$filename;
                   
                   Image::make($image_tmp)->save($large_image_path);
                   Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                   Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                   Image::make($image_tmp)->resize(70,70)->save($thumb_image_path);

                   $product->image = $filename;
               }
           }

           $product->save();
           return redirect('/admin/view-products')->with('flash_message_success','Product Added Successfully');
       }
        
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='' ".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value= '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        return view('admin.products.add_products')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id=null)
    {
        
        if($request->isMethod('post')){
            $data = $request->all();


            if($request->hasFile('image')){
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    $thumb_image_path = 'images/backend_images/products/thumb/'.$filename;
                    
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    Image::make($image_tmp)->resize(70,70)->save($thumb_image_path);

                }
            }else{
                $filename = $data['current_image'];
            }
 
               if(empty($data['description'])){
                   $data['description'] = '';
               }
            
                Product::where(['id'=>$id])->update(
                ['category_id'=>$data['category_id'],
                'product_name'=>$data['product_name'],
                'product_code'=>$data['product_code'],
                'product_color'=>$data['product_color'],
                'description'=>$data['description'],
                'price'=>$data['price'],
                'image'=>$filename
                ]);
              return redirect()->route('viewProducts')->with('flash_message_success','Product Details Have Been Updated');
            }
    
        
        $productDetails = Product::where(['id'=>$id])->first();

        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            if($cat->id==$productDetails->category_id){
                $selected = "selected";
            }else {
                $selected = "";
            }
            $categories_dropdown .= "<option value='' ".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat) {
                if($sub_cat->id==$productDetails->category_id){
                    $selected = "selected";
                }else {
                    $selected = "";
                }
               
                $categories_dropdown .= "<option value= '".$sub_cat->id."' ".$selected.">&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown'));
    }

   
    public function deleteProduct($id=null)
    {
         Product::where(['id'=>$id])->delete();
         return redirect()->back()->with('flash_message_success','Product has been deleted successfully!');
    }
   
    public function deleteProductImage($id = null)
   {

    Product::where(['id'=>$id])->update(['image'=>'']);
    return redirect()->back()->with('flash_message_success','Product Image Has Been Deleted');

   }


   public function addAttributes(Request $request, $id=null){
    $productDetails = Product::with('attributes')->where(['id' => $id])->first();
    if($request->isMethod('post')){
        $data = $request->all();
        // echo "<pre>"; print_r($data);die;

        foreach($data['sku'] as $key => $val){
            if(!empty($val)){
                $attribute = new ProductsAttribute;
                $attribute->product_id = $id;
                $attribute->sku = $val;
                $attribute->size = $data['size'][$key];
                $attribute->price = $data['price'][$key];
                $attribute->stock = $data['stock'][$key];
                $attribute->save();
            }
        }
        return redirect('admin/add-attributes/'.$id)->with('flash_message_success','Product Attributes Added Successfully!');
    }
    return view('admin.products.add_attributes')->with(compact('productDetails'));
}
   
}
