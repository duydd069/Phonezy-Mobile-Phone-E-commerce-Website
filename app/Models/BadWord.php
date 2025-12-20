<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadWord extends Model
{
    protected $table = 'bad_words';
    protected $fillable = ['word'];
    
    /**
     * Check if text contains any bad words
     * 
     * @param string $text
     * @return bool
     */
    public static function containsBadWord(string $text): bool
    {
        $badWords = self::pluck('word')->toArray();
        
        if (empty($badWords)) {
            return false;
        }
        
        $text = mb_strtolower($text, 'UTF-8');
        
        foreach ($badWords as $badWord) {
            $badWord = mb_strtolower($badWord, 'UTF-8');
            
            // Check if bad word exists as whole word or part of text
            if (mb_strpos($text, $badWord) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get the specific bad word found in text
     * 
     * @param string $text
     * @return string|null
     */
    public static function findBadWord(string $text): ?string
    {
        $badWords = self::pluck('word')->toArray();
        
        if (empty($badWords)) {
            return null;
        }
        
        $text = mb_strtolower($text, 'UTF-8');
        
        foreach ($badWords as $badWord) {
            $badWordLower = mb_strtolower($badWord, 'UTF-8');
            
            if (mb_strpos($text, $badWordLower) !== false) {
                return $badWord;
            }
        }
        
        return null;
    }
}
