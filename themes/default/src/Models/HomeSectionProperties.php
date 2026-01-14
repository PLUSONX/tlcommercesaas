<?php

namespace Theme\Default\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSectionProperties extends Model
{
    protected $connection = 'mysql'; // <--- force SaaS DB
    protected $fillable = ['section_id', 'key_name'];

    protected $table = "tl_theme_default_home_page_sections_properties";

}
