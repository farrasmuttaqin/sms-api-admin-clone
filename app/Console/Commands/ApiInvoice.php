<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Invoice\InvoiceBank;
use App\Models\Invoice\InvoiceProduct;
use App\Models\Invoice\InvoiceHistory;
use App\Models\Invoice\InvoiceSetting;
use App\Models\Invoice\InvoiceProfile;

use App\Repositories\HistoryRepositories;

use Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PDF;
use File;

class ApiInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api_invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api Invoice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HistoryRepositories $historyRepo)
    {
        parent::__construct();
        $this->historyRepo = $historyRepo;
        $this->logger = Log::getLogger(get_class($this));
    }

    /**
     * Generate invoice for each month
     *
     * @return void
     */
    public function handle()
    {
        /** Refresh and get profiles */
        $this->refreshInvoiceNumber();
        $profiles = $this->getProfiles();
        
        /** Get Setting */
        $setting = $this->getSetting();

        /** Initiate Invoice value with zero */
        $invoice = 0;

        if (empty($profiles)) {
            // Logger
            $this->logger->info('No Profile that should generate in this month or the invoices already generated for this month');
            echo "\033[1;31mNo Profiles\n\033[1;37m";
            // End Logger

            return;
        }
        
        echo "\n";
        echo "\033[1;32m-----------------------------[START GENERATE INVOICES]---------------------------'".PHP_EOL;
        
        foreach ($profiles as $profile) {
            // Logger
            echo "\033[1;34m$profile->COMPANY_NAME\t\t\t";
            // End Logger

            try {

                $invoice = $this->insertInvoice($setting, $profile);
                
                $this->createPDF($invoice);

                /** 
                 * Updating Owner Type
                 */

                $this->historyRepo->updateOwnerType($invoice);

                // Logger
                $this->logger->info('Success Invoice for '. $profile->companyName);
                echo "\033[1;32mSuccess\n\033[1;37m";
                // End Logger
            } catch (\Exception $e) {
                
                $this->deleteInvoiceFile($invoice);
                $this->refreshInvoiceNumber();

                // Logger
                $this->logger->error('Failed Invoice for '. $profile->COMPANY_NAME);
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
                echo "\033[1;31mFailed\n\033[1;37m";
                // End Logger
            }
        }
        
        echo "\033[1;32m-----------------------------[GENERATE INVOICES SUCCESS]---------------------------'".PHP_EOL;
        echo "\n";
        
        $this->refreshInvoiceNumber();
    }

    /**
     * Insert Invoice based on profile
     *
     * @param InvoiceSetting $settings
     * @param InvoiceProfile $profile
     * @return void
     */
    protected function insertInvoice(&$settings, &$profile)
    {
        $settings->LAST_INVOICE_NUMBER += 1;

        $startDate = date('Y-m-d');

        $year = substr($startDate, 0, 4);
        $month = substr($startDate, 5, 2);
        $name = str_replace(' ', '_', $profile->COMPANY_NAME);
        $monthName = date("F", mktime(0, 0, 0, $month, 10)); 

        $fileName = $year.'/'.$month.'/'.$settings->LAST_INVOICE_NUMBER.'_'.$name.'_'.$monthName.'_ORIGINAL_PREVIEW.pdf';

        $history = new InvoiceHistory;

        $history->INVOICE_PROFILE_ID    = $profile->INVOICE_PROFILE_ID;
        $history->INVOICE_NUMBER        = $settings->LAST_INVOICE_NUMBER;
        $history->START_DATE            = $startDate;
        $history->DUE_DATE              = date('Y-m-d', strtotime("{$settings->PAYMENT_PERIOD} days"));
        $history->REF_NUMBER            = '';
        $history->INVOICE_TYPE          = 'ORIGINAL';
        $history->STATUS                = 0;
        $history->FILE_NAME             = $fileName;
        
        $save = $history->save();

        return $history->INVOICE_HISTORY_ID;
    }
    
    /**
     * Function to delete invoice file.
     */
    protected function deleteInvoiceFile($HISTORY_ID = null)
    {
        $history=InvoiceHistory::where('INVOICE_HISTORY_ID',$HISTORY_ID)->delete();

        return (bool) $history;
    }

    /**
     * Get summary path
     *
     * @param int $period         Timestamps value from period
     * @param String $reportName  Name of Summary report
     * @return  String
     */
    protected function summaryPath($period, $reportName)
    {
        $date = date('Y-m-d', $period);
        $date = date('Y-m-d', strtotime($date. ' - 1 month'));

        $period = strtotime($date);

        $month = date('m', $period);
        $year = date('Y', $period);
        $reportDir = public_path('archive/reports/'. $year . '/' . $month . '/FINAL_STATUS');
        
        return $reportDir .'/'. $reportName . '_' . date('M_Y', $period) . '_Summary.xlsx';
    }
    
    /**
     * Function to create invoice PDF.
     */
    protected function createPDF($HISTORY_ID = null)
    {
        /**
         * Create PDF
         */

        $all_products = $this->historyRepo->invoiceDataProfile($HISTORY_ID);
        $profile = $this->historyRepo->profile($HISTORY_ID);
        $setting = $this->historyRepo->setting();

        $qtyArr = [];
        $unitPrice = [];
        $productName = [];
        $counterEachProduct = [];
        $productNamePrefix = [];
        $productDate = [];
        $counter = 0;

        foreach ($all_products as $product) {
            $sumProduct = 0;
            $counterEachProduct[$counter] = 0;

            if (!empty($product->REPORT_NAME && $product->QTY == 0 && $product->UNIT_PRICE == 0)){

                /**
                 * Read Summary of Excel File to get Various Product
                 */

                $period = strtotime($product->START_DATE);

                $reportPath = $this->summaryPath($period, $product->REPORT_NAME);
                
                if (file_exists($reportPath)) {
                    $spreadsheet = IOFactory::load($reportPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $row = 146;
                    while(!empty($sheet->getCell('A'.$row)->getValue())) {
                        $qtyArr[]      = (int)$sheet->getCell('B'.$row)->getValue();
                        $unitPrice[]   = (int)$sheet->getCell('D'.$row)->getValue();
                        $productName[] = $sheet->getCell('A'.$row)->getValue();
                        $productNamePrefix[] = $product->PRODUCT_NAME;

                        $date = date('Y-m-d', $period);
                        $date = date('Y-m-d', strtotime($date. ' - 1 month'));
                        
                        $productDate[] = strtotime($date);
                        $row++;
                        $sumProduct++;

                        $counterEachProduct[$counter] = $sumProduct;
                    }
                }
            }else if (!empty($product->REPORT_NAME && ($product->QTY != 0 || $product->UNIT_PRICE != 0))){
                /**
                 * Read Summary of Excel File to get Various Product
                 */

                $period = strtotime($product->START_DATE);

                $reportPath = $this->summaryPath($period, $product->REPORT_NAME);
                
                if (file_exists($reportPath)) {
                    $spreadsheet = IOFactory::load($reportPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $row = 146;
                    while(!empty($sheet->getCell('A'.$row)->getValue())) {
                        $qtyArr[]      = (int)$product->QTY;
                        $unitPrice[]   = (int)$product->UNIT_PRICE;
                        $productName[] = $sheet->getCell('A'.$row)->getValue();
                        $productNamePrefix[] = $product->PRODUCT_NAME;

                        $date = date('Y-m-d', $period);
                        $date = date('Y-m-d', strtotime($date. ' - 1 month'));
                        
                        $productDate[] = strtotime($date);
                        $row++;
                        $sumProduct++;

                        $counterEachProduct[$counter] = $sumProduct;
                    }
                }
            }
            $counter++;
        }

        $pdf_name = $profile->FILE_NAME;
        $year = explode('/',$pdf_name);
        $month = explode('/',$pdf_name);

        $file_year = $year[0];
        $file_month = $month[1];

        $path_archive = public_path().'/archive';

        if(!File::isDirectory($path_archive)){

            File::makeDirectory($path_archive, 0755, true, true);

        }

        $path_invoice = $path_archive.'/invoices';
        
        if(!File::isDirectory($path_invoice)){

            File::makeDirectory($path_invoice, 0755, true, true);

        }

        $path_year = $path_invoice.'/'.$file_year;
        
        if(!File::isDirectory($path_year)){

            File::makeDirectory($path_year, 0755, true, true);

        }

        $path_month = $path_year.'/'.$file_month;
        
        if(!File::isDirectory($path_month)){

            File::makeDirectory($path_month, 0755, true, true);

        }
        
        $path = public_path('/archive/invoices/'.$pdf_name);

        $pdf = PDF::loadView('invoice.invoice_file_full', ['productDate'=>$productDate, 'counterEachProduct'=>$counterEachProduct, 'productNamePrefix'=>$productNamePrefix, 'setting'=>$setting, 'profile'=>$profile, 'all_products'=>$all_products,'qtyArr'=>$qtyArr, 'unitPrice'=>$unitPrice, 'productName'=>$productName])->save($path);

        return $pdf;
    }

    /**
     * Refresh Invoice Number
     *
     * @return void
     */
    public function refreshInvoiceNumber()
    {
        $query = $this->getSetting();
        $data = [];
        
        if ($query){
            
            $maxHistoryNumber = InvoiceHistory::max('INVOICE_NUMBER');
            // $add = (int)$maxHistoryNumber + 1;
            
            if($maxHistoryNumber){
                InvoiceSetting::where('SETTING_ID',$query->SETTING_ID)
                        ->update([
                            'LAST_INVOICE_NUMBER' => $maxHistoryNumber
                        ]);
            }else{
                InvoiceSetting::where('SETTING_ID',$query->SETTING_ID)
                        ->update([
                            'LAST_INVOICE_NUMBER' => $query->INVOICE_NUMBER+1
                        ]);
            }

        }else{
            $data[] = [
                'PAYMENT_PERIOD' => '14',
                'AUTHORIZED_NAME' => 'Mona Eftarina',
                'AUTHORIZED_POSITION' => 'Finance & Accounting Manager',
                'NOTE_MESSAGE' => "Please quote the above invoice number reference on all payment orders and note that all associated charges for the financial transfer are at the payees expense.<br>Any errors/discrepancies must be reported to PT. FIRST WAP INTERNATIONAL (financial@1rstwap.com) in writing withing 7 (seven) days, otherwise claims for changes will not be accepted",
                'INVOICE_NUMBER_PREFIX' => '1rstwap - ',
                'LAST_INVOICE_NUMBER' => 1
            ];
            InvoiceSetting::insert($data);
        }

        return true;
    }

    /**
     * Get Profiles that will be generate
     * Profile data will include profile's products
     *
     * @return array
     */
    protected function getProfiles()
    {
        return InvoiceProfile::where('INVOICE_PROFILE.AUTO_GENERATE',1)
                                ->leftJoin('CLIENT', 'INVOICE_PROFILE.CLIENT_ID','=','CLIENT.CLIENT_ID')
                                ->leftJoin('INVOICE_BANK', 'INVOICE_PROFILE.INVOICE_BANK_ID','=','INVOICE_BANK.INVOICE_BANK_ID')
                                ->whereNotIn('INVOICE_PROFILE.INVOICE_PROFILE_ID', function($notIn){

                                    $firstDate = date('Y-m-d', strtotime('first day of this month'));

                                    $notIn->select('INVOICE_HISTORY.INVOICE_HISTORY_ID')
                                    ->from('INVOICE_HISTORY')
                                    ->where('START_DATE','>=',$firstDate);
                                })
                                ->orderBy('CLIENT.COMPANY_NAME','ASC')
                                ->get();
    }

    /**
     * Get invoice settings
     *
     * @return InvoiceSetting
     */
    protected function getSetting()
    {
        return InvoiceSetting::first();
    }
}
