<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Block;
use App\Models\Scopes\OrderByBlockAmountScope;
use App\Models\Scopes\OrderByBlockFeeScope;
use App\Models\Scopes\OrderByGeneratorPublicKeyScope;
use App\Models\Scopes\OrderByHeightScope;
use App\Models\Scopes\OrderByIdScope;
use App\Models\Scopes\OrderByTimestampScope;
use App\Models\Scopes\OrderByTransactionsAmountScope;
use App\ViewModels\ViewModelFactory;
use ARKEcosystem\UserInterface\Http\Livewire\Concerns\HasPagination;
use Illuminate\View\View;
use Livewire\Component;

final class WalletBlockTable extends Component
{
    use HasPagination;

    public string $publicKey;

    public string $username;

    public array $state = [
        'blocksOrdering'          => 'height',
        'blocksOrderingDirection' => 'desc',
    ];

    /** @phpstan-ignore-next-line */
    protected $listeners = [
        'orderBlocksBy',
    ];

    public function orderBlocksBy(string $value): void
    {
        $this->state['blocksOrdering'] = $value;

        $this->state['blocksOrderingDirection'] = $this->state['blocksOrderingDirection'] === 'desc' ? 'asc' : 'desc';

        $this->gotoPage(1);
    }

    public function mount(string $publicKey, string $username): void
    {
        $this->publicKey = $publicKey;
        $this->username  = $username;
    }

    public function render(): View
    {
        /** @phpstan-ignore-next-line */
        $query = Block::where('generator_public_key', $this->publicKey)->scoped($this->getOrderingScope(), $this->state['blocksOrderingDirection']);

        return view('livewire.block-table', [
            'blocks' => ViewModelFactory::paginate($query->paginate()),
        ]);
    }

    private function getOrderingScope(): string
    {
        $scopes = [
            'id'           => OrderByIdScope::class,
            'timestamp'    => OrderByTimestampScope::class,
            'generated_by' => OrderByGeneratorPublicKeyScope::class,
            'height'       => OrderByHeightScope::class,
            'transactions' => OrderByTransactionsAmountScope::class,
            'amount'       => OrderByBlockAmountScope::class,
            'fee'          => OrderByBlockFeeScope::class,
        ];

        return $scopes[$this->state['blocksOrdering']];
    }
}
