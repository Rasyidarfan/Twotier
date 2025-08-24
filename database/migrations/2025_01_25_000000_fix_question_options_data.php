<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing question options data that are stored as escaped JSON strings
        DB::table('questions')->get()->each(function ($question) {
            $updated = false;
            $updateData = [];

            // Fix tier1_options if it's a string
            if (is_string($question->tier1_options)) {
                $tier1_decoded = json_decode($question->tier1_options, true);
                if ($tier1_decoded && is_array($tier1_decoded)) {
                    // Re-encode without escaping Unicode characters
                    $updateData['tier1_options'] = json_encode($tier1_decoded, JSON_UNESCAPED_UNICODE);
                    $updated = true;
                } else {
                    // If JSON decode fails, try to split by comma as fallback
                    $tier1_array = explode(',', trim($question->tier1_options, '"'));
                    if (count($tier1_array) > 1) {
                        $updateData['tier1_options'] = json_encode(array_map('trim', $tier1_array), JSON_UNESCAPED_UNICODE);
                        $updated = true;
                    }
                }
            }

            // Fix tier2_options if it's a string
            if (is_string($question->tier2_options)) {
                $tier2_decoded = json_decode($question->tier2_options, true);
                if ($tier2_decoded && is_array($tier2_decoded)) {
                    // Re-encode without escaping Unicode characters
                    $updateData['tier2_options'] = json_encode($tier2_decoded, JSON_UNESCAPED_UNICODE);
                    $updated = true;
                } else {
                    // If JSON decode fails, try to split by comma as fallback
                    $tier2_array = explode(',', trim($question->tier2_options, '"'));
                    if (count($tier2_array) > 1) {
                        $updateData['tier2_options'] = json_encode(array_map('trim', $tier2_array), JSON_UNESCAPED_UNICODE);
                        $updated = true;
                    }
                }
            }

            // Update the record if any changes were made
            if ($updated) {
                DB::table('questions')
                    ->where('id', $question->id)
                    ->update($updateData);
                
                echo "Fixed question ID {$question->id}\n";
            }
        });

        echo "Question options data migration completed.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as we're fixing data format
        // The original escaped format was incorrect anyway
        echo "This migration cannot be reversed - data format was corrected.\n";
    }
};
