<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\DonationCreateRequest;
use App\Jobs\Files\OptimizeImage;
use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Validation\ValidationException;

class DonorTransactionController extends Controller
{
    public function create()
    {
        $this->authorize('create', new Transaction);

        $partners = $this->getAvailablePartners(['donatur']);
        $bankAccounts = BankAccount::where('is_active', BankAccount::STATUS_ACTIVE)->pluck('name', 'id');
        $genders = [
            'm' => __('app.gender_male'),
            'f' => __('app.gender_female'),
        ];
        $books = Book::orderBy('name')
            ->where('status_id', Book::STATUS_ACTIVE)
            ->pluck('name', 'id');

        return view('donors.transactions.create', compact('partners', 'bankAccounts', 'genders', 'books'));
    }

    public function store(DonationCreateRequest $request)
    {
        $payload = $request->validated();
        $partner = Partner::find($payload['partner_id']);
        $partnerName = $partner ? $partner->name : $payload['partner_name'];
        $transactionDescription = $this->buildTransactionDescription($partnerName, $payload['notes']);

        if (!$partner) {
            $partner = Partner::create([
                'name' => $payload['partner_name'],
                'phone' => $payload['partner_phone'],
                'gender_code' => $payload['partner_gender_code'],
                'type_code' => ['donatur'],
                'creator_id' => auth()->id(),
            ]);
        }

        $newTransaction = [
            'date' => $payload['date'],
            'amount' => $payload['amount'],
            'description' => $transactionDescription,
            'partner_id' => $partner->id,
            'book_id' => $payload['book_id'],
            'bank_account_id' => $payload['bank_account_id'],
            'in_out' => Transaction::TYPE_INCOME,
            'creator_id' => auth()->id(),
        ];
        $transaction = Transaction::create($newTransaction);

        if (isset($payload['files'])) {
            $filePath = 'files/'.now()->format('Y/m/d');
            foreach ($payload['files'] as $uploadedFile) {
                $fileName = $uploadedFile->store($filePath);
                $file = $transaction->files()->create([
                    'type_code' => 'raw_image',
                    'file_path' => $fileName,
                ]);
                dispatch(new OptimizeImage($file));
            }
        }

        flash(__('transaction.income_added'), 'success');

        if ($referencePage = $request->get('reference_page')) {
            if ($referencePage == 'donor') {
                return redirect()->route('donors.show', [$partner->id]);
            }
        }

        return redirect()->route('donors.index');
    }

    private function getAvailablePartners(array $partnerTypeCodes): array
    {
        $partners = Partner::where('is_active', Partner::STATUS_ACTIVE)
            ->whereJsonContains('type_code', 'donatur')
            ->orderBy('name')
            ->get();

        return $partners->pluck('name_phone', 'id')->toArray();
    }

    private function buildTransactionDescription(string $partnerName, ?string $donationNotes): string
    {
        $transactionDescription = __('donor.donation_from', ['donor_name' => $partnerName]);
        if ($donationNotes) {
            $transactionDescription .= '|'.$donationNotes;
        }

        if (strlen($transactionDescription) > 255) {
            $descriptionPrefix = str_replace('|'.$donationNotes, '', $transactionDescription);
            $maxNotesLength = (255 - strlen($descriptionPrefix));
            throw ValidationException::withMessages([
                'notes' => [__('validation.donor.notes.max', ['max' => $maxNotesLength])],
            ]);
        }

        return $transactionDescription;
    }
}
