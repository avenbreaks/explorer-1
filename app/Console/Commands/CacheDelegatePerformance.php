<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Facades\Rounds;
use Performance\Performance;
use Illuminate\Console\Command;
use App\Services\Monitor\Monitor;
use App\Jobs\CachePastRoundPerformanceByPublicKey;

final class CacheDelegatePerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'explorer:cache-delegate-performance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache the past performance for each active delegate in the current round.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Performance::point();

        dd(Rounds::allByRound(Monitor::roundNumber()));

        Rounds::allByRound(Monitor::roundNumber())
            ->each(fn ($round) => CachePastRoundPerformanceByPublicKey::dispatch($round->round, $round->public_key)->onQueue('performance'));

        return Performance::results()->toJson();
    }
}
