<?php

namespace App\Services;

use App\Expense;
use App\Income;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Report
{
    /**
     * @var Collection
     */
    private $incomes;
    /**
     * @var string
     */
    private $folder;
    /**
     * @var string
     */
    private $filename;

    /**
     * InvoiceReport constructor.
     * @param Collection $incomes
     */
    public function __construct(Collection $incomes)
    {
        $this->incomes = $incomes;
        $this->folder = md5(time());
        Storage::disk('local')->createDir($this->folder);
    }

    /**
     * @throws \Exception
     */
    public function generateInvoices(): void
    {
        /** @var Income $income */
        foreach ($this->incomes as $income) {
            $file = $this->folder . '/invoices/' . $income->invoice_no . '.pdf';
            Storage::disk('local')->put(
                $file,
                $income->generateInvoice()->filename($income->invoice_no)->stream()
            );
        }
    }

    public function gatherExpenses(Collection $expenses): void
    {
        /** @var Expense $expens */
        foreach ($expenses as $expens) {
            $file = $expens->getFirstMediaPath('file');

            Storage::disk('local')->put(
                $this->folder . '/expenses/' . $expens->id . '.pdf',
                file_get_contents($file)
            );
        }
    }

    public function download(): BinaryFileResponse
    {
        return response()->download($this->filename);
    }

    public function zipInvoices(): void
    {
        $zip = new \ZipArchive();
        $this->filename = 'invoices.zip';
        $zip->open($this->filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addEmptyDir('invoices');
        $zip->addEmptyDir('expenses');

        $this->addFiles(glob(Storage::disk('local')->path($this->folder) . '/invoices/*.pdf'), $zip, 'invoices');
        $this->addFiles(glob(Storage::disk('local')->path($this->folder) . '/expenses/*.pdf'), $zip, 'expenses');

        $zip->close();
    }

    /**
     * @param bool|array $folder
     * @param \ZipArchive $zip
     * @return void
     */
    public function addFiles($folder, \ZipArchive $zip, $folderToAdd): void
    {
        foreach ($folder as $item) {
            $zip->addFile($item, $folderToAdd . '/' . basename($item));
        }
    }

    public function deleteFolder(): void
    {
        Storage::drive('local')->deleteDirectory($this->folder);
    }

}
