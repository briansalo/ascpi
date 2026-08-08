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
                    'message' => "Nganung gamay mani imong score? Mao na kay sige man og chat anang seaman. haahah bawi rata next quiz love",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "😂 Ayaw kaulaw sa imong score ron. Quiz ra ni, dili pa ang ASCPi.",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "imong score ron kay same sa imoha kay cute hahahaha",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "✨ Ayaw surrender ha. Ganahan pa ko makakita sa imong next score. bawi rata 😊",
                    'image_url' => null,
                    'video_url' => null
                ],
                [
                    'message' => "Gamay lagi imong score ron? Hmm, basin kulang kag lambing ni Brian? 😆",
                    'image_url' => null,
                    'video_url' => null
                ],
            ],

            'medium' => [
                [
                    'message' => "kulangan pako sa imong score ron, ta mangaon nalang ta, dedto nalang ta bawi sa kaon hahaha",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "💙 Goods ra imong score ron. Ako kaha? Goods ra sa imoha? 😆",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😏 Focus lang sa review... ako na bahala mo-cheer nimo.",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "🤭 Murag inspired lagi ka ron. Tungod nako noh? 😆",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😂 Dili man perfect imong score ron, pero pwede na pang-flex sa akong heart. 😜",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "hmm not bad ang score ron. pero murag feel nako need ko nimo ron aron next quiz kay dako na og score hahah. 😜",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "guto ka mo dako pa imong score sunod? tara date sata para pang inspire haha",
                    'image_url' => null,
                    'video_url' => null,
                ],
            
            ],

            'high' => [
                [
                    'message' => "dakoa sa score ron oy😍. dahil dyan deserve mong e date. tara? hahaha",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "👑 Ahh, syaro og dili mahimong topnotcher sa ASCPi exam.",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😆 Ka-bright gyod? Basin nagkodigo ka diha? Basin ga-search ka sa Google?",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "💙 Ahh, kiligon man sab ta ani oy. Ka-bright ba ani oy, bigyan ng jacket!",
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "😉 Dakoa gyod nimog score ron? hahaha tara e celebrate nato ne mangaon ta sa gawas hahaha",
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
                    'image_url' => null,
                    'video_url' => null,
                ],
                [
                    'message' => "dako lagi kag score ron? pa impress ka sako noh? hahahah ",
                    'image_url' => null,
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