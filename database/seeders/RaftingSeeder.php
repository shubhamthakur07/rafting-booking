<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use App\Models\Testimonial;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class RaftingSeeder extends Seeder
{
    public function run(): void
    {
        // Create time slots
        $timeSlots = [
            ['start_time' => '08:00:00', 'end_time' => '10:00:00', 'max_people' => 20, 'price' => 50.00],
            ['start_time' => '10:00:00', 'end_time' => '12:00:00', 'max_people' => 20, 'price' => 50.00],
            ['start_time' => '12:00:00', 'end_time' => '14:00:00', 'max_people' => 20, 'price' => 55.00],
            ['start_time' => '14:00:00', 'end_time' => '16:00:00', 'max_people' => 20, 'price' => 50.00],
            ['start_time' => '16:00:00', 'end_time' => '18:00:00', 'max_people' => 20, 'price' => 45.00],
        ];

        foreach ($timeSlots as $slot) {
            TimeSlot::create($slot);
        }

        // Create sample testimonials
        $testimonials = [
            [
                'customer_name' => 'John Smith',
                'rating' => 5,
                'comment' => 'Amazing experience! The rapids were thrilling and the guides were professional and safety-conscious. Highly recommend!',
                'is_active' => true,
            ],
            [
                'customer_name' => 'Sarah Johnson',
                'rating' => 5,
                'comment' => 'Best river rafting experience ever! The scenery was beautiful and the whole team made us feel safe and excited.',
                'is_active' => true,
            ],
            [
                'customer_name' => 'Mike Davis',
                'rating' => 4,
                'comment' => 'Great adventure with friends. The morning trip was perfect - not too crowded and the water was just right.',
                'is_active' => true,
            ],
            [
                'customer_name' => 'Emily Chen',
                'rating' => 5,
                'comment' => 'Absolutely loved it! The instructors were knowledgeable and the equipment was top-notch. Will definitely come back!',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Create sample gallery images (placeholder URLs)
        $galleryImages = [
            ['title' => 'Rafting Adventure', 'image_path' => 'https://images.unsplash.com/photo-1530866495561-507c9faab2ed?w=800', 'category' => 'photos', 'sort_order' => 1],
            ['title' => 'Thrilling Rapids', 'image_path' => 'https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=800', 'category' => 'photos', 'sort_order' => 2],
            ['title' => 'Team Building', 'image_path' => 'https://images.unsplash.com/photo-1605130284535-11dd9eedc58a?w=800', 'category' => 'photos', 'sort_order' => 3],
            ['title' => 'Beautiful Scenery', 'image_path' => 'https://images.unsplash.com/photo-1465311530779-5241f5a29892?w=800', 'category' => 'photos', 'sort_order' => 4],
            ['title' => 'Adrenaline Rush', 'image_path' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800', 'category' => 'photos', 'sort_order' => 5],
            ['title' => 'Sunset Rafting', 'image_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800', 'category' => 'photos', 'sort_order' => 6],
            ['title' => 'River Rafting Highlights', 'image_path' => 'https://images.unsplash.com/photo-1530866495561-507c9faab2ed?w=800', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'category' => 'videos', 'sort_order' => 7],
        ];

        foreach ($galleryImages as $image) {
            GalleryImage::create($image);
        }
    }
}
