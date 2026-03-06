<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        // Intentionally empty
    }

    public function seedForCompany(int $companyId): void
    {
        $accounts = [
            // Assets (1000–1999)
            [
                'code' => 1000,
                'name' => 'Cash',
                'type' => 'Asset',
                'sub_type' => 'Current Asset',
                'description' => 'Cash on hand and in bank',
            ],
            [
                'code' => 1100,
                'name' => 'Accounts Receivable',
                'type' => 'Asset',
                'sub_type' => 'Current Asset',
                'description' => 'Money owed by customers',
            ],
            [
                'code' => 1500,
                'name' => 'Equipment',
                'type' => 'Asset',
                'sub_type' => 'Fixed Asset',
                'description' => 'Equipment and machinery',
            ],

            // Liabilities (2000–2999)
            [
                'code' => 2000,
                'name' => 'Accounts Payable',
                'type' => 'Liability',
                'sub_type' => 'Current Liability',
                'description' => 'Money owed to suppliers',
            ],
            [
                'code' => 2100,
                'name' => 'Credit Card',
                'type' => 'Liability',
                'sub_type' => 'Current Liability',
                'description' => 'Credit card payables',
            ],

            // Equity (3000–3999)
            [
                'code' => 3000,
                'name' => 'Owner Equity',
                'type' => 'Equity',
                'sub_type' => 'Equity',
                'description' => 'Owner investment',
            ],

            // Income (4000–4999)
            [
                'code' => 4000,
                'name' => 'Sales Revenue',
                'type' => 'Income',
                'sub_type' => 'Revenue',
                'description' => 'Revenue from sales',
            ],

            // Expenses (5000–9999)
            [
                'code' => 5000,
                'name' => 'Cost of Goods Sold',
                'type' => 'Expense',
                'sub_type' => 'Cost of Sales',
                'description' => 'Direct costs of sales',
            ],
            [
                'code' => 5100,
                'name' => 'Operating Expenses',
                'type' => 'Expense',
                'sub_type' => 'Operating Expense',
                'description' => 'General business expenses',
            ],
        ];

        foreach ($accounts as $account) {
            DB::table('chart_of_accounts')->updateOrInsert(
                [
                    'company_id' => $companyId,
                    'code' => $account['code'],
                ],
                [
                    'name' => $account['name'],
                    'type' => $account['type'],
                    'sub_type' => $account['sub_type'],
                    'description' => $account['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
