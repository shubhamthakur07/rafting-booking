<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, change the column to VARCHAR to allow any value
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status VARCHAR(20)");

        // Update any existing 'checked_in' status to 'pending'
        DB::statement("UPDATE bookings SET booking_status = 'pending' WHERE booking_status = 'checked_in'");

        // Then change the enum values for booking_status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // First, change the column to VARCHAR to allow any value
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status VARCHAR(20)");

        // Revert 'pending' back to 'checked_in'
        DB::statement("UPDATE bookings SET booking_status = 'checked_in' WHERE booking_status = 'pending'");

        // Revert back to original enum values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_status ENUM('confirmed', 'checked_in', 'completed', 'cancelled') DEFAULT 'confirmed'");
    }
};
