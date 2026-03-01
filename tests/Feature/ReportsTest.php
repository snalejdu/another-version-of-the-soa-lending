<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function authenticated_user_can_view_reports_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Monthly Transaction Report');
    }

    /** @test */
    public function filter_route_returns_transactions_without_totals()
    {
        $user = User::factory()->create();

        // create some transactions for the current month/year with known amounts
        $year = date('Y');
        $month = date('n');
        $dateString = sprintf('%04d-%02d-05', $year, $month);

        \App\Models\Transaction::factory()
            ->payment()
            ->create(['transaction_date' => $dateString, 'amount' => 1500]);
        \App\Models\Transaction::factory()
            ->disbursement()
            ->create(['transaction_date' => $dateString, 'amount' => 2500]);

        $response = $this->actingAs($user)->post(route('reports.generate'), [
            'year' => $year,
            'month' => $month,
            'action' => 'filter',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Transactions for');
        $response->assertDontSee('PDF saved to');

        // headers still visible
        $response->assertSee('Client Name');
        $response->assertSee('Account No');
        $response->assertSee('Disbursement');
        $response->assertSee('Payment');

        // totals should not be displayed on the page
        $response->assertDontSee('Totals');
        $response->assertDontSee('₱2,500.00');
        $response->assertDontSee('₱1,500.00');
    }

    /** @test */
    public function generate_route_creates_pdf_and_keeps_page_clean()
    {
        $user = User::factory()->create();

        $year = date('Y');
        $month = date('n');
        $dateString = sprintf('%04d-%02d-05', $year, $month);

        \App\Models\Transaction::factory()
            ->payment()
            ->create(['transaction_date' => $dateString, 'amount' => 1500]);
        \App\Models\Transaction::factory()
            ->disbursement()
            ->create(['transaction_date' => $dateString, 'amount' => 2500]);

        $response = $this->actingAs($user)->post(route('reports.generate'), [
            'year' => $year,
            'month' => $month,
            'action' => 'generate',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Transactions for');
        $response->assertSee('PDF saved to');

        // still no totals shown on the report page
        $response->assertDontSee('Totals');
        $response->assertDontSee('₱2,500.00');
        $response->assertDontSee('₱1,500.00');

        // ensure a PDF file was created in public/pdf/reports
        $publicDir = public_path('pdf/reports');
        $files = glob($publicDir . '/*.pdf');
        $this->assertCount(1, $files, 'Expected one PDF in public/pdf/reports');
        $this->assertStringEndsWith('.pdf', $files[0]);
    }
}
