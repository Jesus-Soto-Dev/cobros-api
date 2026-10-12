<?php

namespace App\Console\Commands;

use App\Enums\DebtStatus;
use App\Models\Debt;
use Illuminate\Console\Command;

class MarkOverdueDebts extends Command
{
    protected $signature = 'debts:mark-overdue';

    protected $description = 'Marca como vencidas las deudas pendientes cuya fecha de vencimiento ya pasó.';

    public function handle(): int
    {
        $count = Debt::query()
            ->where('status', DebtStatus::Pending->value)
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => DebtStatus::Overdue->value]);

        $this->info("Se marcaron {$count} deudas como vencidas.");

        return self::SUCCESS;
    }
}
