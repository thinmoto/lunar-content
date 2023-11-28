<?php

namespace Thinmoto\LunarContent\Traits;

use Lunar\Models\Language;

trait WithLanguages
{
    public $currentLanguageCode;

    /**
     * Getter for default language.
     *
     * @return \Lunar\Models\Language
     */
    public function getDefaultLanguageProperty()
    {
        return $this->languages->first(fn ($language) => $language->default);
    }

    /**
     * Getter for current language.
     *
     * @return \Lunar\Models\Language
     */
    public function getCurrentLanguageProperty()
    {
        if(!$this->currentLanguageCode)
            $this->currentLanguageCode = $this->defaultLanguage->code;

        foreach($this->languages as $language)
            if($language->code == $this->currentLanguageCode)
                return $language;

        return $this->defaultLanguage->code;
    }

    /**
     * Getter for all languages in the system.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLanguagesProperty()
    {
        return Language::get();
    }

    /**
     * @return mixed
     */
    public function setCurrentLanguage($language)
    {
        $this->currentLanguageCode = $language;
    }
}
