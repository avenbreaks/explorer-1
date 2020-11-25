<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Facades\Rounds;
use App\Jobs\CachePastRoundPerformanceByPublicKey;
use App\Services\Monitor\Monitor;
use Illuminate\Console\Command;

final class horizon extends Command
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
        Rounds::allByRound(Monitor::roundNumber())
            ->each(fn ($round) => CachePastRoundPerformanceByPublicKey::dispatch($round->round, $round->public_key)->onQueue('performance'));
    }
}
