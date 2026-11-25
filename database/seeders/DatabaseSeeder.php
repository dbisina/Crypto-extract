<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * All articles below are DEMONSTRATION CONTENT, not real reporting.
 */
class DatabaseSeeder extends Seeder
{
    private const IMG = [
        'lagos1' => '1569706971306-de5d78f6418e', 'lagos2' => '1744907895363-d351aa6019ef',
        'lagos3' => '1765475467677-579353b25ce0', 'lagos4' => '1793359868867-98af182411fc',
        'nairobi1' => '1794390884172-0f5b0951af01', 'nairobi2' => '1781504841876-0718e1aeb9de', 'nairobi3' => '1794390884071-a228d24889ca',
        'screens1' => '1651341050677-24dba59ce0fd', 'btc' => '1590283603385-17ffb3a7f29f', 'desk' => '1707761918029-1295034aa31e',
        'charts' => '1560221328-12fe60f83ab8', 'screens2' => '1651340981821-b519ad14da7c', 'fx' => '1612178991541-b48cc8e92a4d',
        'cape1' => '1525946159414-20188b278a0a', 'cape2' => '1639983220402-d8539e8d50a9', 'cape3' => '1679128991854-77953a560856',
        'accra1' => '1724428533592-df1c672429ea', 'accra2' => '1697049643603-b35346a3cb5b', 'accra3' => '1697049643520-cfa552d99de7',
        'gov1' => '1436450412740-6b988f486c6b', 'gov2' => '1505547828843-176834e42154', 'gov3' => '1523292562811-8fa7962a78c8',
        'mobile1' => '1608286022625-bc07f7a21154', 'mobile2' => '1599050751795-6cdaafbc2319', 'mobile3' => '1649486116188-b464d7f864a9',
    ];

    private static function img(string $k): string
    {
        return 'https://images.unsplash.com/photo-'.self::IMG[$k].'?auto=format&fit=crop&q=80&w=1600';
    }

    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@kryptoextract.test'], [
            'name' => 'KryptoExtract Admin', 'password' => Hash::make('password'), 'is_admin' => true,
        ]);

        $cats = [];
        foreach ([
            ['Bitcoin', 'Bitcoin, miners, ETFs and the macro forces behind the largest digital asset.'],
            ['Blockchain', 'Infrastructure, protocols and the engineering of public ledgers.'],
            ['Africa', 'Cryptocurrency, stablecoins and digital finance across the African continent.'],
            ['Regulation', 'Central banks, securities regulators and the law of digital assets.'],
            ['DeFi', 'Decentralised finance, lending, exchanges and on-chain liquidity.'],
            ['Stablecoins', 'Dollar tokens, local-currency coins and the new payment rails.'],
            ['Markets', 'Prices, flows, institutions and the structure of crypto markets.'],
            ['Press Releases', 'Company announcements published as supplied. Not independent reporting.'],
            ['Case Studies', 'Real-world deployments of digital asset technology.'],
            ['Insights', 'Opinion, analysis and long-form reporting from our contributors.'],
        ] as $i => [$name, $desc]) {
            $cats[Str::slug($name)] = Category::create(['name' => $name, 'slug' => Str::slug($name), 'description' => $desc, 'sort' => $i]);
        }

        $authors = [];
        foreach ([
            ['Adaeze Okonkwo', 'Africa Editor', 'Adaeze leads KryptoExtract\'s Africa desk from Lagos, covering stablecoin adoption, exchange policy and the intersection of fintech and regulation. She previously reported on banking for a pan-African business weekly.', '@adaeze_demo'],
            ['Kwame Mensah', 'Markets Correspondent', 'Kwame covers bitcoin, ether and institutional flows from Accra. He spent a decade in West African equities research before turning to digital assets.', '@kwame_demo'],
            ['Wanjiru Kamau', 'Regulation Reporter', 'Based in Nairobi, Wanjiru follows central banks, securities regulators and the drafting of virtual asset legislation across East Africa.', '@wanjiru_demo'],
            ['Thabo Nkosi', 'DeFi & Infrastructure Writer', 'Thabo writes about on-chain markets and protocol design from Johannesburg, with a background in software engineering and payments.', '@thabo_demo'],
            ['Amara Diallo', 'Senior Analyst, Insights', 'Amara produces long-form analysis and research for KryptoExtract Insights. She holds a master\'s degree in development economics.', '@amara_demo'],
        ] as [$n, $role, $bio, $tw]) {
            $authors[$n] = Author::create(['name' => $n, 'slug' => Str::slug($n), 'role' => $role, 'bio' => $bio, 'twitter' => $tw]);
        }
        $A = array_keys($authors);

        // [title, category, country, image, author index, excerpt, hours ago, flags, kind, tags]
        $rows = [
            // Featured
            ['Inside Nigeria\'s $59 billion stablecoin economy: how naira stress built a dollar-token habit', 'africa', 'Nigeria', 'lagos3', 0,
                'On Lagos\'s trading floors, market stalls and freelancer WhatsApp groups, dollar-pegged tokens have quietly become the working currency of a generation. We trace the flows, the fears and the regulators racing to catch up.',
                3, ['featured'], 'news', ['Nigeria', 'Stablecoins', 'Remittances']],
            // Latest (8)
            ['Bitcoin holds above key support as spot ETF inflows return after a two-week lull', 'markets', null, 'btc', 1,
                'Net creations across US-listed bitcoin funds turned positive, easing concerns that the autumn rally had run out of buyers.', 1, ['breaking'], 'news', ['Bitcoin', 'ETFs']],
            ['Kenya\'s Treasury publishes draft virtual asset service provider rules for public comment', 'regulation', 'Kenya', 'nairobi1', 2,
                'The draft sets out licensing tiers, capital requirements and custody standards, and would designate the Capital Markets Authority as lead supervisor.', 5, [], 'news', ['Kenya', 'Licensing']],
            ['Ethereum validators approve gas-limit increase as layer-2 fees fall to record lows', 'blockchain', null, 'screens1', 3,
                'A coordinated vote among node operators lifts block capacity, a move developers say should lower costs on rollups and mainnet alike.', 7, [], 'news', ['Ethereum', 'Layer 2']],
            ['Ghana\'s central bank signals phased framework for digital asset exchanges', 'africa', 'Ghana', 'accra1', 0,
                'Governor says a sandbox will open to licensed firms in the first quarter, with consumer-protection rules to follow.', 9, [], 'news', ['Ghana', 'Central banks']],
            ['South African court ruling clarifies how crypto gains are taxed for retail investors', 'regulation', 'South Africa', 'cape1', 2,
                'The judgment distinguishes between long-term holders and active traders, and is expected to shape SARS guidance in the new fiscal year.', 12, [], 'news', ['South Africa', 'Tax']],
            ['Solana network activity climbs as payment firms trial token-based settlement', 'blockchain', null, 'screens2', 3,
                'Developer data show a rise in stablecoin transfers, with two global card networks confirming limited pilots.', 15, [], 'news', ['Solana', 'Payments']],
            ['Rwanda\'s fintech regulator opens applications to tokenisation start-ups', 'africa', 'Rwanda', 'nairobi3', 0,
                'The National Bank of Rwanda will accept applications for its innovation sandbox from firms exploring tokenised bonds and trade finance.', 20, [], 'news', ['Rwanda', 'Tokenisation']],
            ['Lending protocol TVL rebounds as borrowing demand returns to stablecoin pools', 'defi', null, 'desk', 3,
                'Decentralised credit markets added liquidity for the third consecutive week as rates on dollar loans edged higher.', 26, [], 'news', ['Lending', 'DeFi']],
            // Africa (5 more beyond the ones above)
            ['Mobile-money giants and crypto exchanges are circling the same customers in East Africa', 'africa', 'Kenya', 'mobile1', 0,
                'A growing number of partnerships link wallets to on-ramps, raising questions about interoperability, fees and supervision.', 30, [], 'news', ['Kenya', 'Mobile money']],
            ['Why Ethiopian developers are building for a currency they can\'t buy', 'africa', 'Ethiopia', 'accra3', 4,
                'With tight capital controls, a community of builders is shipping stablecoin tools aimed at freelancers and the diaspora.', 40, [], 'news', ['Ethiopia', 'Developers']],
            ['Cape Town start-up raises seed round to settle cross-border payroll in stablecoins', 'africa', 'South Africa', 'cape3', 3,
                'The company says it will use the funding to expand corridors from South Africa to Nigeria, Ghana and Kenya.', 52, [], 'news', ['South Africa', 'Funding']],
            ['Remittance costs and the case for dollar tokens: Ghana\'s diaspora weighs the options', 'africa', 'Ghana', 'accra2', 1,
                'Senders say stablecoins cut fees by more than half, but recipients still struggle to convert to cedis at fair rates.', 60, [], 'news', ['Ghana', 'Remittances']],
            // Markets (4 incl. above)
            ['Institutional desks rotate into ether as options skew flips bullish', 'markets', null, 'charts', 1,
                'Derivatives data show demand for upside calls at multi-month highs, while basis trades remain subdued.', 34, [], 'news', ['Ethereum', 'Institutions']],
            ['Altcoin rally narrows: only a handful of tokens keep up with bitcoin', 'markets', null, 'screens2', 1,
                'Breadth indicators weaken even as headline indices rise, a pattern analysts associate with late-cycle momentum.', 44, [], 'news', ['Altcoins']],
            ['How African exchanges priced the latest volatility spike', 'markets', 'Nigeria', 'lagos1', 1,
                'Order-book data show naira pairs trading at wider premiums during the sell-off, a measure of local liquidity stress.', 70, [], 'news', ['Nigeria', 'Exchanges']],
            // Regulation (4 incl. above)
            ['Securities regulators in three African markets coordinate on token classification', 'regulation', null, 'gov1', 2,
                'A joint statement says regulators will align on when a token is a security, a move welcomed by exchanges and issuers.', 28, [], 'news', ['Regulators']],
            ['Nigeria\'s SEC outlines custody and disclosure rules for digital asset platforms', 'regulation', 'Nigeria', 'lagos2', 2,
                'New circular requires segregation of client assets and monthly proof-of-reserves attestations from licensed platforms.', 48, [], 'news', ['Nigeria', 'Custody']],
            ['Central banks weigh stablecoin reserve rules after run on a regional token', 'regulation', null, 'gov2', 2,
                'Officials say disclosure of reserve composition could become a licensing condition for dollar tokens operating in Africa.', 76, [], 'news', ['Stablecoins', 'Central banks']],
            // Stablecoins (3 incl. above)
            ['USDT and USDC: a field guide to who holds which, and why', 'stablecoins', null, 'fx', 3,
                'Tether dominates emerging-market trading while Circle\'s coin leads in regulated venues. Here is how the split plays out across Africa.', 36, [], 'news', ['USDT', 'USDC']],
            ['Local-currency stablecoins: can a naira or shilling token compete with the dollar?', 'stablecoins', 'Kenya', 'mobile2', 0,
                'Issuers argue that local tokens can unlock domestic payments, but liquidity remains the unresolved problem.', 66, [], 'news', ['Kenya', 'Naira']],
            // DeFi
            ['What the next lending-protocol exploit will probably look like', 'defi', null, 'screens1', 3,
                'Auditors say oracle design and governance-controlled parameters remain the most common failure points.', 80, [], 'news', ['Security', 'DeFi']],
            // Insights (3)
            ['Opinion: Africa does not need a crypto strategy, it needs a payments strategy', 'insights', null, 'accra1', 4,
                'The continent\'s policymakers keep asking how to regulate tokens. The more useful question is how to fix cross-border payments.', 18, [], 'insight', ['Opinion', 'Payments']],
            ['Analysis: the five numbers that explain African stablecoin demand', 'insights', null, 'cape2', 4,
                'From inflation differentials to remittance fees, a data-driven look at what actually pushes users toward dollar tokens.', 54, [], 'insight', ['Research', 'Stablecoins']],
            ['Long read: a year inside Lagos\'s peer-to-peer crypto economy', 'insights', 'Nigeria', 'lagos4', 0,
                'We spent twelve months with traders, merchants and regulators to understand an economy that officially barely exists.', 100, [], 'insight', ['Nigeria', 'Long read']],
            // Case studies, press releases, sponsored
            ['Case study: a Nairobi logistics firm settles supplier invoices on-chain', 'case-studies', 'Kenya', 'nairobi2', 3,
                'By paying regional suppliers in stablecoins, the company says it cut settlement from five days to under five minutes.', 86, [], 'news', ['Kenya', 'Case study']],
            ['Press release: Demo Exchange Africa announces new naira on-ramp [DEMONSTRATION]', 'press-releases', 'Nigeria', 'mobile3', 0,
                'This is a demonstration press release shown to illustrate how supplied company announcements are labelled and separated from reporting.', 22, ['press'], 'news', ['Press release']],
            ['Sponsored: Five questions to ask before choosing a custody provider [DEMONSTRATION]', 'insights', null, 'desk', 4,
                'Demonstration sponsored article from a fictional partner, shown to illustrate sponsored-content labelling.', 38, ['sponsored'], 'news', ['Sponsored']],
            // Guides (Learn)
            ['Guide: how self-custody works, and what can go wrong', 'blockchain', null, 'gov3', 3,
                'Seed phrases, hardware wallets and recovery plans explained for beginners, with plain-language risk warnings.', 200, [], 'guide', ['Guide', 'Wallets']],
            ['Guide: reading a stablecoin\'s reserve report', 'stablecoins', null, 'charts', 4,
                'What attestations tell you, what they leave out, and the red flags that experienced analysts look for.', 220, [], 'guide', ['Guide', 'Stablecoins']],
            ['Guide: what is a spot bitcoin ETF and how does it differ from owning bitcoin?', 'bitcoin', null, 'btc', 1,
                'A primer on creation and redemption, custody and fees, and why the structure matters for market flows.', 240, [], 'guide', ['Guide', 'Bitcoin', 'ETFs']],
        ];

        $tagCache = [];
        foreach ($rows as $i => [$title, $cat, $country, $img, $ai, $excerpt, $hours, $flags, $kind, $tags]) {
            $article = Article::create([
                'title' => $title,
                'slug' => Str::slug(Str::limit(preg_replace('/\[.*?\]/', '', $title), 70, '')),
                'excerpt' => $excerpt,
                'body' => $this->body($title, $cat, $country, $kind),
                'kind' => $kind,
                'image' => self::img($img),
                'image_alt' => 'Editorial photograph accompanying: '.Str::limit($title, 80),
                'image_caption' => 'Photograph via Unsplash. Demonstration imagery.',
                'author_id' => $authors[$A[$ai]]->id,
                'category_id' => $cats[$cat]->id,
                'country' => $country,
                'status' => 'published',
                'published_at' => now()->subHours($hours),
                'is_featured' => in_array('featured', $flags),
                'is_breaking' => in_array('breaking', $flags),
                'is_sponsored' => in_array('sponsored', $flags),
                'is_press_release' => in_array('press', $flags),
                'sponsor_name' => in_array('sponsored', $flags) ? 'Demo Custody Co.' : null,
                'sources' => "Company filings and public statements (demonstration)\nCentral bank and regulator publications (demonstration)\nOn-chain analytics dashboards (demonstration)",
                'is_demo' => true,
            ]);
            $article->reading_time = Article::readingTime($article->body);
            $article->save();

            $ids = [];
            foreach ($tags as $t) {
                $ids[] = ($tagCache[$t] ??= Tag::create(['name' => $t, 'slug' => Str::slug($t)]))->id;
            }
            $article->tags()->sync($ids);
        }

        foreach ([
            ['Demo: Header leaderboard', 'Demo Exchange Africa', 'header_leaderboard', 'Trade with local-currency pairs on Demo Exchange', 'https://example.com/'],
            ['Demo: Homepage banner', 'Demo Custody Co.', 'homepage_banner', 'Institutional-grade custody for African treasuries', 'https://example.com/'],
            ['Demo: Sidebar', 'Demo Payments', 'homepage_sidebar', 'Settle cross-border payroll in minutes', 'https://example.com/'],
            ['Demo: In-article', 'Demo Wallet', 'in_article', 'The self-custody wallet built for Africa', 'https://example.com/'],
            ['Demo: Between content', 'Demo Analytics', 'between_content', 'On-chain analytics for emerging markets', 'https://example.com/'],
            ['Demo: Footer', 'Demo Exchange Africa', 'footer', 'Your gateway to digital assets', 'https://example.com/'],
            ['Demo: Newsletter sponsor', 'Demo Custody Co.', 'newsletter_sponsor', 'Today\'s edition is brought to you by Demo Custody Co.', 'https://example.com/'],
        ] as [$name, $adv, $place, $headline, $url]) {
            Advertisement::create(['name' => $name, 'advertiser' => $adv, 'placement' => $place, 'headline' => $headline, 'url' => $url,
                'starts_at' => now()->subDays(7), 'ends_at' => now()->addDays(60), 'active' => true]);
        }

        Setting::put('site_tagline', 'Independent financial journalism on digital assets, with Africa at the centre.');
        Setting::put('contact_email', 'newsroom@kryptoextract.example');
    }

    private function body(string $title, string $cat, ?string $country, string $kind): string
    {
        $where = $country ?? 'the broader market';
        $topic = [
            'africa' => 'African digital-finance markets', 'markets' => 'crypto markets', 'regulation' => 'the regulatory landscape',
            'stablecoins' => 'the stablecoin sector', 'defi' => 'decentralised finance', 'insights' => 'the industry',
            'blockchain' => 'blockchain infrastructure', 'bitcoin' => 'the bitcoin market', 'case-studies' => 'enterprise adoption', 'press-releases' => 'company news',
        ][$cat] ?? 'digital finance';

        $h = '<p class="lede"><em>This article is demonstration content created to showcase the KryptoExtract design. It is not real reporting.</em></p>';
        $h .= "<p>Over the past year, developments in {$where} have drawn the attention of traders, policymakers and founders alike. The story is part of a larger shift in {$topic}, where adoption has outpaced the institutions designed to oversee it.</p>";
        $h .= "<p>Interviews with market participants, regulators and analysts point to a common theme: demand is practical, not speculative. Users want cheaper payments, a hedge against currency risk and access to markets that are otherwise difficult to reach. Those motives shape how products are built and how rules are written.</p>";
        $h .= "<h2>What the data show</h2>";
        $h .= "<p>Volumes on regional venues have grown even as global trading has fluctuated. Analysts caution that headline numbers can mislead, since a large share of activity is peer-to-peer and does not appear in exchange statistics.</p>";
        $h .= '<blockquote><p>The question is no longer whether people will use these tools. It is whether the rules and the infrastructure will be ready when they do.</p><cite>Industry analyst, demonstration quote</cite></blockquote>';
        $h .= "<h2>Why it matters</h2><ul><li>Cross-border payment costs remain among the highest in the world.</li><li>Local currencies have experienced sustained pressure against the dollar.</li><li>Regulators are moving from observation to formal licensing regimes.</li></ul>";
        $h .= "<p>Not everyone is convinced. Sceptics point to consumer-protection gaps, the risk of platform failures and the difficulty of enforcing rules across borders. Supporters counter that clear licensing can reduce harm by moving activity onto supervised venues.</p>";
        if ($kind === 'guide') {
            $h .= "<h2>Step by step</h2><ol><li>Understand the asset and the risks involved.</li><li>Choose a reputable platform or wallet and verify it independently.</li><li>Start small, keep records and secure your credentials.</li><li>Review local tax and regulatory obligations.</li></ol>";
        }
        $h .= "<h2>What to watch next</h2><p>Over the coming months, readers should watch for formal guidance from regulators, the launch of supervised sandboxes and changes in the fee structures of remittance corridors. KryptoExtract will continue to track the story.</p>";
        $h .= '<p class="note">KryptoExtract content is for information only and is not investment advice.</p>';

        return $h;
    }
}
