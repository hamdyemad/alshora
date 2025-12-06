<?php

namespace App\Traits;

use App\Models\Language;
use App\Models\Translation as TranslationModel;

trait Translation
{
  public function translations()
  {
      return $this->morphMany(TranslationModel::class, 'translatable');
  }

  public function getTranslation(string $key, string $locale)
  {
    $lang = Language::where('code', $locale)->first();
    if($lang) {
      return $this->translations()
          ->where('lang_id', $lang->id)
          ->where('lang_key', $key)
          ->first()
          ->lang_value ?? null;
    }
    return null;
  }

  public function setTranslation(string $key, string $locale, string $value)
  {
      $lang = Language::where('code', $locale)->first();

      if($lang) {
          $translation = $this->translations()
              ->firstOrNew(['lang_id' => $lang->id, 'lang_key' => $key]);
          $translation->lang_value = $value;
          $translation->save();
      }
  }
}
