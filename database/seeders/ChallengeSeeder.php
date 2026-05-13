<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\ChallengeVersion;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = [

            // ==========================
            // WEB EXPLOITATION (CATEGORY 1)
            // ==========================
            [
                'title' => 'Basic SQL Injection',
                'category_id' => 1,
                'difficulty' => 'easy',
                'points' => 50,
                'description' => 'Exploit a basic SQL injection vulnerability to bypass login.',
                'flag' => 'flag{web_sql_basic}',
            ],
            [
                'title' => 'Cookie Tampering',
                'category_id' => 1,
                'difficulty' => 'medium',
                'points' => 125,
                'description' => 'Modify client-side cookies to escalate privileges.',
                'flag' => 'flag{cookie_monster}',
            ],
            [
                'title' => 'XSS Payload Forge',
                'category_id' => 1,
                'difficulty' => 'hard',
                'points' => 250,
                'description' => 'Craft a JavaScript payload to steal a session token.',
                'flag' => 'flag{xss_steal_king}',
            ],

            // ==========================
            // CRYPTOGRAPHY (CATEGORY 2)
            // ==========================
            [
                'title' => 'XOR Baby Cipher',
                'category_id' => 2,
                'difficulty' => 'easy',
                'points' => 50,
                'description' => 'Decode a string encrypted with a repeating XOR key.',
                'flag' => 'flag{crypto_xor}',
            ],
            [
                'title' => 'Broken Caesar',
                'category_id' => 2,
                'difficulty' => 'medium',
                'points' => 120,
                'description' => 'Reverse a Caesar cipher with a twist.',
                'flag' => 'flag{crypto_caesar_shift}',
            ],
            [
                'title' => 'RSA Went Wrong',
                'category_id' => 2,
                'difficulty' => 'hard',
                'points' => 300,
                'description' => 'Factor weak RSA keys to recover a message.',
                'flag' => 'flag{crypto_rsa_cracked}',
            ],

            // ==========================
            // FORENSICS (CATEGORY 3)
            // ==========================
            [
                'title' => 'Hidden PNG',
                'category_id' => 3,
                'difficulty' => 'easy',
                'points' => 40,
                'description' => 'Recover an image hidden inside another file.',
                'flag' => 'flag{forensics_png_hidden}',
            ],
            [
                'title' => 'Memory Dump Dive',
                'category_id' => 3,
                'difficulty' => 'medium',
                'points' => 150,
                'description' => 'Analyze a memory dump and extract credentials.',
                'flag' => 'flag{forensics_memory}',
            ],
            [
                'title' => 'Carve the Drive',
                'category_id' => 3,
                'difficulty' => 'hard',
                'points' => 275,
                'description' => 'Carve deleted files from a disk image.',
                'flag' => 'flag{forensics_drive_carved}',
            ],

            // ==========================
            // REVERSE ENGINEERING (CATEGORY 4)
            // ==========================
            [
                'title' => 'String Decoder',
                'category_id' => 4,
                'difficulty' => 'easy',
                'points' => 60,
                'description' => 'Reverse a binary that obfuscates its output.',
                'flag' => 'flag{rev_decoded}',
            ],
            [
                'title' => 'Unlock the Check',
                'category_id' => 4,
                'difficulty' => 'medium',
                'points' => 160,
                'description' => 'Patch a binary to bypass authentication logic.',
                'flag' => 'flag{rev_patch_check}',
            ],
            [
                'title' => 'Deobfuscate Hell',
                'category_id' => 4,
                'difficulty' => 'hard',
                'points' => 320,
                'description' => 'Reverse a heavily obfuscated binary.',
                'flag' => 'flag{rev_obfus_master}',
            ],

            // ==========================
            // PWN (CATEGORY 5)
            // ==========================
            [
                'title' => 'Stack Baby',
                'category_id' => 5,
                'difficulty' => 'easy',
                'points' => 80,
                'description' => 'Use a simple buffer overflow to redirect execution.',
                'flag' => 'flag{pwn_stack_easy}',
            ],
            [
                'title' => 'Format String Attack',
                'category_id' => 5,
                'difficulty' => 'medium',
                'points' => 175,
                'description' => 'Exploit a format string vulnerability.',
                'flag' => 'flag{pwn_format_attack}',
            ],
            [
                'title' => 'Ret2libc Mastery',
                'category_id' => 5,
                'difficulty' => 'hard',
                'points' => 350,
                'description' => 'Defeat ASLR using a return-to-libc payload.',
                'flag' => 'flag{pwn_ret2libc_master}',
            ],

            // ==========================
            // OSINT (CATEGORY 6)
            // ==========================
            [
                'title' => 'Find the Username',
                'category_id' => 6,
                'difficulty' => 'easy',
                'points' => 30,
                'description' => 'Track down a username using public techniques.',
                'flag' => 'flag{osint_username_found}',
            ],
            [
                'title' => 'Location Hunt',
                'category_id' => 6,
                'difficulty' => 'medium',
                'points' => 110,
                'description' => 'Use images and metadata to identify a location.',
                'flag' => 'flag{osint_geo_detective}',
            ],
            [
                'title' => 'Deep OSINT Profile',
                'category_id' => 6,
                'difficulty' => 'hard',
                'points' => 260,
                'description' => 'Cross-source analyze multiple datasets.',
                'flag' => 'flag{osint_deep_profile}',
            ],
        ];

        foreach ($challenges as $c) {
            $chal = Challenge::factory()->create([
                'title' => $c['title'],
            ]);
            ChallengeVersion::factory()
                ->for($chal)
                ->create([
                    'challenge_category_id' => $c['category_id'],
                    'difficulty' => $c['difficulty'],
                    'points' => $c['points'],
                    'description_md' => $c['description'],
                    'flag' => $c['flag'],
                    'tags' => strtolower(\App\Models\ChallengeCategory::find($c['category_id'])->name).' challenge',
                ]);
        }

    }
}
