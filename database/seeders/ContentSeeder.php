<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => '5 Ways to Improve Your Home Wi-Fi',
                'category' => 'Internet Tips',
                'excerpt' => 'Simple, practical steps to get better coverage and faster speeds at home.',
                'body' => "A weak Wi-Fi signal is one of the most common causes of a poor internet experience. Here are five practical ways to improve it:\n\n1. Reposition your router - place it centrally and elevated, away from walls and metal objects.\n2. Reduce interference - keep the router away from microwaves, cordless phones and baby monitors.\n3. Use the right band - connect speed-sensitive devices to 5GHz and distant devices to 2.4GHz.\n4. Update firmware - keep your router's firmware current for performance and security fixes.\n5. Add an access point - for larger homes, a mesh system or access point removes dead zones.",
            ],
            [
                'title' => 'How to Protect Your Wi-Fi Network',
                'category' => 'Cybersecurity',
                'excerpt' => 'Your Wi-Fi is the door to your devices and data. Keep it locked.',
                'body' => "Securing your Wi-Fi protects your devices and personal data.\n\n- Change the default router admin password.\n- Use WPA2 or WPA3 encryption with a strong passphrase.\n- Turn off WPS and remote management unless needed.\n- Create a separate guest network for visitors.\n- Keep firmware updated and review connected devices regularly.",
            ],
            [
                'title' => 'Understanding Internet Speed: Mbps vs MB/s',
                'category' => 'Technology',
                'excerpt' => 'Why your 20 Mbps connection does not download at 20 MB per second.',
                'body' => "Internet speeds are advertised in megabits per second (Mbps), while downloads are usually shown in megabytes per second (MB/s). One byte equals eight bits, so 20 Mbps is roughly 2.5 MB/s in ideal conditions. Real-world speeds are lower due to network overhead, congestion and Wi-Fi conditions. Understanding this difference helps you choose the right package and set realistic expectations.",
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                $post + [
                    'author' => 'INET Solutions',
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 20)),
                    'meta_description' => $post['excerpt'],
                ]
            );
        }
    }
}
