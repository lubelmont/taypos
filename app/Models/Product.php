<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	use HasFactory;
	

	protected $fillable = ['name','barcode','cost','price','stock','alerts','image','category_id'];


	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function ventas()
	{
		return $this->hasMany(SaleDetail::class);
	}


	public function getImagenAttribute()
	{	
		if($this->image != null)
			return (file_exists('storage/products/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}

	public function getPriceAttribute($value)
	{
		//comma por punto
		//return str_replace('.', ',', $value);
		// punto por coma
		return str_replace(',', '.', $value);
	}
	

	public function setPriceAttribute($value)
    {
        $this->attributes['price'] =  preg_replace('/[\$,]/', '', $value);
    }
    
    public function setCostAttribute($value)
    {
        $this->attributes['cost'] =  preg_replace('/[\$,]/', '', $value);
    }



	


}
