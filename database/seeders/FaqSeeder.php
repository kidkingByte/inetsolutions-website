<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['What areas does INET cover?', 'INET provides internet services in selected areas. Customers can contact INET or use the coverage checker to confirm availability at their location.'],
            ['How do I subscribe?', 'Choose an available package and submit a connection request. Our team will contact you for the next steps.'],
            ['How can I pay for my internet?', 'Customers can pay using the payment channels supported by INET.'],
            ['How do I check my internet usage?', 'Customers can monitor their account and usage through the iNet customer platform or mobile application.'],
            ['What should I do if my internet stops working?', 'Check your router and network connection first. If the problem continues, contact our support team or submit a support ticket.'],
            ['Can businesses get customized packages?', 'Yes. Business and enterprise customers can contact our team for customized connectivity solutions.'],
            ['How can I contact customer support?', 'Customers can contact INET through phone, WhatsApp, email or the online support system.'],
        ];

        foreach ($faqs as $i => [$question, $answer]) {
            Faq::updateOrCreate(['question' => $question], [
                'answer' => $answer,
                'category' => 'General',
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }
}
