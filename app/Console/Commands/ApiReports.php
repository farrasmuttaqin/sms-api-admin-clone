<?php

namespace App\Console\Commands;

require_once dirname(dirname(__DIR__)).'/Configs/config.php';

use Illuminate\Console\Command;
use Log;
use DB;
use File;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Box\Spout\Common\Type;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;



class ApiReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api Report';

    /**
     * Type of allowd Billing Profiles
     */
    const   BILLING_OPERATOR_BASE           = 'OPERATOR';
    const   BILLING_FIXED_BASE              = 'FIXED';
    const   BILLING_TIERING_BASE            = 'TIERING';
    const   BILLING_TIERING_OPERATOR_BASE            = 'TIERING-OPERATOR';


    /**
     * SMS Status for Query where clause
     */
    const   SMS_STATUS_CHARGED              = 'STATUS_CHARGED';
    const   SMS_STATUS_UNCHARGED            = 'STATUS_UNCHARGED';

    /**
     * SMS Status which displayed to Report's
     */
    const   SMS_STATUS_DELIVERED            = 'DELIVERED';
    const   SMS_STATUS_UNDELIVERED_CHARGED  = 'UNDELIVERED (CHARGED)';
    const   SMS_STATUS_UNDELIVERED          = 'UNDELIVERED (UNCHARGED)';
    const   SMS_STATUS_UNDEFINED            = 'UNDELIVERED (CHARGED)';

    const   OPERATOR_INDONESIA              = ['1RSTWAP',  'AXIS', 'CDMA_ID','CERIA','ESIA','EXCELCOM', 'HEPI','IM3',  'LIPPO', 'MOBILE_8','PSN',
                                               'SATELINDO','SMART','STARONE','TELKOMMOBILE','TELKOMSEL','TELKOM_FLEXI','THREE'];


    /**
     * SMS Legth by for every type
     */
    const   GSM_7BIT_SINGLE_SMS             = 160;
    const   GSM_7BIT_MULTIPLE_SMS           = 153;
    const   UNICODE_SINGLE_SMS              = 70;
    const   UNICODE_MULTIPLE_SMS            = 67;


    /**
     * Default value for undefined operator
     */
    const   DEFAULT_OPERATOR                = 'DEFAULT';
    const   LEGEND_SUMMARY_TITLE            = 'Legend';


    /**
     * Cache file name
     */
    const   CACHE_BILLING_PROFILE           = 'billingProfiles.lfu',
            CACHE_REPORT_GROUP              = 'reportGroups.lfu',
            ALL_REPORT_PACKAGE              = 'BILLING_REPORT';


    /**
     * Query mode
     */
    const   QUERY_ALL                       = '',
            QUERY_SINGLE_ROW                = 'SINGLE_ROW',
            QUERY_SINGLE_COLUMN             = 'SINGLE_COLUMN',
            QUERY_SINGLE_ROW_AND_COLUMN     = 'SINGLE_ROW_AND_COLUMN';


    /**
     * Directory and file prefix and suffix
     */
    const   DIR_FINAL_REPORT                = 'FINAL_STATUS',
            DIR_AWAITING_REPORT             = 'INCLUDE_AWAITING_DR',
            SUFFIX_FINAL_REPORT             = '',
            SUFFIX_AWAITING_REPORT          = '_Include_Awaiting_Dr',
            SUFFIX_SUMMARY_FINAL_REPORT     = '_Summary',
            SUFFIX_SUMMARY_AWAITING_REPORT  = '_Include_Awaiting_Dr_Summary';


    const   DETAILED_REPORT_HEADER          = ['MESSAGE ID', 'DESTINATION', 'MESSAGE CONTENT', 'ERROR CODE','OPERATOR COUNTRY CODE','OPERATOR ID', 'DESCRIPTION CODE', 'RECEIVE_DATETIME', 'SEND DATETIME', 'SENDER', 'USER ID', 'MESSAGE COUNT', 'OPERATOR', 'SINGLE PRICE','TOTAL PRICE'],
            DETAILED_MESSAGE_FORMAT         = ['MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT', 'ERROR_CODE','OPERATOR COUNTRY CODE','OPERATOR ID', 'DESCRIPTION_CODE', 'RECEIVE_DATETIME', 'SEND_DATETIME', 'SENDER', 'USER_ID', 'MESSAGE_COUNT', 'OPERATOR', 'SINGLE PRICE','TOTAL PRICE'];

    /**
     * Regular Expression to find a character except GSM 7Bit
     * The messages will set to latin if it only have character are defined on regex
     */
    const   GSM_7BIT_CHARS                  = '~[^A-Za-z0-9 \r\n¤@£$¥èéùìòÇØøÅå\x{0394}_\x{5C}\x{03A6}\x{0393}\x{039B}\x{03A9}\x{03A0}\x{03A8}\x{03A3}\x{0398}\x{039E}ÆæßÉ!\"#$%&\'\(\)*+,\-.\/:;<=>;?¡ÄÖÑÜ§¿äöñüà^{}\[\~\]\|\]~u';

    /**
     * Private properties
     * @var     PDO     $db                 Database connection handler
     */
    public $db,
            $reportDir,
            $finalReportWriter,
            $finalReportSummary,

            $currentMonth,
            $month,
            $year,
            $firstDateOfMonth,
            $lastDateOfMonth,
            $lastFinalStatusDate,

            $unchargedDeliveryStatus,
            $periodSuffix
            ;

    public $messageOperatorDistinct = [], $messagePriceDistinct = [], $messageTotalQuantityCharged = [], $messageTotalQuantityUncharged = [], $messageSinglePrice = [];

    /**
     * Public properties
     *
     * @var     Logger  $log                Log handler
     * @var     Array   $queryHistory       History of SQL syntax, total records and execution time
     * @var     Array   $deliveryStatus     Delivery status list for which splitted by CHARGED and UNCHARGED SMS
     */
    public  $log,
            $counter,
            $queryHistory,
            $deliveryStatus,
            $operator;

    /**
     * server timezone
     *
     * @var type String
     */
    public  $timezoneServer = "+0"; //db

    /**
     * server timezone
     *
     * @var type String
     */
    public  $timezoneClient = "+7"; //sms api admin

    /**
     * Api Report constructor
     */
    public function __construct($year = null, $month = null, $generateMode = false)
    {
        parent::__construct();

        $this->log               = Log::getLogger(get_class($this));

        $this->currentMonth      = date('Ym') === $year.$month;
        $this->year              = sprintf('%02d', $year  ?: date('Y'));
        $this->month             = sprintf('%02d', $month ?: date('m'));
        $this->reportDir         = SMSAPIADMIN_ARCHIEVE_EXCEL_REPORT.$year.'/'.$month;

        $this->prepareReportDir();
        $this->configureBillingPeriod();

        $this->periodSuffix      = '_'.date('M_Y', strtotime($year.'-'.$month));
        $this->queryHistory      = [];
        $this->counter           = ['charged' => 0, 'uncharged'];

        $this->deliveryStatus    = $this->getDeliveryStatus();
    }

    /**
     * Configure billing period
     * -------------------------------------
     * calculate
     * first date of the month
     * and last date of the month
     * and last send date
     *
     * @return void
     */
    private function configureBillingPeriod()
    {
        $currentMonth       = (int) date('m');
        $reportDate         = $this->year.'-'.$this->month.'-01 00:00:00';
        $this->firstDateOfMonth    = $this->serverTimeZone(strtotime($reportDate.' -1 second'));
        $this->lastDateOfMonth     = $this->serverTimeZone(date('Y-m-01 00:00:00', strtotime($reportDate.' first day of next month')));
        $this->lastFinalStatusDate = $this->lastDateOfMonth;
    }

    /**
     * Prepare report directory
     * this function will check report directory and trying to create the directory if not exist
     */
    private function prepareReportDir() {
        if (!@is_dir($this->reportDir)) {
            $this->log->info('Create Report directory "'.$this->reportDir.'"');
            if(!@mkdir($this->reportDir, 0777, TRUE)){
                $this->log->error('Could not create Report directory "'.$this->reportDir.'", please check the permission.');
                $this->log->info ('Cancel generate Report.');
            }
        }

        if(!file_exists(BILLING_QUERY_HISTORY_DIR)){
            if(!@mkdir(BILLING_QUERY_HISTORY_DIR, 0777, true)){
                $this->log->error('Could not create History directory "'.BILLING_QUERY_HISTORY_DIR.'", please check the permission.');
                $this->log->info ('Cancel generate Report.');
            }
        }
    }

    /**
     * Get all User list from SMS_API_V2.USER for API REPORT
     *
     * @return  Mixed   2D Array [['USER_ID', 'USER_NAME', 'BILLING_PROFILE_ID', 'BILLING_REPORT_GROUP_ID', 'BILLING_TIERING_GROUP_ID']]
     */
    public function getUserDetailApiReport($userId = null, $billingProfile = null) {

        $whereClause = !is_null($userId) || !is_null($billingProfile)
                        ? ' WHERE '
                        : '';

        $userClause = !is_null($userId)
                        ? ' USER_ID ' . (
                                is_array($userId) ? ' IN ('.implode(',', $userId ?: ['\'\'']).')': ' = '.$userId) : '';

        $billingClause = !is_null($billingProfile) ? (!is_null($userId) ? ' AND ' : '' ). ' BILLING_PROFILE_ID = '.$billingProfile.' ' : '';

        if (!$userId){

            $startDateTime  = $this->firstDateOfMonth;
            $endDateTime    = $this->lastFinalStatusDate;

            $start = '5OTP'.substr($startDateTime, 0,7)."%";
            $end = '5OTP'.substr($endDateTime, 0,7)."%";

            $distinctUser  =  ' SELECT DISTINCT (USER_MESSAGE_STATUS.USER_ID_NUMBER)'
                        . ' FROM  (SELECT '
                                . 'STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), \'%Y-%m-%d %H:%i:%s\') AS RECEIVE_DATETIME,'
                                . 'USER_ID_NUMBER'
                        . ' FROM '. DB_SMS_API_V2 . '.USER_MESSAGE_STATUS WHERE MESSAGE_ID like "'.$start.'" OR MESSAGE_ID like "'.$end.'") USER_MESSAGE_STATUS '
                        . ' WHERE     USER_MESSAGE_STATUS.RECEIVE_DATETIME >  \''.$startDateTime.'\' '
                        . '           AND USER_MESSAGE_STATUS.RECEIVE_DATETIME < \''.$endDateTime  .'\' ';

            $totalUsers = DB::select($distinctUser);

            if (!empty($totalUsers))
            {
                $whereClause= "";
                $userClause = " WHERE USER_ID ='".$totalUsers[0]->USER_ID_NUMBER."'";

                if (count($totalUsers) > 1){
                    for ($i=1; $i<count($totalUsers); $i++){
                        $userClause .=" OR USER_ID='".$totalUsers[$i]->USER_ID_NUMBER."'";
                    }
                }
            }
        }

        $select = ' SELECT   USER_ID, USER_NAME, BILLING_PROFILE_ID, BILLING_REPORT_GROUP_ID, BILLING_TIERING_GROUP_ID'
                .' FROM     '.DB_SMS_API_V2.'.USER'
                .  $whereClause
                .  $userClause
                .  $billingClause
                .' ORDER BY BILLING_PROFILE_ID';

        return DB::select($select);
    }

    /**
     * Get Detail of Billing Profile detail                                     <br />
     * would not return the rule of billing report                              <br />
     * use getOperatorBaseDetail() or getTieringDetail() instead to get price rule
     *
     * @return  Array   Array ['BILLING_TYPE', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT']
     */
    public function getBilingProfileDetail($billingProfileId = null) {
        $profileIdClause = !is_null($billingProfileId)
                                ? ' WHERE    BILLING_PROFILE_ID = '.$billingProfileId
                                : '';

        $query = ' SELECT  BILLING_PROFILE_ID, NAME, BILLING_TYPE, DESCRIPTION, CREATED_AT, UPDATED_AT'
                        .' FROM     '.DB_BILL_PRICELIST.'.BILLING_PROFILE'
                        .  $profileIdClause;

        return collect(DB::select($query))->first();

    }

    /**
     * Get Operator Dial Prefix form First_Intermedia.OPERATOR_DIAL_PREFIX      <br />
     *
     * @return  Array   2D Array [['OP_ID', 'RANGE_LOWER', 'RANGE_UPPER']]
     */
    public function getOperatorDialPrefix(Array $opId = []){
        $opClause = !empty($opId)
                        ? ' WHERE OPERATOR_ID IN(\''.implode('\',\'', $opId).'\') '
                        : '' ;
        $query = ' SELECT   OPERATOR_ID,'
                         .'OP_DIAL_RANGE_LOWER as RANGE_LOWER,'
                         .'OP_DIAL_RANGE_UPPER as RANGE_UPPER '
                        .' FROM     '.DB_BILL_U_MESSAGE.'.OPERATOR_DIAL_PREFIX '
                        .  $opClause
                        .' ORDER BY OPERATOR_ID ';

        return DB::select($query);
    }

    /**
     * Get Tiering Base Price Rule
     *
     * @return  Array   2D Array [['SMS_COUNT_FROM', 'SMS_COUNT_UP_TO', 'PER_SMS_PRICE']]
     */
    public function getTieringDetail($billingProfileId = null) {

        $query = ' SELECT   SMS_COUNT_FROM, SMS_COUNT_UP_TO, PER_SMS_PRICE'
                .' FROM     '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING'
                .' WHERE    BILLING_PROFILE_ID = '.$billingProfileId;

        return !empty($billingProfileId) ? DB::select($query)
                : [];
    }

    /**
     * Get Tiering Group Detail                                                 <br />
     * Would not return accumulate SMS_TRAFFIC or User List                     <br />
     * use getTieringGroupUserList() instead to get the User List
     *
     * @return  Mixed       List or an Array                                    <br />
     *                      ['NAME', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT']
     */
    public function getTieringGroupDetail($tieringGroupId = null) {
        $groupClause = !is_null($tieringGroupId)
                            ? ' WHERE    BILLING_TIERING_GROUP_ID = '.$tieringGroupId
                            : '';

        $query = ' SELECT   BILLING_TIERING_GROUP_ID , NAME, DESCRIPTION, CREATED_AT, UPDATED_AT'
                        .' FROM     '.DB_BILL_PRICELIST.'.BILLING_TIERING_GROUP'
                        .  $groupClause;

        return collect(DB::select($query))->first();
    }

    /**
     * Get Tiering Group User List which accumulate the same Tiering Rule
     *
     * @return  Array   Array ['USER_ID','USER_NAME']
     */
    public function getTieringGroupUserList($tieringGroupId) {

        $query = ' SELECT   USER_ID, USER_NAME, BILLING_PROFILE_ID, BILLING_TIERING_GROUP_ID'
                        .' FROM     '.DB_SMS_API_V2.'.USER'
                        .' WHERE    BILLING_TIERING_GROUP_ID = '.$tieringGroupId;

        return DB::select($query);
    }

    /**
     * Get Operator Base Price rule
     *
     * @return  Array   2D Array [['OP_ID', 'PER_SMS_PRICE']]
     */
    public function getOperatorBaseDetail($billingProfileId = null) {
        $query = ' SELECT   OP_ID, PER_SMS_PRICE'
                        .' FROM     '.DB_BILL_PRICELIST.'.BILLING_PROFILE_OPERATOR'
                        .' WHERE    BILLING_PROFILE_ID = '.$billingProfileId;

        return DB::select($query);
    }

    /**
     * Load single or all Billing Profile detail from cache
     *
     * @param   Int     $profileId      Billing Profile id
     * @return  Mixed                   Billing profile Detail or null
     */
    public function loadBillingProfileCache($profileId = null)
    {
        if (!is_null($profileId))
        {
            $billingDetail = $this->getBilingProfileDetail($profileId);
            if (!empty($billingDetail))
            {
                if (strtoupper($billingDetail->BILLING_TYPE) == self::BILLING_TIERING_BASE)
                {
                    $billingDetail->PRICING = $this->getTieringDetail($profileId);
                }
                else
                {
                    $billingDetail->PRICING = $this->getOperatorBaseDetail($profileId);
                    $billingDetail->PREFIX  = array_column($billingDetail->PRICING, 'OP_ID');
                }

                $billingProfileDetail = $billingDetail;
            }
            else
            {
                $billingProfileDetail = null;
            }
        }
        return $billingProfileDetail;
    }

    /**
     * Load single or all Report group detail from cache
     *
     * @param   Int     $groupId    Group Id
     * @return  Mixed               Report group detail or null
     */
    public function loadReportGroupCache($groupId = null) {
        $cache = 0;
        if(empty($cache)) {
            $cache  = $this->getReportGroupDetail();

            foreach($cache as &$group) {
                for ($i =0 ; $i<count($this->getReportGroupUserList($group->BILLING_REPORT_GROUP_ID)); $i++){
                    $group->USERS[] = $this->getReportGroupUserList($group->BILLING_REPORT_GROUP_ID)[$i];
                }
            }

            // $this->saveCache(self::CACHE_REPORT_GROUP, $cache);
        }

        if(!is_null($groupId)) {
            $key   = array_search(
                        $groupId,array_column($cache,'BILLING_REPORT_GROUP_ID')
                    );
            $cache = $key !== false
                        ? $cache[$key]
                        : null;
        }

        return $cache;
    }

    /**
     * Get one or all Report Group detail                                       <br />
     * Would not return User list who's merge their report file                 <br />
     * use getReportGroupUserList() instead to get the User List                <br />
     *
     * @param   Int     $reportGroupId  Billing Report Group Id
     * @return  Mixed   List or an array of report group detail                 <br />
     *                  ['NAME', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT']
     */
    public function getReportGroupDetail($reportGroupId = null) {
        $groupClause = !is_null($reportGroupId) ? ' WHERE    BILLING_REPORT_GROUP_ID = '.$reportGroupId : '';

        $query = ' SELECT   BILLING_REPORT_GROUP_ID, NAME, DESCRIPTION, CREATED_AT, UPDATED_AT'
                        .' FROM     '.DB_BILL_PRICELIST.'.BILLING_REPORT_GROUP'
                        .  $groupClause;

        return DB::select($query);
    }

    /**
     * Get Report Group User List who's join their report files
     *
     * @return  Array   Array ['USER_ID','USER_NAME']
     */
    public function getReportGroupUserList($reportGroupId) {
        $query = ' SELECT   USER_ID, USER_NAME,BILLING_PROFILE_ID'
                        .' FROM     '.DB_SMS_API_V2.'.USER'
                        .' WHERE    BILLING_REPORT_GROUP_ID = '.$reportGroupId;

        return DB::select($query);
    }


    /**
     * Get report package fileName
     *
     * @param  string $fileNamePrefix
     * @return string
     */
    protected function reportPackageFileName($fileNamePrefix)
    {
        return $this->reportDir .'/' . self::DIR_FINAL_REPORT . '/' . $fileNamePrefix . $this->periodSuffix . '.zip';
    }

    /**
     * Checking the report package exist or not
     *
     * @param string $fileNamePrefix
     * @return  bool
     */
    protected function isReportPackageExists($fileNamePrefix)
    {
        return file_exists($this->reportPackageFileName($fileNamePrefix));
    }

    /**
     * Get Message status by group
     *
     * @param   Array     $users            List of user with their last message send date time [['USER_ID','SEND_DATETIME']]
     * @param   String    $endDateTime      End Time
     * @param   String    $dataSize         Limit per query index
     * @param   String    $startIndex       Start index
     * @return  Array                       2D Array [[                                                     <br />
     *                                              'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                              'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                              'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                                      ]]
     */
    public function getGroupMessageStatus(array $users = [], $endDateTime = null, $dataSize = null, $startIndex = null)
    {
        $start = '5OTP'.substr($this->firstDateOfMonth, 0,7)."%";
        $end = '5OTP'.substr($endDateTime, 0,7)."%";

        $userClause  = [];

        foreach($users as $userId => $startDateTime) {
            $userClause[] = '( USER_MESSAGE_STATUS.USER_ID_NUMBER = '.$userId
                           .'  AND USER_MESSAGE_STATUS.RECEIVE_DATETIME > \''.$startDateTime.'\''
                           .(!$this->currentMonth ? '  AND USER_MESSAGE_STATUS.RECEIVE_DATETIME < \''.$endDateTime.'\' ' : ' ').')';

        }

        if(empty($userClause)){return [];}

        $query = ' SELECT USER_MESSAGE_STATUS.MESSAGE_ID,'
                            . 'USER_MESSAGE_STATUS.DESTINATION,'
                            . 'USER_MESSAGE_STATUS.OP_COUNTRY_CODE,'
                            . 'USER_MESSAGE_STATUS.OP_ID,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_CONTENT,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_STATUS,'
                            . '\'\' AS DESCRIPTION_CODE,'
                            . 'USER_MESSAGE_STATUS.RECEIVE_DATETIME,'
                            . 'USER_MESSAGE_STATUS.SEND_DATETIME,'
                            . 'USER_MESSAGE_STATUS.SENDER,'
                            . 'USER_MESSAGE_STATUS.USER_ID,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_COUNT'
                    . ' FROM  (SELECT '
                            . 'MESSAGE_ID,'
                            . 'DESTINATION,'
                            . 'USER_MESSAGE_STATUS.OP_COUNTRY_CODE,'
                            . 'USER_MESSAGE_STATUS.OP_ID,'
                            . 'MESSAGE_CONTENT,'
                            . 'MESSAGE_STATUS,'
                            . 'SEND_DATETIME,'
                            . 'STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), \'%Y-%m-%d %H:%i:%s\') AS RECEIVE_DATETIME,'
                            . 'SENDER,'
                            . 'USER_ID_NUMBER,'
                            . 'USER_ID,'
                            . "IF(
                                MESSAGE_CONTENT REGEXP \"".static::GSM_7BIT_CHARS."\",
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::UNICODE_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::UNICODE_MULTIPLE_SMS.")
                                )
                                ,
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::GSM_7BIT_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::GSM_7BIT_MULTIPLE_SMS.")
                                )
                            ) AS MESSAGE_COUNT"
                    . ' FROM '. DB_SMS_API_V2 . '.USER_MESSAGE_STATUS WHERE MESSAGE_ID like "'.$start.'" OR MESSAGE_ID like "'.$end.'") USER_MESSAGE_STATUS '
                       .' WHERE     ('.implode(' OR ', $userClause).')'
                       .' ORDER BY USER_MESSAGE_STATUS.MESSAGE_ID ASC '
                       .' LIMIT     '.$startIndex.','.$dataSize;

        $messages = DB::select($query);

        $this->log->info('query getGroupMessageStatus '.json_encode($userClause));

        return $messages;
    }

    /**
     * Get User Message list from SMS_API_V2.USER_MESSAGE_STATUS                                            <br />
     * could get by one or several USER_ID (TIERING_GROUP or REPORT_GROUP)                                  <br />
     *
     * @param   Mixed       $userId                 a string for single USER_ID                             <br />
     *                                              and an Array of USER_ID for mutiple USER_ID
     * @param   String      $startDateTime          the begining of SEND_DATETIME
     * @param   String      $endDateTime            the end of SEND_DATETIME
     * @param   Int         $dataSize               limit of record on one query
     * @param   Int         $startIndex             number of skipped row to return
     * @return  Array                               2D Array [[                                                 <br />
     *                                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                                              ]]
     */
    public function getUserMessageStatus($userId = null, $startDateTime = null, $endDateTime = null, $dataSize = null, $startIndex = null)
    {
        $start = '5OTP'.substr($startDateTime, 0,7)."%";
        $end = '5OTP'.substr($endDateTime, 0,7)."%";
        $userIdClause = is_array($userId) ? ' IN ('.implode(',', $userId).')' : ' = '.$userId;

        $message  =  ' SELECT USER_MESSAGE_STATUS.MESSAGE_ID,'
                            . 'USER_MESSAGE_STATUS.DESTINATION,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_CONTENT,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_STATUS,'
                            . 'USER_MESSAGE_STATUS.OP_COUNTRY_CODE,'
                            . 'USER_MESSAGE_STATUS.OP_ID,'
                            . '\'\' AS DESCRIPTION_CODE,'
                            . 'USER_MESSAGE_STATUS.RECEIVE_DATETIME,'
                            . 'USER_MESSAGE_STATUS.SEND_DATETIME,'
                            . 'USER_MESSAGE_STATUS.SENDER,'
                            . 'USER_MESSAGE_STATUS.USER_ID,'
                            . 'USER_MESSAGE_STATUS.MESSAGE_COUNT'
                    . ' FROM  (SELECT '
                            . 'MESSAGE_ID,'
                            . 'DESTINATION,'
                            . 'MESSAGE_CONTENT,'
                            . 'MESSAGE_STATUS,'
                            . 'OP_COUNTRY_CODE,'
                            . 'OP_ID,'
                            . 'SEND_DATETIME,'
                            . 'STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), \'%Y-%m-%d %H:%i:%s\') AS RECEIVE_DATETIME,'
                            . 'SENDER,'
                            . 'USER_ID_NUMBER,'
                            . 'USER_ID,'
                            . "IF(
                                MESSAGE_CONTENT REGEXP \"".static::GSM_7BIT_CHARS."\",
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::UNICODE_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::UNICODE_MULTIPLE_SMS.")
                                ),
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::GSM_7BIT_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::GSM_7BIT_MULTIPLE_SMS.")
                                )
                            ) AS MESSAGE_COUNT"
                    . ' FROM '. DB_SMS_API_V2 . '.USER_MESSAGE_STATUS WHERE USER_ID_NUMBER '.$userIdClause.' AND MESSAGE_ID like "'.$start.'" OR MESSAGE_ID like "'.$end.'") USER_MESSAGE_STATUS '
                    . ' WHERE     USER_MESSAGE_STATUS.USER_ID_NUMBER '.$userIdClause
                    . '           AND USER_MESSAGE_STATUS.RECEIVE_DATETIME >  \''.$startDateTime.'\' '
                    . '           AND USER_MESSAGE_STATUS.RECEIVE_DATETIME < \''.$endDateTime  .'\' '
                    . ' ORDER BY USER_MESSAGE_STATUS.MESSAGE_ID ASC '
                    . ' LIMIT     '.$startIndex.','.$dataSize;
        $messages = DB::select($message);
        return $messages;
    }

    /**
     * Create Zip package
     * Will Create an user or a group or all package file if
     *
     * @param String    $fileName   Report file name
     */
    private function createReportPackage($fileName = '*')
    {
        $dirFinal        = $this->reportDir.'/'. self::DIR_FINAL_REPORT;
        $finalReport     = $dirFinal.       '/'.$fileName.$this->periodSuffix.'*.xlsx';

        $fileName        = $fileName == '*' ? self::ALL_REPORT_PACKAGE : $fileName;
        $finalPackage    = $dirFinal.       '/'.$fileName.$this->periodSuffix.'.zip';

        exec('zip -j '.$finalPackage   .' '.$finalReport);
    }

    /**
     * Create Report file and set the headers
     *
     * @param String    $fileName               Report File name
     * @return void
     */
    private function createReportFile($fileName)
    {
        $dirFinal              = $this->reportDir.'/'. self::DIR_FINAL_REPORT;
        $finalReport           = $dirFinal.       '/'.$fileName.$this->periodSuffix.self::SUFFIX_FINAL_REPORT.'.xlsx';
        $summaryFinalReport    = $dirFinal.       '/'.$fileName.$this->periodSuffix.self::SUFFIX_SUMMARY_FINAL_REPORT.'.xlsx';
        $headers               = self::DETAILED_REPORT_HEADER;

        is_dir($dirFinal)    ?: @mkdir($dirFinal, 0777, true);


        $this->finalReportSummary    = ['senderId' => [], 'operator' => [], 'userId' => [], 'intl' => []];
        $this->finalReportWriter     = WriterEntityFactory::createWriter(Type::XLSX);

        $defaultStyle = (new StyleBuilder())
                ->setFontName('Arial')
                ->setFontSize(8)
                ->setShouldWrapText(true)
                ->build();

        $this->finalReportWriter->setDefaultRowStyle($defaultStyle);
        $this->finalReportWriter->openToFile($finalReport);

        $this->finalReportWriter->addRow(WriterEntityFactory::createRowFromArray($headers));
    }

    /**
     * Get Message Status Description by given ERROR_CODE
     *
     * @param   String  $errorCode      Error_Code | Message_Status
     * @return  String                  Description of ERROR_CODE
     */
    private function getMessageStatus(&$message) {
        $errorCode = $message->MESSAGE_STATUS;

        return isset($this->deliveryStatus[$errorCode])
                ? $this->deliveryStatus[$errorCode]
                : self::SMS_STATUS_UNDEFINED;
    }

    /**
     * Parsing the operator it's own Operator Name
     *
     * @param   String  $destination    Destination number wich will be parsing
     * @param   Array   $operators      2D Array of Operator                                <br />
     *                                  could be get from getOperatorDialPrefix()           <br />
     *                                  [['OP_ID', 'RANGE_LOWER', 'RANGE_UPPER']]           <br />
     * @return  String                  Operator Name or self::DEFAULT_OPERATOR
     */
    private function getDestinationOperator($op_id, &$operators) {
        foreach($operators as $operator){
            if ($operator !== self::DEFAULT_OPERATOR && $operator == $op_id){
                return $operator;
            }
        }
        return self::DEFAULT_OPERATOR;
    }

    /**
     * Format SEND_DATETIME, RECEIVE_DATETIME, DESCRIPTION CODE, MESSAGE_COUNT, OPERATOR field
     *
     * @param $message array containing details about one specific message, such as the content, send datetime, etc.
     * @param $operators array containing operator data
     * @return void
     */
    private function formatMessageData(&$message, &$operators){
        $message->SEND_DATETIME    = $this->clientTimeZone($message->SEND_DATETIME);
        $message->RECEIVE_DATETIME = $this->clientTimeZone($message->RECEIVE_DATETIME);
        $message->DESCRIPTION_CODE = $this->getMessageStatus($message);
        $message->OPERATOR         = $this->getDestinationOperator($message->OP_ID, $operators);
    }

    /**
     * Assign message price base on Billing profile type
     *
     *
     * @param   String  $type       Billing Type, defined on ApiReport::BILLING_*_BASE
     * @param   Array   $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                              ]]
     * @param   Array   $rule       2D array of Pricing  [['OP_ID', 'PER_SMS_PRICE']]
     * @param   Array   $operator   2D Array of Billing Rule                                    <br />
     *                              [['OP_ID']]
     * @return  Int                 Total price
     */
    public function formatMessages(String $type, Array &$messages, Array &$rules, Array &$operators = [])
    {
        return $type == self::BILLING_OPERATOR_BASE
                    ? $this->formatOperatorPrice($messages, $rules, $operators)
                    : $this->formatTieringPrice ($messages, $rules, $operators);
    }

    /**
     * Assign message price base on Billing profile type tiering-operator
     *
     *
     * @param   String  $type       Billing Type, defined on ApiReport::BILLING_*_BASE
     * @param   Array   $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                              ]]
     * @param   Array   $rule       2D array of Pricing  [['OP_ID', 'PER_SMS_PRICE']]
     * @param   Array   $operator   2D Array of Billing Rule                                    <br />
     *                              [['OP_ID']]
     * @return  Int                 Total price
     */
    public function formatMessagesTieringOperator(String $type, Array &$messages, Array &$rules, Array &$operators = [], $findTieringOperatorRangeID, Array &$opArray, Array &$priceArray)
    {
        return $this->formatTieringOperatorPrice($messages, $rules, $operators, $findTieringOperatorRangeID, $opArray, $priceArray);
    }

    /**
     * Validate tiering
     *
     *
     * @param   Array   $type       Billing Type, defined on ApiReport::BILLING_*_BASE
     * @param   Array   $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT', 'OPERATOR'                    <br />
     *                              ]]
     * @return  Array
     */
    public function manipulateOperator($operator)
    {
        if ($operator == "EXCELCOM" || $operator == "AXIS")
        {$operator = "XL";}
        else if ($operator == "IM3" || $operator == "SATELINDO")
        {$operator = "INDOSAT";}
        else if ($operator == "SMART" || $operator == "MOBILE_8")
        {$operator = "SMART";}
        else if ($operator == "TELKOMSEL" || $operator == "TELKOMMOBILE")
        {$operator = "TELKOMSEL";}
        else if ($operator == "THREE")
        {$operator = "HUTCH";}
        return $operator;
    }

    /**
     * Close and save both Final and Awaiting report Writer handler
     *
     * @return void
     */
    private function saveReportFile()
    {
        $this->finalReportWriter->close();
    }

    /**
     * List of summary color style
     *
     * @return Object   List of summary report style like color, font, background etc,
     */
    private function getSummaryColorStyle() {
        return (object) [
            'bold'  => ['font'      => ['bold' => true]],

            'center'=> [
                        'font'      => ['bold' => true],
                        'alignment' => ['horizontal' => 'center','vertical'   => 'center',],
                    ],

            'black' => [
                        'font' => ['bold' => true,   'color' => ['argb' => '#FFFFFF']],
                        'fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#000000']]
                    ],

            'gray'  => [
                        'font' => ['bold' => true,   'color' => ['argb' => '#FFFFFF']],
                        'fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#333333']]
                    ],

            'odd'   => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#FF3300']]],
            'even'  => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#3366FF']]],

            'd'     => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#99FF00']]],
            'udC'   => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#66FF33']]],
            'udUc'  => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#FFCC99']]],
            'ts'    => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#33FF99']]],
            'tsC'   => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#00FFFF']]],
            'tp'    => ['fillType' => [Fill::FILL_SOLID,'color' => ['argb' => '#00CCFF']]],

            'intlPrice' => [
                'fillType'      => [Fill::FILL_SOLID, 'color' => ['argb' => '#ccff66']],
                'font'      => ['bold' => true],
                'alignment' => ['horizontal' => 'center','vertical'   => 'center',],
            ],
        ];

    }

    /**
     * Generate Summary Report file                                             <br />
     *                                                                          <br />
     * This function would generate 2 report file                               <br />
     * included "Final Report Summary" and "Awaiting Report Summary"            <br />
     *                                                                          <br />
     * use this function after generate detailed report                         <br />
     * this function would get summary data from                                <br />
     * $this->finalReportSummary                                                <br />
     *
     *
     * @param String    $fileName   User Detailed Report file name
     * @param Mixed     $userIds    Single User id or list of User id
     */
    private function saveSummary($fileName, $userIds, &$billingProfile)
    {
        $fReport        = new Spreadsheet();
        $startRow       = 1;
        $userNames      = [];
        $dirFinal       = $this->reportDir.'/'. self::DIR_FINAL_REPORT;
        $finalReport    = $dirFinal.       '/'.$fileName.$this->periodSuffix.self::SUFFIX_SUMMARY_FINAL_REPORT.'.xlsx';
        $userIds        = is_array($userIds) ? $userIds : [$userIds];

        foreach ($userIds as $userId)
        {
            $uID = $this->getUserDetailApiReport($userId);
            $userNames[]= $uID[0]->USER_NAME;
        }

        $userNames = array_unique($userNames);

        $this->setSummaryReportHeader($fReport, $userNames, $this->finalReportSummary, $billingProfile, $startRow);
        $this->insertSummaryByCategory('SENDER',   $fReport, $this->finalReportSummary['senderId'], $startRow);
        $this->insertSummaryByCategory('OPERATOR', $fReport, $this->finalReportSummary['operator'], $startRow +37);
        $this->insertSummaryByCategory('USER NAME', $fReport, $this->finalReportSummary['userId'], $startRow +74);
        $this->insertOperatorPriceByCategory('OPERATOR', $fReport, $this->messageOperatorDistinct, 145);
        $this->setSummaryToAutoSize($fReport);

        $writer = IOFactory::createWriter($fReport, 'Xlsx');
        $writer->save($finalReport);
    }

    /**
     * Resize all excel columns to auto size
     *
     * @param PHPExecl  $excel  PHP Excel Object
     */
    private function setSummaryToAutoSize(&$excel) {
        $sheet = $excel->setActiveSheetIndex(0);
        foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Generate Summary Report Header and set excel file information
     * ----------------------------------------------------------------
     * Last Update Date           Wednesday, 07 June 2017 at 09:49
     * User Name                  yamahabdg
     *
     * Delivered                  1413
     * Undelivered (charged)      48
     * Undelivered (uncharged)    5
     * Total SMS                  1466
     * Total SMS Charged          1461
     * Total Price                999999
     * ----------------------------------------------------------------
     *
     * @param PHPExcel  $excel      PHP Excel Object
     * @param Mixed     $userName   UserId
     * @param Array     $data
     * @param Int       $startRow
     */
    private function setSummaryReportHeader(&$excel, $userName, &$data, &$billingProfile, &$startRow)
    {
        $sheet     = $excel->setActiveSheetIndex(0);
        $lastCol   = 'G';
        $userNames = is_array($userName) ? implode(', ', $userName) : $userName;
        $style     = $this->getSummaryColorStyle();
        $date      = $this->clientTimeZone(date('Y-m-d H:i:s'),'l, d F Y \a\t H:i');
        $sum       = [
            'd'    => 0,
            'udC'  => 0,
            'udUc' => 0,
            'ts'   => 0,
            'tsC'  => 0,
            'tp'   => 0
        ];

        // Calculate Summary
        foreach($data['userId'] as &$traffics)
        {
            $sum['d']    += array_sum(array_column($traffics,'d'));
            $sum['udC']  += array_sum(array_column($traffics,'udC'));
            $sum['udUc'] += array_sum(array_column($traffics,'udUc'));
            $sum['ts']   += array_sum(array_column($traffics,'ts'));
            $sum['tsC']  += array_sum(array_column($traffics,'tsC'));
            $sum['tp']   += array_sum(array_column($traffics,'tp'));

        }

        $excel->getProperties()
                    ->setCreator('1rstwap')
                    ->setLastModifiedBy('SMS_API_ADMIN')
                    ->setTitle('Billing Report '.$userNames.' on '.date('M_Y', strtotime($this->year.'-'.$this->month)))
                    ->setSubject('Billing Report');

        $sheet
            ->setCellValue('A'.$startRow,       'Last Update Date')     ->setCellValue('B'.$startRow, $date)                                                   ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Billing Profile Name') ->setCellValue('B'.$startRow, $billingProfile->NAME)                                 ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Profile Type')         ->setCellValue('B'.$startRow, ucfirst(strtolower($billingProfile->BILLING_TYPE)))    ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            // ->setCellValue('A'.($startRow+=1),  'International Price')  ->setCellValue('B'.$startRow, $useInternationalPrice)
            ->setCellValue('A'.($startRow+=1),  'User Name')            ->setCellValue('B'.$startRow, $userNames)                                              ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
        ;

        // Add Empty Row
        $startRow++;

        // Summary Total Messages count
        $sheet
            ->setCellValue('A'.($startRow+=1),  'Delivered')              ->setCellValue('B'.$startRow, number_format($sum['d']))      ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Undelivered (charged)')  ->setCellValue('B'.$startRow, number_format($sum['udC']))    ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Undelivered (uncharged)')->setCellValue('B'.$startRow, number_format($sum['udUc']))   ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Total SMS')              ->setCellValue('B'.$startRow, number_format($sum['ts']))     ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Total SMS Charged')      ->setCellValue('B'.$startRow, number_format($sum['tsC']))    ->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+=1),  'Total Price')            ->setCellValue('B'.$startRow, $this->formatPrice($sum['tp']))->mergeCells('B'.$startRow.':'  . $lastCol.$startRow)
            ->getStyle('B'.($startRow-5).':B'.$startRow)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        ;

        // Add Empty Row
        $startRow+=3;

         // Legend Information
        $sheet
            ->setCellValue('A'.($startRow), static::LEGEND_SUMMARY_TITLE)   ->mergeCells('A'.($startRow).':' . $lastCol.$startRow)
            ->setCellValue('A'.($startRow+1), 'D')          ->setCellValue('B'.($startRow+1), 'Delivered')                  ->mergeCells('B'.($startRow+1).':' . $lastCol.($startRow+1))
            ->setCellValue('A'.($startRow+2), 'UD_C')       ->setCellValue('B'.($startRow+2), 'Undelivered (Charged)')      ->mergeCells('B'.($startRow+2).':' . $lastCol.($startRow+2))
            ->setCellValue('A'.($startRow+3), 'UD_UC')      ->setCellValue('B'.($startRow+3), 'Undelivered (Uncharged)')    ->mergeCells('B'.($startRow+3).':' . $lastCol.($startRow+3))
            ->setCellValue('A'.($startRow+4), 'TS')         ->setCellValue('B'.($startRow+4), 'Total SMS')                  ->mergeCells('B'.($startRow+4).':' . $lastCol.($startRow+4))
            ->setCellValue('A'.($startRow+5), 'TS_C')       ->setCellValue('B'.($startRow+5), 'Total SMS Charged')          ->mergeCells('B'.($startRow+5).':' . $lastCol.($startRow+5))
            ->setCellValue('A'.($startRow+6), 'TP')         ->setCellValue('B'.($startRow+6), 'Total Price')                ->mergeCells('B'.($startRow+6).':' . $lastCol.($startRow+6))

            // Set cell alignment to right
            ->getStyle('B'.$startRow.':B'.($startRow+6))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Set legend color
        $sheet->getStyle('A'.$startRow)        ->applyFromArray($style->center);
        $sheet->getStyle('A'.$startRow)        ->applyFromArray($style->black);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->d);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->udC);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->udUc);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->ts);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->tsC);
        $sheet->getStyle('A'.($startRow+=1))   ->applyFromArray($style->tp);

        // Add Empty Row
        $startRow+=3;
    }

    /**
     * Dump symmary data by group 'userId' or 'operator' or 'sender'
     *
     * -----------------|--------------------------------------------------------------------------------------------
     *                  |               OPERATOR                            # level 1
     *                  |--------------------------------------------------------------------------------------------
     *     Date         |               TELKOMSEL                       DEFAULT         # level 2
     *                  |--------------------------------------------------------------------------------------------
     *                  | D UD_C    UD_UC   TS  TS_C    TP  D   UD_C    UD_UC   TS  TS_C    TP      # level 3
     * -----------------|--------------------------------------------------------------------------------------------
     * 2017-05-01       | 0 0   0   0   0   0   0   0   0   0   0   0
     * 2017-05-02       | 29    0   0   29  29  0   33  0   0   33  33  0
     * 2017-05-03       | 48    0   1   48  49  0   65  0   0   65  65  0
     * 2017-05-04       | 39    0   0   39  39  0   42  1   0   43  43  0
     * 2017-05-05       | 38    0   0   38  38  0   55  1   0   56  56  0
     * -----------------|--------------------------------------------------------------------------------------------
     *
     * @param String    $type       Group Name
     * @param PHPExcel  $excel      PHP Excel Object
     * @param Array     $data       Summary data list $this->finalReportSummary
     * @param Int       $startRow   Start row
     */
    private function insertSummaryByCategory($type ,&$excel, &$data, $startRow) {
        $sheet    = $excel->setActiveSheetIndex(0);
        $style    = $this->getSummaryColorStyle();
        $colWidth = count($data) *6;
        $lastCol  = 'G';
        $iterator = 0;

        // Set Column Title level 1
        $sheet  ->setCellValue('A'.$startRow, 'Date') ->mergeCells('A'.$startRow.':A'.($startRow +2))
                ->setCellValue('B'.$startRow, $type);

        $i = 0;
        foreach($data as $group => $traffics) {
            // Set Column Name
            $start    = ($i++ *6) +1;
            $startCol = Coordinate::stringFromColumnIndex($start+1);
            $endCol   = Coordinate::stringFromColumnIndex(($i *6)+1);
            $col      = [
                            'd'     => Coordinate::stringFromColumnIndex($start +1),
                            'udC'   => Coordinate::stringFromColumnIndex($start +2),
                            'udUc'  => Coordinate::stringFromColumnIndex($start +3),
                            'ts'    => Coordinate::stringFromColumnIndex($start +4),
                            'tsC'   => Coordinate::stringFromColumnIndex($start +5),
                            'tp'    => Coordinate::stringFromColumnIndex($start +6),
                        ];
            $lastCol  = $col['tp'];


            // Set Column Title level 2 & 3
            $sheet
                ->setCellValue($startCol   . ($startRow +1), $group)    ->mergeCells($startCol.($startRow +1).':'.$endCol.($startRow +1))
                ->setCellValue($col['d']   . ($startRow +2), 'D')
                ->setCellValue($col['udC'] . ($startRow +2), 'UD_C')
                ->setCellValue($col['udUc']. ($startRow +2), 'UD_UC')
                ->setCellValue($col['ts']  . ($startRow +2), 'TS')
                ->setCellValue($col['tsC'] . ($startRow +2), 'TS_C')
                ->setCellValue($col['tp']  . ($startRow +2), 'TP');

            // Set current Group Column Title Style
            $sheet->getStyle($startCol   . ($startRow +1)) ->applyFromArray($i%2 ? $style->even : $style->odd);
            $sheet->getStyle($col['d']   . ($startRow +2)) ->applyFromArray($style->d);
            $sheet->getStyle($col['udC'] . ($startRow +2)) ->applyFromArray($style->udC);
            $sheet->getStyle($col['udUc']. ($startRow +2)) ->applyFromArray($style->udUc);
            $sheet->getStyle($col['ts']  . ($startRow +2)) ->applyFromArray($style->ts);
            $sheet->getStyle($col['tsC'] . ($startRow +2)) ->applyFromArray($style->tsC);
            $sheet->getStyle($col['tp']  . ($startRow +2)) ->applyFromArray($style->tp);

            // Insert Summary per day transaction specific group item
            $iterator = $startRow +2;

            // Set cell alignment to right
            $sheet
                ->getStyle($col['d'] . ($startRow + 3) .':'.$col['tp'].($iterator + count($traffics) + 1))
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            foreach($traffics as $date => $traffic) {
                $sheet
                    ->setCellValue('A' .       ++$iterator, $date)
                    ->setCellValue($col['d']   . $iterator, number_format($traffic['d']))
                    ->setCellValue($col['udC'] . $iterator, number_format($traffic['udC']))
                    ->setCellValue($col['udUc']. $iterator, number_format($traffic['udUc']))
                    ->setCellValue($col['ts']  . $iterator, number_format($traffic['ts']))
                    ->setCellValue($col['tsC'] . $iterator, number_format($traffic['tsC']))
                    ->setCellValue($col['tp']  . $iterator, $this->formatPrice($traffic['tp']));
            }

            // Write Total of perday transaction
            $sheet
                ->setCellValue('A' .       ++$iterator, 'TOTAL')
                ->setCellValue($col['d']   . $iterator, number_format(array_sum(array_column($traffics,'d'))))
                ->setCellValue($col['udC'] . $iterator, number_format(array_sum(array_column($traffics,'udC'))))
                ->setCellValue($col['udUc']. $iterator, number_format(array_sum(array_column($traffics,'udUc'))))
                ->setCellValue($col['ts']  . $iterator, number_format(array_sum(array_column($traffics,'ts'))))
                ->setCellValue($col['tsC'] . $iterator, number_format(array_sum(array_column($traffics,'tsC'))))
                ->setCellValue($col['tp']  . $iterator, $this->formatPrice(array_sum(array_column($traffics,'tp'))));
        }

        // Merge Column which contain "group" label
        $sheet->mergeCells('B'.$startRow.':'.$lastCol.$startRow);

        // Center all Column Title
        $sheet->getStyle('A'.$startRow.':'.$lastCol.($startRow +2)) ->applyFromArray($style->center);

        // Fill "Total" row with sytle gray
        $sheet->getStyle('A'.$iterator.':'.$lastCol.$iterator)      ->applyFromArray($style->gray);

        // Fill "Date" and "group" label with style black
        $sheet->getStyle('A'.$startRow.':B'.$startRow)->applyFromArray($style->black);
    }

    private function insertOperatorPriceByCategory($type ,&$excel, &$data, $startRow) {
        $sheet    = $excel->setActiveSheetIndex(0);
        $iterator = 0;
        $totalPrice = 0;

        $sheet  ->setCellValue('A'.$startRow, "OPERATOR DISTINCT")
                ->setCellValue('B'.$startRow, "TOTAL QUANTITY (CHARGED)")
                ->setCellValue('C'.$startRow, "TOTAL QUANTITY (UNCHARGED)")
                ->setCellValue('D'.$startRow, "SINGLE PRICE")
                ->setCellValue('E'.$startRow, "TOTAL PRICE (CHARGED)");

        for ($i=0 ; $i<count($this->messageOperatorDistinct);$i++){
            $startRow = $startRow + 1;

            if (empty($this->messageTotalQuantityCharged[$i])){
                $this->messageTotalQuantityCharged[$i] = 0;
            }

            if (empty($this->messageTotalQuantityUncharged[$i])){$this->messageTotalQuantityUncharged[$i] = 0;}

            $sheet  ->setCellValue('A'.$startRow, $this->messageOperatorDistinct[$i])
                ->setCellValue('B'.$startRow, $this->messageTotalQuantityCharged[$i])
                ->setCellValue('C'.$startRow, $this->messageTotalQuantityUncharged[$i])
                ->setCellValue('D'.$startRow, $this->messageSinglePrice[$i])
                ->setCellValue('E'.$startRow, $this->messageSinglePrice[$i] * $this->messageTotalQuantityCharged[$i]);

            $totalPrice = $totalPrice + ($this->messageSinglePrice[$i] * $this->messageTotalQuantityCharged[$i]);
        }

        $startRow++;

        if (count($this->messageOperatorDistinct) > 0){
            $sheet  ->setCellValue('E'.$startRow, $totalPrice);
            $sheet->getStyle('E'.$startRow)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => Border::BORDER_THIN,
                            'color' => array('rgb' => 'DDDDDD')
                        )
                    )
                )
            );
        }else{
            $sheet  ->setCellValue('A'.$startRow, "NO DATA")
                ->setCellValue('B'.$startRow, "NO DATA")
                ->setCellValue('C'.$startRow, "NO DATA")
                ->setCellValue('D'.$startRow, "NO DATA")
                ->setCellValue('E'.$startRow, "NO DATA");
        }

    }

    /**
     * Assign message price when billing profil is Operator Base
     *
     * @param   Array   $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                              ]]
     * @param   Array   $rule       2D array of Pricing  [['OP_ID', 'PER_SMS_PRICE']]
     * @param   Array   $operator   2D Array of Billing Rule                                    <br />
     *                              [['OP_ID', 'RANGE_LOWER', 'RANGE_UPPER']]
     */
    private function formatOperatorPrice(&$messages, &$rules, &$operators)
    {
        $a =0 ;
        $tmpCache = 0;
        $perSmsPrice = 0;

        $pid = getmypid();
        foreach($messages as &$message)
        {
            $this->formatMessageData($message, $operators);

            $message->OPERATOR = $message->OPERATOR ?? static::DEFAULT_OPERATOR;

            /**
             * find pricing
             */

            for ($i=0;$i<count($rules);$i++){
                if ($rules[$i]->OP_ID == $message->OPERATOR){
                    $perSmsPrice = $rules[$i]->PER_SMS_PRICE;
                }
            }

            if (empty($perSmsPrice)){
                $perSmsPrice = 0;
            }

            if ($message->DESCRIPTION_CODE !== self::SMS_STATUS_UNDELIVERED)
            {
                $message->PRICES = $this->formatPrice(intval($perSmsPrice));
                $message->PRICE   = $this->formatPrice(intval($perSmsPrice * intval($message->MESSAGE_COUNT)));
            }
            else
            {
                $message->PRICES = 0;
                $message->PRICE   = 0;
            }

            $message->OPERATOR = $this->manipulateOperator($message->OPERATOR);

            $this->getMessageSummary($message);

            $insertMessage = (array) $message;
            $this->finalReportWriter->addRow(WriterEntityFactory::createRowFromArray($insertMessage));

            if (in_array($message->OPERATOR, $this->messageOperatorDistinct)){

                $key = array_search($message->OPERATOR, $this->messageOperatorDistinct);

                if (!isset($this->messageTotalQuantityCharged[$key])){
                    $this->messageTotalQuantityCharged[$key] = 0;
                }

                if (!isset($this->messageTotalQuantityUncharged[$key])){
                    $this->messageTotalQuantityUncharged[$key] = 0;
                }

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$key] = $this->messageTotalQuantityCharged[$key]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$key] = $this->messageTotalQuantityUncharged[$key]+$message->MESSAGE_COUNT;
                }

            }else{
                $a = $a+1;

                $this->messageTotalQuantityCharged[$a]  = 0;
                $this->messageTotalQuantityUncharged[$a]= 0;

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$a] = $this->messageTotalQuantityCharged[$a]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$a] = $this->messageTotalQuantityUncharged[$a]+$message->MESSAGE_COUNT;
                }

            }
        }
    }

    /**
     * Get country detail name by given country code
     *
     * @param   $countryCode   COUNTRY_CODE
     * @return  Mixed          List of Operator details or null
     */
    public function getCountryName($countryCode = '')
    {
        $query = "SELECT * FROM ".DB_SMS_API_V2.".COUNTRY WHERE COUNTRY_CODE = '{$countryCode}' LIMIT 1";
        return collect(DB::select($query))->first();
    }

    /**
     * Assign message price when billing profil was in TIERING or FIXED price Base
     *
     * @param Array     $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                              ]]
     * @param   Array   $rule       2D array of Pricing  [['OP_ID', 'PER_SMS_PRICE']]
     */
    private function formatTieringPrice(&$messages, &$rules, &$operators, $useInternationalPrice = false)
    {
        $price         =  current($rules)->PER_SMS_PRICE;
        $a = 0;
        $tmpCache = 0;
        foreach($messages as &$message)
        {
            /**
             * Validate if the message already formated or not
             */
            if (empty($message->DESCRIPTION_CODE))
            {
                $this->formatMessageData($message, $operators);
            }

            $message->OPERATOR= $message->OP_ID ?? static::DEFAULT_OPERATOR;

            if ($message->DESCRIPTION_CODE !== self::SMS_STATUS_UNDELIVERED)
            {
                $message->PRICES = $this->formatPrice(intval($price));
                $message->PRICE   = $this->formatPrice(intval($price * intval($message->MESSAGE_COUNT)));
            }
            else
            {
                $message->PRICES = 0;
                $message->PRICE   = 0;
            }

            $countryName = $this->getCountryName($message->OP_COUNTRY_CODE);

            $countryName = $countryName->COUNTRY_NAME;

            $message->OPERATOR = $this->manipulateOperator($message->OPERATOR);

            $this->getMessageSummary($message, $useInternationalPrice, $countryName);

            $insertMessage = (array) $message;
            $this->finalReportWriter->addRow(WriterEntityFactory::createRowFromArray($insertMessage));

            if (in_array($message->OPERATOR, $this->messageOperatorDistinct) || in_array($countryName, $this->messageOperatorDistinct)){

                $key = array_search($message->OPERATOR, $this->messageOperatorDistinct);

                if (!$key) $key = array_search($countryName, $this->messageOperatorDistinct);

                if (!isset($this->messageTotalQuantityCharged[$key])){
                    $this->messageTotalQuantityCharged[$key] = 0;
                }

                if (!isset($this->messageTotalQuantityUncharged[$key])){
                    $this->messageTotalQuantityUncharged[$key] = 0;
                }

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$key] = $this->messageTotalQuantityCharged[$key]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$key] = $this->messageTotalQuantityUncharged[$key]+$message->MESSAGE_COUNT;
                }
            }
            else{
                $a = $a+1;

                $this->messageTotalQuantityCharged[$a]  = 0;
                $this->messageTotalQuantityUncharged[$a]= 0;

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$a] = $this->messageTotalQuantityCharged[$a]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$a] = $this->messageTotalQuantityUncharged[$a]+$message->MESSAGE_COUNT;
                }
            }

        }
    }

    /**
     * Assign message price when billing profil was in TIERING OPERATOR
     *
     * @param Array     $messages   2D Array [[                                                 <br />
     *                                  'MESSAGE_ID', 'DESTINATION', 'MESSAGE_CONTENT',         <br />
     *                                  'MESSAGE_STATUS', 'DESCRIPTION_CODE', 'SEND_DATETIME',  <br />
     *                                  'SENDER', 'USER_ID', 'MESSAGE_COUNT'                    <br />
     *                              ]]
     * @param   Array   $rule       2D array of Pricing  [['OP_ID', 'PER_SMS_PRICE']]
     */
    private function formatTieringOperatorPrice(&$messages, &$rules, &$operators, $findTieringOperatorRangeID, &$opArray, &$priceArray)
    {
        $a =0 ;

        $tmpCache = 0;

        foreach($messages as &$message)
        {

            /**
             * Validate if the message already formated or not
             */
            if (empty($message->DESCRIPTION_CODE))
            {
                $this->formatMessageData($message, $operators);
            }

            $message->OPERATOR= $message->OP_ID ?? static::DEFAULT_OPERATOR;

            if (in_array($message->OPERATOR,$opArray))
            {

                $keyPrice = array_search($message->OPERATOR, $opArray);

                $perSmsPrice = $priceArray[$keyPrice];
            }else{
                $message->OPERATOR = "DEFAULT";

                $keyPrice = array_search($message->OPERATOR, $opArray);

                $perSmsPrice = $priceArray[$keyPrice];
            }

            $message->OPERATOR= $message->OPERATOR ?? static::DEFAULT_OPERATOR;

            if ($message->DESCRIPTION_CODE !== self::SMS_STATUS_UNDELIVERED)
            {
                $message->PRICES = $this->formatPrice(intval($perSmsPrice));
                $message->PRICE   = $this->formatPrice(intval($perSmsPrice * intval($message->MESSAGE_COUNT)));
            }
            else
            {
                $message->PRICES = $this->formatPrice(intval($perSmsPrice));
                $message->PRICE   = $this->formatPrice(intval($perSmsPrice * intval($message->MESSAGE_COUNT)));
            }

            $message->OPERATOR = $this->manipulateOperator($message->OPERATOR);

            $this->getMessageSummary($message);

            $insertMessage = (array) $message;
            $this->finalReportWriter->addRow(WriterEntityFactory::createRowFromArray($insertMessage));

            if (in_array($message->OPERATOR, $this->messageOperatorDistinct)){

                $key = array_search($message->OPERATOR, $this->messageOperatorDistinct);

                if (!isset($this->messageTotalQuantityCharged[$key])){
                    $this->messageTotalQuantityCharged[$key] = 0;
                }

                if (!isset($this->messageTotalQuantityUncharged[$key])){
                    $this->messageTotalQuantityUncharged[$key] = 0;
                }

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$key] = $this->messageTotalQuantityCharged[$key]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$key] = $this->messageTotalQuantityUncharged[$key]+$message->MESSAGE_COUNT;
                }

            }else{
                $a = $a+1;

                $this->messageTotalQuantityCharged[$a]  = 0;
                $this->messageTotalQuantityUncharged[$a]= 0;

                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED"){
                    $this->messageTotalQuantityCharged[$a] = $this->messageTotalQuantityCharged[$a]+$message->MESSAGE_COUNT;
                }else if ($message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){
                    $this->messageTotalQuantityUncharged[$a] = $this->messageTotalQuantityUncharged[$a]+$message->MESSAGE_COUNT;
                }

            }
        }
    }

    /**
     * Format the price value to currency format
     * example : 8,816,395.50
     *
     * @param float $price      The price in decimal/float data type
     * @return  String          Formatted price
     */
    protected function formatPrice($price)
    {
        return number_format(floatval($price), 2);
    }

    /**
     * Convert currency format to float
     *
     * @param  String $price  currency format value that come from billing report, ex: 1,234.99
     * @return float          the return value is a float format, ex: 1234.99
     */
    protected function toFloat($price)
    {
        return floatval(str_replace(",", "", $price));
    }

    /**
     * Get Message Summary
     *
     * @param Array     $message   A message data
     * @return void
     */
    private function getMessageSummary(&$message, $useInternationalPrice = false, $countryName = false)
    {
        if (!empty($message))
        {
            $sendDate = date('Y-m-d', strtotime($message->RECEIVE_DATETIME));
            $status   = $message->DESCRIPTION_CODE;
            $price    = $this->toFloat($message->PRICE);
            $smsCount = $message->MESSAGE_COUNT;

            if (!in_array($message->OPERATOR, $this->messageOperatorDistinct)){
                if ($message->DESCRIPTION_CODE == "UNDELIVERED (CHARGED)" || $message->DESCRIPTION_CODE == "DELIVERED" || $message->DESCRIPTION_CODE == "UNDELIVERED (UNCHARGED)"){

                    $this->messageOperatorDistinct[] = $message->OPERATOR;
                    if (empty($message->PRICES)){
                        $this->messageSinglePrice[] = $price;
                        $message->PRICES = $price;
                    }else{
                        $this->messageSinglePrice[] = $this->toFloat($message->PRICES);
                    }
                }
            }

            $this->storeSummary($this->finalReportSummary, 'senderId', $message->SENDER, $sendDate, $status, $price, $smsCount);
            $this->storeSummary($this->finalReportSummary, 'operator', $message->OPERATOR, $sendDate, $status, $price, $smsCount);
            $this->storeSummary($this->finalReportSummary, 'userId',   $message->USER_ID, $sendDate, $status, $price, $smsCount);
        }
    }

    /**
     * Gererate date range
     * to initialize data from first til end date of the month
     * if new member was added into summary group
     *
     * @param   String    $first            Start Date
     * @param   String    $last             End Date
     * @return  Array                       List of Range date
     */
    public function getDateRange($first, $last) {
        $dates   = [];
        $current = strtotime($first);
        $last    = strtotime($last);

        while( $current <= $last ) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }
        return $dates;
    }

    /**
     * Save message infromation into summary
     *
     * @param Array     $summary    "finalReportSummary"
     * @param String    $group      Group name like 'operator' or 'sender'
     * @param String    $member     Member name like 'TELKOMSEL' or 'IM3' etc
     * @param String    $date       Message date
     * @param String    $status     Message Status
     * @param Int       $price      Message Price
     * @param Int       $count      Message Count
     */
    private function storeSummary(Array &$summary, $group, $member, $date, $status, $price, $count)
    {
        isset($summary[$group]) ?: $summary[$group] = [];

        if (!isset($summary[$group][$member])) {
           $startDate = $this->clientTimeZone(strtotime($this->firstDateOfMonth.' 1 second'));
           $endDate = $this->clientTimeZone(strtotime($this->lastDateOfMonth.' -1 second'));
           $period  = $this->getDateRange($startDate, $endDate);
           $traffic = [
                    'd'     => 0,   // Delivered
                    'udC'   => 0,   // Undelivered Charged
                    'udUc'  => 0,   // Undelivered Uncharged
                    'tsC'   => 0,   // Total SMS Charged
                    'ts'    => 0,   // Total SMS
                    'tp'    => 0,   // Total Price
                ];

           $summary[$group][$member] = array_fill_keys($period, $traffic);
        }

        $transaction = &$summary[$group][$member][$date];

        switch($status) {
            case self::SMS_STATUS_DELIVERED:
                    $transaction['d']    += $count;
                    $transaction['tsC']  += $count;
                    $transaction['tp'] += $price;
                break;
            case self::SMS_STATUS_UNDELIVERED_CHARGED:
                    $transaction['udC']  += $count;
                    $transaction['tsC']  += $count;
                    $transaction['tp'] += $price;
                break;
            case self::SMS_STATUS_UNDELIVERED:
                    $transaction['udUc'] += $count;
                    $transaction['tp'] += 0;
                break;
        }

        $transaction['ts'] += $count;
    }

    /**
     * Get user or group tiering monthly traffic
     *
     * @param   Mixed   $userIds                   User Id or list of user id if need group traffic
     * @param   Bool    $useInternationalPrice     Exclude the international number for accumulate traffic
     * @return  Int
     */
    public function getTieringTraffic($userIds)
    {
        $userIds      = is_array($userIds)
                            ? array_column($userIds, 'USER_ID')
                            : $userIds;

        $usersClause  = is_array($userIds)
                            ? ' IN ('.implode(',', $userIds).') '
                            : ' = '.$userIds;

        $endDateQuery = $this->currentMonth
                        ? ''
                        : "AND USER_MESSAGE_STATUS.RECEIVE_DATETIME < '{$this->lastFinalStatusDate}'";

        if ($userIds == []){
          $query = "SELECT SUM(MESSAGE_COUNT) as FINAL_TIERING_TRAFFIC FROM (SELECT
                              STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), '%Y-%m-%d %H:%i:%s') AS RECEIVE_DATETIME,
                              IF(
                                  MESSAGE_CONTENT REGEXP \"".static::GSM_7BIT_CHARS."\",
                                  IF(
                                      CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::UNICODE_SINGLE_SMS.",
                                      1,
                                      CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::UNICODE_MULTIPLE_SMS.")
                                  ),
                                  IF(
                                      CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::GSM_7BIT_SINGLE_SMS.",
                                      1,
                                      CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::GSM_7BIT_MULTIPLE_SMS.")
                                  )
                              ) AS MESSAGE_COUNT
                              FROM ".DB_SMS_API_V2.".USER_MESSAGE_STATUS
                              WHERE MESSAGE_STATUS NOT IN ({$this->unchargedDeliveryStatus})
                    ) USER_MESSAGE_STATUS
                      WHERE USER_MESSAGE_STATUS.RECEIVE_DATETIME > '{$this->firstDateOfMonth}'
                            {$endDateQuery}
                    ";
        }else{
          $query = "SELECT SUM(MESSAGE_COUNT) as FINAL_TIERING_TRAFFIC FROM (SELECT
                              STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), '%Y-%m-%d %H:%i:%s') AS RECEIVE_DATETIME,
                              IF(
                                  MESSAGE_CONTENT REGEXP \"".static::GSM_7BIT_CHARS."\",
                                  IF(
                                      CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::UNICODE_SINGLE_SMS.",
                                      1,
                                      CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::UNICODE_MULTIPLE_SMS.")
                                  ),
                                  IF(
                                      CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::GSM_7BIT_SINGLE_SMS.",
                                      1,
                                      CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::GSM_7BIT_MULTIPLE_SMS.")
                                  )
                              ) AS MESSAGE_COUNT
                              FROM ".DB_SMS_API_V2.".USER_MESSAGE_STATUS
                              WHERE MESSAGE_STATUS NOT IN ({$this->unchargedDeliveryStatus})
                                    AND USER_ID_NUMBER {$usersClause}
                    ) USER_MESSAGE_STATUS
                      WHERE USER_MESSAGE_STATUS.RECEIVE_DATETIME > '{$this->firstDateOfMonth}'
                            {$endDateQuery}
                    ";
        }
        return collect(DB::select($query))->first() ?: 0;
    }

    /**
     * Get apllied price from pricing list by given traffic
     *
     * @param   Array   $rules      pricing list
     * @param   Int     $traffic    Total traffic
     * @return  Array               applied Price
     */
    private function getTieringPriceByTraffic(&$rules, $traffic)
    {
        foreach($rules as &$rule) {
            $min = is_numeric($rule->SMS_COUNT_FROM)  ? (int)$rule->SMS_COUNT_FROM  : 0;
            $max = is_numeric($rule->SMS_COUNT_UP_TO) ? (int)$rule->SMS_COUNT_UP_TO : PHP_INT_MAX;
            if(($min <= $traffic) && ($traffic <= $max)) {
                return [$rule];
            }
        }
        return [['PER_SMS_PRICE' => 0]];
    }

    /**
     * Get Count Message
     * could get by one or several USER_ID (TIERING_GROUP or REPORT_GROUP)                                  <br />
     * @return  Array                               2D Array [[                                                 <br />
     *                                                 'MESSAGE_COUNT'                    <br />
     *                                              ]]
     */
    public function countMessage($userId, $startDateTime, $endDateTime)
    {
        $start = '5OTP'.substr($startDateTime, 0,7)."%";
        $end = '5OTP'.substr($endDateTime, 0,7)."%";

        $userIdClause = is_array($userId) ? ' IN ('.implode(',', $userId).')' : ' = '.$userId;

        $message  =  ' SELECT USER_MESSAGE_STATUS.MESSAGE_COUNT'
                    . ' FROM  (SELECT MESSAGE_ID,'
                            . 'STR_TO_DATE(SUBSTRING(MESSAGE_ID, 5, 19), \'%Y-%m-%d %H:%i:%s\') AS RECEIVE_DATETIME,'
                            . 'USER_ID_NUMBER,'
                            . "IF(
                                MESSAGE_CONTENT REGEXP \"".static::GSM_7BIT_CHARS."\",
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::UNICODE_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::UNICODE_MULTIPLE_SMS.")
                                ),
                                IF(
                                    CHAR_LENGTH(MESSAGE_CONTENT) <= ".static::GSM_7BIT_SINGLE_SMS.",
                                    1,
                                    CEILING(CHAR_LENGTH(MESSAGE_CONTENT) / ".static::GSM_7BIT_MULTIPLE_SMS.")
                                )
                            ) AS MESSAGE_COUNT"
                    . ' FROM '. DB_SMS_API_V2 . '.USER_MESSAGE_STATUS WHERE USER_ID_NUMBER '.$userIdClause.' AND MESSAGE_ID like "'.$start.'" OR MESSAGE_ID like "'.$end.'" ) USER_MESSAGE_STATUS '
                    . ' WHERE     USER_MESSAGE_STATUS.USER_ID_NUMBER '.$userIdClause
                    . '           AND USER_MESSAGE_STATUS.RECEIVE_DATETIME >  \''.$startDateTime.'\' '
                    . '           AND USER_MESSAGE_STATUS.RECEIVE_DATETIME < \''.$endDateTime  .'\' '
                    . ' ORDER BY USER_MESSAGE_STATUS.MESSAGE_ID ASC ';

        $messages = DB::select($message);
        return $messages;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Generate Last month report
        (new ApiReports(
            date('Y', strtotime('first day of last month')),
            date('m', strtotime('first day of last month')),
            true
        ))->generate();

        // Generate current month report
        (new ApiReports(
                date('Y',strtotime('now')),
                date('m',strtotime('now')),
                true
            ))->generate();

        /**
        * Clear archieved reports & invoices with years - 1
        */

        $dirnameReport = public_path().'/archive/reports';
        $dirnameInvoice = public_path().'/archive/invoices';

        $month = date('m', strtotime('now'));
        $year = date ('Y', strtotime('now'));

        for($i = 2016; $i <= (int)$year - 1 ; $i++){
            if(is_dir($dirnameReport.'/'.$i) or is_dir($dirnameInvoice.'/'.$i)){
                if(File::isDirectory($dirnameReport.'/'.$i)){
                    rmdir($dirnameReport.'/'.$i);
                }

                if(File::isDirectory($dirnameInvoice.'/'.$i)){
                    rmdir($dirnameInvoice.'/'.$i);
                }
            }
        }

        /**
         * Clear archieved reports & invoices with month - 3
         */
        for($i = 1 ; $i <= (int)$month - 3; $i++){
            if(is_dir(dirname($dirnameReport.'/'.$year.'/'.'0'.(string)$i)) or is_dir(dirname($dirnameInvoice.'/'.$year.'/'.'0'.(string)$i))){
                if(File::isDirectory($dirnameReport.'/'.$year.'/'.'0'.(string)$i)){
                    rmdir($dirnameReport.'/'.$year.'/'.'0'.(string)$i);
                }

                if(File::isDirectory($dirnameInvoice.'/'.$year.'/'.'0'.(string)$i)){
                    rmdir($dirnameInvoice.'/'.$year.'/'.'0'.(string)$i);
                }
            }
        }
    }

    /**
     * Generate Api Report
     */
    public function generate()
    {
        echo "\033[1;32m-----------------------------[START GENERATE REPORT ".$this->periodSuffix.']---------------------------'.PHP_EOL;
        echo "\033[1;97mPERIOD\t\t\tSTATUS\t\tPROFILE ID\tTYPE\t\t\tREPORT NAME".PHP_EOL;

        $scriptRunningTime = $this->getMicroTime();

        if ($this->lastFinalStatusDate !== false)
        {
            $statusArray = array_map(
                function($item) {return "'$item'";},array_keys($this->getDeliveryStatus(self::SMS_STATUS_UNCHARGED))
            );

            $this->unchargedDeliveryStatus  = implode(',', $statusArray);

            $users                          = $this->getUserDetailApiReport();

            $excludedUser                   = [];

            /**
             * Start Generate user's report
             */

            foreach($users as &$user)
            {
                $fileName              = $user->USER_NAME;
                $userName              = $user->USER_NAME;
                $userId                = $user->USER_ID;
                $userBillingProfileId  = $user->BILLING_PROFILE_ID;
                $userTieringGroupId    = $user->BILLING_TIERING_GROUP_ID;
                $userTieringGroup      = null;
                $userReportGroupId     = $user->BILLING_REPORT_GROUP_ID;
                $userReportGroup       = null;
                $getByGroup            = false;
                $hasEmptyStatus        = false;
                $userReportGroupDates  = [];
                $counter               = 0;

                $this->messageOperatorDistinct = [];
                $this->messagePriceDistinct = [];
                $this->messageTotalQuantityCharged = [];
                $this->messageTotalQuantityUncharged = [];
                $this->messageSinglePrice = [];

                if (is_null($userId) || in_array($userId, $excludedUser))
                {
                    continue;
                }

                /* =======================================
                 *  Get User billing information
                 * ======================================= */
                $this->log->debug('Validate Billing Profile for User : "'.$userName.'" ('.$userId.')');
                $this->log->debug('Check user billing information');

                if (is_null($userBillingProfileId))
                {
                    $this->log->warning('User '.$userName.' was not assigned to any Billing Profile.');
                    $this->log->info('Skip generate report for user '.$userName);

                    continue;
                }

                $this->log->debug('Get user billing information');

                $userBillingProfile    = $this->loadBillingProfileCache($userBillingProfileId);

                $this->log->debug('Check user billing detail');

                if (is_null($userBillingProfile))
                {
                    $this->log->warning('User '.$userName.' was not assigned to any Billing Profile.');
                    $this->log->warning('User '.$userName.' was assigned to Billing Profile '.$userBillingProfileId.' but not found on any detail on '.DB_BILL_PRICELIST.'.BILLING_PROFILE');
                    $this->log->info('Skip generate report for user '.$userName);

                    continue;
                }
                /* =======================================
                 *  End Of Get User billing information
                 * ======================================= */


                /*=======================================
                 * Get Report Group information
                 *=======================================*/
                $this->log->debug('Check user report group');
                if (!empty($userReportGroupId))
                {

                   $this->log->debug('Get user report group information '.$userReportGroupId);

                    $userReportGroup = $this->loadReportGroupCache($userReportGroupId);

                    if (is_null($userReportGroup))
                    {
                        $this->log->warning('User '.$userName.' was assigned to Billing Profile "'.$userReportGroupId.'" but not found billing profile detail on '.DB_BILL_PRICELIST.'.BILLING_REPORT_GROUP');

                        $this->log->info('Skip generate report for user '.$userName);

                        continue;
                    }

                    $getByGroup   = true;
                    $fileName     = $userReportGroup->NAME;

                    $this->log->debug('get list of user on report group '.$fileName);

                    $userGroups             = $this->getReportGroupUserList($userReportGroupId);

                    $userId                 = array_merge([$userId], array_column($userGroups, 'USER_ID'));
                    $excludedUser           = array_merge($excludedUser, $userId);
                    $userReportGroupDates   = array_fill_keys($userId, $this->firstDateOfMonth);

                    $loads = sys_getloadavg();
                    $core_nums = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
                    $load = round($loads[0]/($core_nums + 1)*100, 2);
                }


                /* =======================================
                 *  End of Get report Group information
                 * ======================================= */

                /* =======================================
                 * Checking the report file are already
                 * exist or not for the last month report
                 * ======================================= */

                if (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_TIERING_OPERATOR_BASE){
                    $tab = " \t";
                }else if (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_TIERING_BASE){
                    $tab = " \t\t";
                }else if (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_OPERATOR_BASE){
                    $tab = " \t\t";
                }

                if ($this->currentMonth === false && $this->isReportPackageExists($fileName))
                {
                    $this->log->debug('Report file already exists for '. $fileName);
                    echo "\033[1;31m".$this->year.'-'.$this->month.'-'.date('d').' '.date("H:i:s")."\tSkipped  \t".$userBillingProfileId."\t\t".$userBillingProfile->BILLING_TYPE.$tab.$fileName.PHP_EOL;
                    continue;
                }

                /* =============================================================
                 *  Start get User messages and insert into report file
                 * ============================================================= */
                $this->log->debug('Checking new message for user '.$userName);

                $hasNewMessage = !empty(
                                    $getByGroup
                                    ? $this->getGroupMessageStatus($userReportGroupDates, $this->lastFinalStatusDate, 1, 0)
                                    : $this->getUserMessageStatus ($userId, $this->firstDateOfMonth, $this->lastFinalStatusDate, 1, 0)
                                );

                if ($hasNewMessage)
                {
                    echo "\033[1;32m".$this->year.'-'.$this->month.'-'.date('d').' '.date("H:i:s")."\tGenerate \t".$userBillingProfileId."\t\t".$userBillingProfile->BILLING_TYPE.$tab.$fileName.PHP_EOL;

                    if (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_OPERATOR_BASE)
                    {

                        $operatorPrice  = &$userBillingProfile->PRICING;
                        $operatorPrefix = &$userBillingProfile->PREFIX;

                        /**
                         * OPERATOR BASE - Final status messages
                         */

                        $this->createReportFile($fileName);

                        $tmpCache = 0;
                        do {
                            $this->log->debug('Get '.$fileName.' messages from '.$counter .' to '.($counter + REPORT_PER_BATCH_SIZE));

                            $messages = $getByGroup
                                            ? $this->getGroupMessageStatus($userReportGroupDates,      $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter)
                                            : $this->getUserMessageStatus ($userId, $this->firstDateOfMonth, $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter);

                            $tmpCache++;

                            $messagesTotal = is_array($messages) ? count($messages) : 0;

                            if ($messagesTotal > 0)
                            {

                                $this->formatMessages(self::BILLING_OPERATOR_BASE, $messages, $operatorPrice, $operatorPrefix);
                                $counter      += REPORT_PER_BATCH_SIZE;

                            }
                        } while ($messagesTotal > 0 && $messagesTotal === REPORT_PER_BATCH_SIZE);
                    }
                    elseif (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_TIERING_BASE)
                    {

                        /**
                         * Get TIERING Traffics
                         */
                        if (is_null($userTieringGroupId))
                        {
                            $finalTieringTraffic    = $this->getTieringTraffic($userId);
                        }
                        else
                        {
                            $tieringGroupUserList   = $this->getTieringGroupUserList($userTieringGroupId);
                            $finalTieringTraffic    = $this->getTieringTraffic($tieringGroupUserList);
                        }

                        $finalTieringTraffic = $finalTieringTraffic->FINAL_TIERING_TRAFFIC;

                        $this->log->debug('Got '.$fileName.' tiering traffic on '.$this->year.'-'.$this->month.' with status final: '.$finalTieringTraffic);

                        $pricing        = $this->getTieringDetail($userBillingProfileId);
                        $finalPrice     = $this->getTieringPriceByTraffic($userBillingProfile->PRICING, $finalTieringTraffic);

                        $operatorPrefix = $this->getOperatorDialPrefix(self::OPERATOR_INDONESIA);

                        $this->log->debug('Applied Price: Final = '.json_encode($finalPrice));
                        $this->createReportFile($fileName);

                        /**
                         * TIERING BASE - Dump Final SMS
                         */
                        do {

                            $this->log->debug('Get '.$fileName.' messages from '.$counter .' to '.($counter + REPORT_PER_BATCH_SIZE));

                            $messages = $getByGroup ? $this->getGroupMessageStatus($userReportGroupDates, $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter) : $this->getUserMessageStatus ($userId, $this->firstDateOfMonth, $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter);

                            $messagesTotal = is_array($messages) ? count($messages) : 0;

                            if ($messagesTotal > 0)
                            {

                                $this->formatMessages(self::BILLING_TIERING_BASE, $messages, $finalPrice, $operatorPrefix);
                                $counter      += REPORT_PER_BATCH_SIZE;

                            }
                        } while ($messagesTotal > 0 && $messagesTotal === REPORT_PER_BATCH_SIZE);
                    }
                    elseif (strtoupper($userBillingProfile->BILLING_TYPE) == self::BILLING_TIERING_OPERATOR_BASE)
                    {
                        /**
                         * Get TIERING OPERATOR Traffics
                         */
                        if (is_null($userTieringGroupId)){$finalTieringTraffic    = $this->getTieringTraffic($userId);}
                        else
                        {

                            $tieringGroupUserList   = $this->getTieringGroupUserList($userTieringGroupId);
                            $finalTieringTraffic    = $this->getTieringTraffic($tieringGroupUserList);

                        }

                        $finalTieringTraffic = $finalTieringTraffic->FINAL_TIERING_TRAFFIC;

                        $this->log->debug('Got '.$fileName.' tiering traffic on '.$this->year.'-'.$this->month.' with status final: '.$finalTieringTraffic);

                        $finalPrice     = $this->getTieringPriceByTraffic($userBillingProfile->PRICING, $finalTieringTraffic);
                        $operatorPrefix = $this->getOperatorDialPrefix(self::OPERATOR_INDONESIA);

                        $this->log->debug('Applied Price: Final = '.json_encode($finalPrice));
                        $this->createReportFile($fileName);


                        /**
                         * TIERING BASE - Dump Final SMS
                         */

                        $messagesSMSCount = $this->countMessage ($userId, $this->firstDateOfMonth, $this->lastFinalStatusDate);
                        $totalSmsCount = 0;

                        foreach($messagesSMSCount as &$message)
                        {
                            $totalSmsCount = $totalSmsCount + intval($message->MESSAGE_COUNT);
                        }
                        $tmpCache = 0;

                        do {
                            $this->log->debug('Get '.$fileName.' messages from '.$counter .' to '.($counter + REPORT_PER_BATCH_SIZE));

                            $messages = $getByGroup ? $this->getGroupMessageStatus($userReportGroupDates, $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter) : $this->getUserMessageStatus ($userId, $this->firstDateOfMonth, $this->lastFinalStatusDate, REPORT_PER_BATCH_SIZE, $counter);

                            $messagesTotal = is_array($messages) ? count($messages) : 0;

                            $queryRangeID = ' SELECT   '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_TIERING_OPERATOR_ID'
                                .' FROM     '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR'
                                .' WHERE '.$totalSmsCount.' BETWEEN '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.SMS_COUNT_FROM AND '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.SMS_COUNT_UP_TO AND '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_ID = '.$userBillingProfileId.' LIMIT 1';

                            $findTieringOperatorRangeID = DB::select($queryRangeID);

                            if ($findTieringOperatorRangeID == []){
                                $queryRangeID = ' SELECT   '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_TIERING_OPERATOR_ID'
                                .' FROM     '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR'
                                .' WHERE '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.SMS_COUNT_UP_TO = "MAX" AND '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_ID = '.$userBillingProfileId.' ORDER BY '.DB_BILL_PRICELIST.'.BILLING_PROFILE_TIERING_OPERATOR.BILLING_PROFILE_TIERING_OPERATOR_ID DESC LIMIT 1';

                                $findTieringOperatorRangeID = DB::select($queryRangeID);
                            }

                            $findTieringOperatorRangeID = $findTieringOperatorRangeID[0]->BILLING_PROFILE_TIERING_OPERATOR_ID;

                            if ($messagesTotal > 0 && !empty($findTieringOperatorRangeID))
                            {
                                $query = ' SELECT '.DB_BILL_PRICELIST.'.BILLING_PROFILE_OPERATOR_PRICE.OP_ID,'.DB_BILL_PRICELIST.'.BILLING_PROFILE_OPERATOR_PRICE.PER_SMS_PRICE'
                                .' FROM '.DB_BILL_PRICELIST.'.BILLING_PROFILE_OPERATOR_PRICE'
                                .' WHERE '.DB_BILL_PRICELIST.'.BILLING_PROFILE_OPERATOR_PRICE.BILLING_PROFILE_TIERING_OPERATOR_ID = '.$findTieringOperatorRangeID;

                                $OP_CHECK = DB::select($query);

                                $opArray = [];
                                $priceArray = [];

                                foreach ($OP_CHECK as $operator)
                                {
                                    $opArray[] = $operator->OP_ID;
                                    $priceArray[] = $operator->PER_SMS_PRICE;
                                }

                                $this->formatMessagesTieringOperator(self::BILLING_TIERING_OPERATOR_BASE, $messages, $finalPrice, $operatorPrefix, $findTieringOperatorRangeID, $opArray, $priceArray);
                                $counter      += REPORT_PER_BATCH_SIZE;

                            }
                        } while ($messagesTotal > 0 && $messagesTotal === REPORT_PER_BATCH_SIZE);

                    }

                    if (in_array(
                            strtoupper($userBillingProfile->BILLING_TYPE),
                            [self::BILLING_OPERATOR_BASE, self::BILLING_TIERING_BASE, self::BILLING_TIERING_OPERATOR_BASE]
                        )
                    ) {

                        $this->saveReportFile();

                        $this->saveSummary($fileName, $userId, $userBillingProfile);

                        $this->createReportPackage($fileName);
                    }
                }
                else
                {
                    echo "\033[1;31m".$this->year.'-'.$this->month.'-'.date('d').' '.date("H:i:s")."\tSkipped  \t".$userBillingProfileId."\t\t".$userBillingProfile->BILLING_TYPE.$tab.$fileName.PHP_EOL;
                    $this->log->info('Skip generate report for '.$fileName.', No new messages found.');
                }
                $this->createReportPackage();

                unset($filename);
                unset($userName);
                unset($userId);
                unset($userBillingProfileId);
                unset($userTieringGroupId);
                unset($userTieringGroup);
                unset($userReportGroupId);
                unset($userReportGroup);
                unset($getByGroup);
                unset($hasEmptyStatus);
                unset($userReportGroupDates);
                unset($counter);
                unset($messagesTotal);
                unset($messages);
                unset($opArray);
                unset($priceArray);
                unset($findTieringOperatorRangeID);
                unset($operatorPrefix);
                unset($finalPrice);
                unset($OP_CHECK);
                unset($totalSmsCount);
                unset($finalTieringTraffic);
                unset($tieringGroupUserList);
                unset($finalPrice);
                unset($messagesSMSCount);
                unset($totalSmsCount);

                unset($this->messageOperatorDistinct);
                unset($this->messagePriceDistinct);
                unset($this->messageTotalQuantityCharged);
                unset($this->messageTotalQuantityUncharged);
                unset($this->messageSinglePrice);

            }

            $this->log->info('Finish generate report for '.$this->year.'-'.$this->month.' period');

            // Calucate peformance for generating

            $temp = count($this->queryHistory);

            if ($temp <= 0){
                $divided = 1;
            }else{
                $divided = $temp;
            }

            $scriptRunningTime = number_format($this->getMicroTime() - $scriptRunningTime, 2).' sec';
            $averageMemory     = number_format(array_sum(array_column($this->queryHistory,'currentMemoryUsed'))  / $divided, 2).' MB';

            $totalQueryRecord  = number_format(array_sum(array_column($this->queryHistory,'totalRecords')));
            $averageRecords    = number_format(array_sum(array_column($this->queryHistory,'totalRecords'))       / $divided, 2);

            $totalQueryTime    = number_format(array_sum(array_column($this->queryHistory,'executionTime')), 2).' sec';
            $averageExecTime   = number_format(array_sum(array_column($this->queryHistory,'executionTime'))      / $divided, 2).' sec';

            $this->log->info("Peformance:"
                        ."\t TotalQueryRecords: "   .$totalQueryRecord
                        ."\t AverageQueryRecords: " .$averageRecords
                        ."\t TotalQueryTime: "      .$totalQueryTime
                        ."\t AverageQueryTime: "    .$averageExecTime
                        ."\t AverageMemoryUsage: "  .$averageMemory
                        ."\t runningTime: "         .$scriptRunningTime
                );

            echo "\033[1;32m------------------------------[END GENERATE REPORT ".$this->periodSuffix.']----------------------------'.PHP_EOL;
            echo "\033[1;32m-------------------------------[ALL MESSAGES DONE GENERATED]-----------------------------".PHP_EOL;
            echo "\n";
        }
        else {
            $this->log->info('Skip generate report, there is no final status message.');
        }
    }

    /**
     * Get Delivery status from BILL_U_MESSAGE.DELIVERY_STATUS
     *
     * @return  Array   2D Array [['ERROR_CODE', 'STATUS']]
     */
    public function getDeliveryStatus($statusType = '') {

        $status = [];

        $select = 'SELECT DASHBOARD_STATUS, ERROR_CODE FROM '.DB_BILL_U_MESSAGE.'.DELIVERY_STATUS';

        $deliveryStatus = DB::select($select);

        foreach ($deliveryStatus as &$delivery) {
            if($delivery->DASHBOARD_STATUS=='Delivered'){
                $status[$delivery->ERROR_CODE] = self::SMS_STATUS_DELIVERED;
            }
            else if($delivery->DASHBOARD_STATUS=='Rejected'){
                $status[$delivery->ERROR_CODE] = self::SMS_STATUS_UNDELIVERED;
            }
            else {
                $status[$delivery->ERROR_CODE] = self::SMS_STATUS_UNDELIVERED_CHARGED;
            }
            // $status[$delivery->ERROR_CODE] = !$delivery->DASHBOARD_STATUS == 'Rejected'
            //                                     ? $delivery->DASHBOARD_STATUS == 'Delivered'
            //                                         ? self::SMS_STATUS_DELIVERED
            //                                         : self::SMS_STATUS_UNDELIVERED_CHARGED
            //                                     : self::SMS_STATUS_UNDELIVERED;
        }

        return $status;
    }


    /**
     * Convert DateTime from server timezone to client timezone
     *
     * @param  String $value
     * @return String
     */
    public function clientTimeZone($value, $format='Y-m-d H:i:s'){

        $value = $this->parseDatetimeInput($value);

        // Create datetime based on input value (GMT)
        $date = new \DateTime($value, new \DateTimeZone($this->timezoneServer));

        // Return datetime corrected for client's timezone (GMT+7)
        return $date->setTimezone(new \DateTimeZone($this->timezoneClient))->format($format);
    }


    /**
     * Convert DateTime from server timezone to client timezone
     *
     * @param  String $value
     * @return String
     */
    public function serverTimeZone($value, $format='Y-m-d H:i:s'){

        $value = $this->parseDatetimeInput($value, true);

        $date = new \DateTime($value, new \DateTimeZone($this->timezoneClient));

        return $date->setTimezone(new \DateTimeZone($this->timezoneServer))->format($format);
    }


    /**
     * Parse the dateTime input value
     * numeric input value as a timestamp value and will convert to default format time
     * incorrect value will set to client timezone if parameter isServerTimeZone true
     *
     * @param mixed $value
     * @param boolean $isServerTimeZone
     * @return String
     */
    private function parseDatetimeInput($value, $isServerTimeZone = false){
        // If input value is a unix timestamp
        if (is_numeric($value)) {
            $value = date('Y-m-d H:i:s', $value);
        }

        // If input value is not a correct datetime format
        if(!strtotime($value)){
            $currentTimestamp = $isServerTimeZone ? strtotime($this->timezoneClient.' hours') : strtotime('now');
            $value = date('Y-m-d H:i:s', $currentTimestamp);
        }

        return $value;
    }

    /**
     * Get Current time in second.micro_second
     *
     * @return  Float
     */
    public function getMicroTime() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
}
