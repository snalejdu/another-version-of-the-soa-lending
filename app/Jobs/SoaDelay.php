
<!--
namespace App\Jobs;

use App\Mail\SoaManagement;
use App\Models\Account;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SoaDelay implements ShouldQueue
{
    use Queueable;

    protected $accountId;

    /**
     * Create a new job instance.
     *
     * @param  int  $accountId
     */
    public function __construct(int $accountId)
    {
        $this->accountId = $accountId; // Store the account ID for later use
        Log::info("SoaDelay job initialized with Account ID: {$this->accountId}");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info("Started processing SoaDelay job for Account ID: {$this->accountId}");

        // Retrieve the Account by its ID
        $account = Account::find($this->accountId);

        if (!$account) {
            Log::error("Account not found: {$this->accountId}");
            return;
        }

        Log::info("Found Account for ID {$this->accountId}: " . json_encode($account));

        // Ensure the account has a customer with a valid email
        if (!$account->customer || !$account->customer->email) {
            Log::error("Account ID {$this->accountId} has no associated customer or valid email.");
            return;
        }

        // Get the customer's email from the account
        $email = $account->customer->email;

        Log::info("Sending SOA email to: {$email}");

        // Create the mail instance
        $mail = new SoaManagement($account);

        // Send the email (delay is already handled by the job queue)
        try {
            Mail::to($email)->send($mail);
            Log::info("SOA email successfully sent to {$email}");
        } catch (\Exception $e) {
            Log::error("Failed to send SOA email to {$email}: " . $e->getMessage());
        }
    }
} -->
