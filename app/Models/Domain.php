<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function domainName () {
        return $this->name;
    }

    public function domainScore () {
        return $this->score;
    }


    public function pages() {
       return  $this->hasMany(Page::class);
    }

    public function removeCascade () {

        $this->removePagesCascade();
        $this->delete();
    }

    public function removePagesCascade() {

        $reletedPages = $this->pages()->get();
        if (count($reletedPages) > 0) {
            foreach ($reletedPages as $page) {
                $page->delete();
            }

        }
    }
}
