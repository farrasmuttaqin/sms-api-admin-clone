<?php

namespace App\Http\Controllers\Billing;

use Auth;
use File;
use Log;
use App\Models\Billing\BillingProfile;
use App\Repositories\ApiUserRepositories;
use App\Repositories\BillingRepositories;
use App\Http\Controllers\Controller;
use App\Classes\ApiMessageFilterReport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Create a new FilterController instance.
     *
     * @return void
     */
    function __construct(ApiUserRepositories $apiUserRepo)
    {
        $this->middleware('auth');
        $this->apiUserRepo = $apiUserRepo;
        $this->log = Log::getLogger(get_class($this));
    }

    /**
     * Show the message filter index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all_users = $this->apiUserRepo->allJoinedData();

        $apiModel = new ApiMessageFilterReport();
        $all_filter_messages = $apiModel->getManifest();
        
        return view('billing.message_filter',['all_users'=>$all_users, 'all_filter_messages' => $all_filter_messages]);
    }

    /**
     * Process the message filter
     */
    public function process(Request $request)
    {
        $this->validate($request, $this->validationRulesForProcess());

        $input = $this->processRequestInput($request);
        
        /**
         * Start filtering
         */
        $msgContent = [];
        $inputFile = $request->file('added_file')->getClientOriginalName();
        $userAPI = $input['added_user'];
        
        /**
         * Check if billing report for input user is exist
         */
        $month = $input['added_month'];
        $year = date('Y');
        $apiModel = new ApiMessageFilterReport($month, $year, $userAPI);

        if (!$apiModel->isReportExist()) {
            return back()->withInput()->withErrors([trans('validation.failed_filter', ['name' => 'No Report Found for User '.$userAPI])]);
        } else {
            try {
                /**
                 * Insert Message Content File
                 */

                if (File::exists(public_path().'/'.$inputFile)){
                    File::delete(public_path().'/'.$inputFile);
                }

                $request->file('added_file')->move(public_path(),$inputFile);
    
                /**
                 * Read Message Content File
                 */

                $inputFile = public_path().'/'.$inputFile;
                $contentReaderType = IOFactory::identify($inputFile);

                /**  Create a new Reader of the type that has been identified  **/

                $contentReaderX = IOFactory::createReader($contentReaderType);
                
                /**  Load $inputFileName to a Spreadsheet Object  **/

                $contentReader = $contentReaderX->load($inputFile);
                $worksheet = $contentReader->getActiveSheet();
                $contentFilter = [];
                $departments = [];
                $highestRow = $worksheet->getHighestDataRow(); 
                $highestColumn = $worksheet->getHighestDataColumn(); 
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn); 
                
                if ($highestColumnIndex != 2){
                    /**
                     * Removing uploaded message filter file
                     */
                    if (File::exists($inputFile)){
                        File::delete($inputFile);
                    }
                    
                    return back()->withInput()->withErrors([trans('validation.failed_filter_inside')]);
                }

                /**
                 * Update manifest to add current file that being process
                 */
                $apiModel->updateManifest(false);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                        $contentRow = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                        if ($col == 2){
                            if (!in_array($contentRow, $departments)){
                                $departments[] = $contentRow;
                                $contentFilter[$contentRow][] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            }else{
                                $contentFilter[$contentRow][] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            }
                        }
                    }
                }    

                try{
                    $apiModelFilter = new ApiMessageFilterReport($month, $year,$userAPI, $contentFilter);
                    $apiModelFilter->generateReport();

                    /**
                     * Removing uploaded message filter file
                     */
                    if (File::exists($inputFile)){
                        File::delete($inputFile);
                    }

                    return redirect()->route('generate.sms.content.index')->with('alert-success', trans('validation.success_filter', ['name' => 'User '.$userAPI]));
                } catch (Throwable $e){
                    $log->error("Failed to generate Report" . $e);
                }
            } catch (Throwable $e) {
                $log->error("Failed to generate report ".$e);
            }

            /**
             * Removing uploaded message filter file
             */
            if (File::exists($inputFile)){
                File::delete($inputFile);
            }

            return back()->withInput()->withErrors([trans('validation.failed_filter', ['name' => 'No Report Found for User '.$userAPI])]);
        }
    }

    /**
     * Download filter message
     *
     */
    protected function download_filter_message($reportName)
    {
        $explode = explode('_',$reportName);

        $date = date_parse($explode[2]);
        $month = $date['month'];

        $year = $explode[3];

        $pathToFilteredMessage = public_path('archive/reports/'.$year.'/'.$month.'/MESSAGE_CONTENT_REPORT');

        $filteredMessage = $pathToFilteredMessage.'/'.$reportName.'.zip';

        if (file_exists($filteredMessage))
        {
            return response()->download($filteredMessage);
        }

        return back()->withInput()->withErrors([trans('validation.failed_download', ['name' => 'Filtered SMS, No Report Found for '.$reportName])]);
    }

    /**
     * Validation rules for process the message filter.
     *
     * @return array
     */
    protected function validationRulesForProcess()
    {
        return [
            'added_user' => ['required'],
            'added_file' => ['required'],
            'added_month' => ['required'],
        ];
    }

    /**
     * Process the input value from request
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInput(Request $request)
    {
        $input = $request->all();
        
        return $input;
    }

}
