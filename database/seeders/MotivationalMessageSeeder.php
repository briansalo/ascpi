<?php

namespace Database\Seeders;

use App\Models\MotivationalMessage;
use Illuminate\Database\Seeder;

class MotivationalMessageSeeder extends Seeder
{
    public function run(): void
    {
        $motivationalMessages = [
            'low' => [
                [
                    'message' => "Nganung gamay mani imong score? Mao na kay sige man og chat anang seaman.",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "😂 Ayaw kaulaw sa imong score ron. Quiz ra ni, dili pa ang ASCPi.",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "cute lagi imong score ron? murag kani sila kay cute couple",
                    'image_url' => 'test.com',
                    'video_url' => null
                ],
                [
                    'message' => "✨ Ayaw surrender ha. Ganahan pa ko makakita sa imong next score. 😊",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "Gamay lagi imong score ron? Hmm, basin kulang kag lambing ni Brian? 😆",
                    'image_url' => '/images/motivational/kulang-lambing.jpg',
                    'video_url' => null
                ],
            ],

            'medium' => [
                [
                    'message' => "😏 Kulangan pa ko sa score. Focus sa sunod, dili sigeg bega sa seaman.",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😂 Not bad ang score. Dili man need perfect score, ikaw lang sapat na. Ayieh, hahaha.",
                    'image_url' => '/images/motivational/ikaw-lang-sapat.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "💙 Goods ra imong score ron. Ako kaha? Goods ra sa imoha? 😆",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😏 Focus lang sa review... ako na bahala mo-cheer nimo.",
                    'image_url' => '/images/motivational/cheer-you.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "🤭 Murag inspired lagi ka ron. Tungod nako noh? 😆",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😂 Dili man perfect, pero pwede na pang-flex sa akong heart. 😜",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "focus ra kaayo ka sa ascpi exam oy tanawa sa ne oh pang pa wala sa stress 😜",
                    'image_url' => 'test.com',
                    'video_url' => null,
                ],
                [
                    'message' => "tanawa sa ni oh para sunod quiz kay dako nakag score 😜",
                    'image_url' => null,
                    'video_url' => 'test.com',
                ],
            ],

            'high' => [
                [
                    'message' => "😍 Galinga gyod sa akong love-love oy, hahaha. 😂",
                    'image_url' => '/images/motivational/love-love.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "👑 Ahh, syaro og dili mahimong topnotcher sa ASCPi exam.",
                    'image_url' => '/images/motivational/topnotcher.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "😆 Ka-bright gyod? Basin nagkodigo ka diha? Basin ga-search ka sa Google?",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "💙 Ahh, kiligon man sab ta ani oy. Ka-bright ba ani oy, bigyan ng jacket!",
                    'image_url' => '/images/motivational/bigyan-jacket.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "😉 Dakoa gyod nimog score. Basic ra ang mga question? Hahaha.",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "🏆 Hoy, murag nagpa-cute man ka pinaagi sa score. 😂",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "👑 Top scorer na lagi ni. Ako na lang kulang para mahimong top couple. 😆",
                    'image_url' => '/images/motivational/top-couple.jpg',
                    'video_url' => null,
                ],
                [
                    'message' => "Imong score kay perfect kaayo, murag kani sila perfect hahaha",
                    'image_url' => '/images/motivational/proud-of-you.jpg',
                    'video_url' => null,
                ],
            ],
        ];

        foreach ($motivationalMessages as $scoreLevel => $items) {
            foreach ($items as $item) {
                MotivationalMessage::updateOrCreate(
                    [
                        'score_level' => $scoreLevel,
                        'message' => $item['message'],
                        'video_url' => $item['video_url'],
                        'image_url' => $item['image_url'],
                        'is_displayed' => false,
                    ]
                );
            }
        }

    }
}