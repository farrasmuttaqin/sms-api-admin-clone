<?php

namespace App\Http\Controllers\Invoice;

use Auth;
use App\Models\Invoice\InvoiceHistory;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepositories;
use App\Repositories\BankRepositories;
use App\Repositories\ProfileRepositories;
use App\Repositories\SettingRepositories;
use App\Repositories\HistoryRepositories;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PDF;
use File;
use ZipArchive;

use Illuminate\Http\Request;

class InvoiceHistoryController extends Controller
{
    /**
     * Create a new InvoiceHistoryController instance.
     *
     * @return void
     */
    function __construct(SettingRepositories $settingRepo, ClientRepositories $clientRepo, BankRepositories $bankRepo, ProfileRepositories $profileRepo, HistoryRepositories $historyRepo)
    {
        $this->middleware('auth');
        $this->clientRepo = $clientRepo;
        $this->bankRepo = $bankRepo;
        $this->profileRepo = $profileRepo;
        $this->settingRepo = $settingRepo;
        $this->historyRepo = $historyRepo;

    }

    /**
     * Show the application dashboard, start default with invoice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_clients = $this->profileRepo->allJoinedDataClient();
        $all_banks = $this->bankRepo->data();
        $all_invoice_profiles = $this->profileRepo->allJoinedData();
        $setting = $this->settingRepo->data();
        $all_history = $this->historyRepo->data();

        return view('invoice.index',['all_history'=>$all_history, 'setting'=>$setting,'all_invoice_profiles'=>$all_invoice_profiles, 'all_clients'=> $all_clients,'all_banks'=> $all_banks]);
    }

    /**
     * Show invoice history detail
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_history($PROFILE_ID)
    {
        $profile = $this->profileRepo->joinedProfile($PROFILE_ID);
        $users = $this->profileRepo->users($profile->CLIENT_ID);
        $all_history = $this->historyRepo->dataByProfile($PROFILE_ID);
        $setting = $this->settingRepo->data();

        return view('invoice.index_history',['setting'=>$setting,'users'=>$users, 'profile'=> $profile, 'all_history'=>$all_history]);
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
     * Show the invoice edit page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index_edit($HISTORY_ID)
    {
        $groups = $this->profileRepo->groups();
        $reports = $this->profileRepo->reports();

        $all_products = $this->historyRepo->invoiceDataHistory($HISTORY_ID);
        
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
        
        return view('invoice.invoice_file',['reports'=>$reports, 'groups'=>$groups, 'productDate'=>$productDate, 'counterEachProduct'=>$counterEachProduct, 'productNamePrefix'=>$productNamePrefix, 'setting'=>$setting, 'profile'=>$profile, 'all_products'=>$all_products,'qtyArr'=>$qtyArr, 'unitPrice'=>$unitPrice, 'productName'=>$productName]);
    }

    /**
     * Create invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRulesForCreate());
        
        $input = $this->processRequestInput($request);

        $saved = $this->historyRepo->save($input);

        /**
         * Create PDF
         */

        $HISTORY_ID = $saved;

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

        /** 
         * Updating Owner Type
         */

        $this->historyRepo->updateOwnerType($HISTORY_ID);
        
        return $saved ? 
                redirect()->route('invoice.index.edit', ['HISTORY_ID' => $saved])->with('alert-success', trans('validation.success_save', ['name' => 'Invoice History'])) 
                : back()->withInput()->withErrors([trans('validation.failed_save', ['name' => 'Invoice History'])]);
    }

    /**
     * Update invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $this->processRequestInput($request);

        $HISTORY_ID = $input['edited_invoice_history_id'];

        $historyData = InvoiceHistory::where('INVOICE_HISTORY_ID',$HISTORY_ID)->first();

        if ($historyData->INVOICE_NUMBER == $input['edited_invoice_number']){
            $this->validate($request, $this->validationRulesForUpdate());
        }else{
            $this->validate($request, $this->validationRulesForUpdateWithNumber($input["edited_invoice_number"]));
        }
        
        $updated = $this->historyRepo->update($input);

        return $updated ? 
                redirect()->route('invoice.index.edit', ['HISTORY_ID' => $HISTORY_ID])->with('alert-success', trans('validation.success_update', ['name' => 'Invoice History'])) 
                : back()->withInput()->withErrors([trans('validation.failed_update', ['name' => 'Invoice History'])]);
    }

    /**
     * Lock invoice.
     *
     * lock the invoice
     */
    public function lock($HISTORY_ID)
    {
        $invoiceHistory = InvoiceHistory::where("INVOICE_HISTORY_ID",$HISTORY_ID)->first();

        $lock = $this->historyRepo->lock($HISTORY_ID);

        return $lock ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_lock', ['name' => 'Invoice History with Number '.$invoiceHistory["INVOICE_NUMBER"]])) 
                : back()->withInput()->withErrors([trans('validation.failed_lock', ['name' => 'Invoice History'])]);
    }

    /**
     * Remove the history.
     *
     * @param  integer $HISTORY_ID
     * @return \Illuminate\Http\Response
     */
    public function destroy($HISTORY_ID)
    {
        $invoiceHistory = InvoiceHistory::where("INVOICE_HISTORY_ID",$HISTORY_ID)->first();

        /**
         * delete pdf
         */

        $path = public_path('/archive/invoices/'.$invoiceHistory->FILE_NAME);

        File::delete($path);

        $deleted = $this->historyRepo->delete($HISTORY_ID);

        return $deleted ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_delete', ['name' => 'Invoice History with Number '.$invoiceHistory["INVOICE_NUMBER"]])) 
                : back()->withInput()->withErrors([trans('validation.failed_delete', ['name' => 'Invoice History'])]);
    }


    /**
     * Preview invoice
     *
     * @param $HISTORY_ID
     * @return preview
     */
    protected function preview($HISTORY_ID)
    {
        $history = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $path = public_path('/archive/invoices/'.$history->FILE_NAME);

        File::delete($path);

        /**
         * Create Refreshed Invoice
         */

        $this->createPDFInside($HISTORY_ID,$path);

        return response()->file($path);
    }

    /**
     * Download invoice
     *
     * @param $HISTORY_ID
     * @return preview
     */
    protected function download($HISTORY_ID)
    {
        $history = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $fileName = explode('/',$history->FILE_NAME);

        $name = $fileName[2];

        /**
         * Create Refreshed Invoice
         */

        $all_products = $this->historyRepo->invoiceDataHistory($HISTORY_ID);
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

        $pdf = PDF::loadView('invoice.invoice_file_full', ['productDate'=>$productDate, 'counterEachProduct'=>$counterEachProduct, 'productNamePrefix'=>$productNamePrefix, 'setting'=>$setting, 'profile'=>$profile, 'all_products'=>$all_products,'qtyArr'=>$qtyArr, 'unitPrice'=>$unitPrice, 'productName'=>$productName]);

        return $pdf->download($name);
    }

    /**
     * Copy invoice.
     *
     * HISTORY_ID
     */
    public function copy($HISTORY_ID)
    {
        $history = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $copy = $this->historyRepo->copy($HISTORY_ID);

        /**
         * Create PDF
         */

        $copied = $this->createPDF($HISTORY_ID);
        
        return $copied ? 
                redirect()->route('invoice')->with('alert-success', trans('validation.success_save_copied', ['name' => 'Invoice History with Number '.$history["INVOICE_NUMBER"]])) 
                : back()->withInput()->withErrors([trans('validation.failed_save_copied', ['name' => 'Invoice History'])]);
    }

    /**
     * Revise invoice.
     *
     * HISTORY_ID
     */
    public function revise($HISTORY_ID)
    {
        $history = InvoiceHistory::where('INVOICE_HISTORY_ID', $HISTORY_ID)->first();

        $revise = $this->historyRepo->revise($HISTORY_ID);

        /**
         * Create PDF
         */

        $revised = $this->createPDF($HISTORY_ID);
        
        return $revised ? 
                redirect()->route('invoice.index.edit', ['HISTORY_ID' => $revise])->with('alert-success', trans('validation.success_save_revised', ['name' => 'Invoice History with Number '.$history["INVOICE_NUMBER"]])) 
                : back()->withInput()->withErrors([trans('validation.failed_save_revised', ['name' => 'Invoice History'])]);
    }

    /**
     * Download all invoices
     *
     */
    protected function download_all_invoices(Request $request)
    {
        $this->validate($request, $this->validationRulesForDownloadAllInvoices());

        $input = $this->processRequestInput($request);

        $month = $input["added_invoice_month"];

        $year = date('Y');

        $zip = new ZipArchive;

        $path_invoices = public_path('/archive/invoices/'.$year.'/'.$month); 

        $path_invoices_without_month = public_path('/archive/invoices/'.$year); 

        $fileName = 'Invoices_'.date('F', mktime(0, 0, 0, $month, 10)).'_'.$year.'.zip';

        if (File::isDirectory($path_invoices)){
            
            if ($zip->open($path_invoices_without_month.'/'.$fileName, ZipArchive::CREATE) === TRUE && !file_exists($path_invoices_without_month.'/'.$fileName))
            {
                $files = File::files($path_invoices);
                
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                
                $zip->close();
            }
        
            return response()->download($path_invoices_without_month.'/'.$fileName);

        }else{

            return back()->withInput()->withErrors([trans('validation.failed_download_all_invoices', ['name' => 'All Invoices, No Invoices Found'])]);

        }
    }

    /**
     * Function to create invoice PDF
     */
    public function createPDF($HISTORY_ID = null)
    {
        /**
         * Create PDF
         */

        $all_products = $this->historyRepo->invoiceDataHistory($HISTORY_ID);
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
     * Process to Create Inside PDF
     */

    public function createPDFInside($HISTORY_ID = null,$path = null)
    {
        $all_products = $this->historyRepo->invoiceDataHistory($HISTORY_ID);
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

        $pdf = PDF::loadView('invoice.invoice_file_full', ['productDate'=>$productDate, 'counterEachProduct'=>$counterEachProduct, 'productNamePrefix'=>$productNamePrefix, 'setting'=>$setting, 'profile'=>$profile, 'all_products'=>$all_products,'qtyArr'=>$qtyArr, 'unitPrice'=>$unitPrice, 'productName'=>$productName])->save($path);
    }

    /**
     * Process the input value from request before store to database
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInput(Request $request)
    {
        $input = $request->all();
        
        return $input;
    }

    /**
     * Validation rules for create invoice.
     *
     * @return array
     */
    protected function validationRulesForCreate()
    {
        return [
            'added_invoice_date' => ['required','date_format:Y-m-d'],
            'added_invoice_number' => ['required','unique:'.env('DB_SMS_API_V2').'.INVOICE_HISTORY,INVOICE_NUMBER'],
            'added_term_of_payment' => ['required'],
            'added_due_date' => ['required','date_format:Y-m-d'],
        ];
    }

    /**
     * Validation rules for update invoice with number.
     *
     * @return array
     */
    protected function validationRulesForUpdateWithNumber($number)
    {
        return [
            'edited_invoice_date' => ['required','date_format:Y-m-d'],
            'edited_invoice_number' => ['required','unique:'.env('DB_SMS_API_V2').'.INVOICE_HISTORY,INVOICE_NUMBER,'.(int)$number.',INVOICE_HISTORY_ID'],
            'edited_term_of_payment' => ['required'],
            'edited_due_date' => ['required','date_format:Y-m-d'],
        ];
    }

    /**
     * Validation rules for update invoice without number.
     *
     * @return array
     */
    protected function validationRulesForUpdate()
    {
        return [
            'edited_invoice_date' => ['required','date_format:Y-m-d'],
            'edited_invoice_number' => ['required'],
            'edited_term_of_payment' => ['required'],
            'edited_due_date' => ['required','date_format:Y-m-d'],
        ];
    }

    /**
     * Validation rules for download all invoices.
     *
     * @return array
     */
    protected function validationRulesForDownloadAllInvoices()
    {
        return [
            'added_invoice_month' => ['required'],
        ];
    }
    

}
