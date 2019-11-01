<?php
/**
 * Class RedirectionsTester
 * @author Francesco Giorgio <francesco@giorgio.dev>
 * @license MIT
 * @version 1.0.0
 */
class RedirectionsTester {

    /**
     * @var resource[] $curl Array containing cURL handles
     * @var string $host The domain name to test
     * @var null|string $redirection_host The (optional) domain name to which requests are redirected to
     */
    private $curl=[], $host, $redirection_host;

    /**
     * @var string[] $results Array containing the results of the tests
     * @var string[] $errors Array containing the errors occurred during the tests
     */
    public $results=[], $errors=[];

    /**
     * Configuration of the HTTPS protocol
     *
     * Possible values:
     * - FORCED - HTTPS protocol is always forced
     * - ENABLED - HTTPS protocol is enabled but not forced
     * - DISABLED - HTTPS protocol is disabled
     *
     * @var string $config_https
     */
    private $config_https = 'FORCED';

    /**
     * Configuration of the WWW prefix
     *
     * Possible values:
     * - FORCED_WITHOUT - The server removes the www prefix if present
     * - FORCED_WITH - The server adds the www prefix if not present
     * - NOT_FORCED - The server doesn't apply any rule to www prefix
     *
     * @var string $config_www
     */
    private $config_www = 'FORCED_WITH';

    /**
     * Configuration of the index.* hiding rule
     *
     * Possible values:
     * - ALWAYS - The server removes index.* file name if present
     * - NEVER - The server doesn't remove index.* file name if present
     *
     * @var string $config_index_rewrite
     * @see RedirectionsTester::$config_index_rewrite_ext for index.* extensions which this rule is applied to
     */
    private $config_index_rewrite = 'ALWAYS';

    /**
     * @var string[] $config_index_rewrite_ext Array containing the extensions which index.* hiding rule is applied to
     * @see RedirectionsTester::$config_index_rewrite to check for index.* hiding rule
     */
    private $config_index_rewrite_ext = [];

    /**
     * Configuration of trail slash policy
     *
     * Possible values:
     * - ONLY_REAL_DIRS - The server adds a trail slash only if the URL points to a real directory, otherwise it does nothing
     * - IF_NOT_REAL_FILE - The server adds a trail slash everytime URL points to something that's not a real file
     * - NEVER_FORCED - The server doesn't apply any rule to trail slash
     * - ALWAYS_FORCED - The server adds always a trail slash to URL (every URL is considered as a directory)
     *
     * @var string $config_trail_slash
     */
    private $config_trail_slash = 'NEVER_FORCED';

    /**
     * Configuration of the path remove rule
     *
     * Possible values:
     * - NEVER - The server never removes the path in the URL
     * - ALWAYS - The server always removes the path in the URL
     * - ONLY_DOMAIN_REDIRECT - The server removes the path in the URL only on domain redirection
     *
     * @var string $config_path_remove
     */
    private $config_path_remove = 'NEVER';

    /**
     * Configuration of the queries remove rule
     *
     * Possible values:
     * - NEVER - The server never removes queries in the URL
     * - ALWAYS - The server always removes queries in the URL
     * - ONLY_DOMAIN_REDIRECT - The server removes queries in the URL only on domain redirection
     *
     * @var string $config_queries_remove
     */
    private $config_queries_remove = 'NEVER';

    /**
     * @var array $configurations_allowed Array containing allowed configurations values
     * @see RedirectionsTester::getAllowedConfigs() to access this property publicly
     */
    const CONFIGURATIONS_ALLOWED = [
        'config_https'=>[
            [
                'label'=>'FORCED',
                'title'=>'Forced'
            ],[
                'label'=>'ENABLED',
                'title'=>'Enabled'
            ],[
                'label'=>'DISABLED',
                'title'=>'Disabled'
            ],
        ],
        'config_www'=>[
            [
                'label'=>'FORCED_WITHOUT',
                'title'=>'Forced Without'
            ],[
                'label'=>'FORCED_WITH',
                'title'=>'Forced With'
            ],[
                'label'=>'NOT_FORCED',
                'title'=>'Not Forced'
            ],
        ],
        'config_index_rewrite'=>[
            [
                'label'=>'ALWAYS',
                'title'=>'Always'
            ],[
                'label'=>'NEVER',
                'title'=>'Never'
            ],
        ],
        'config_trail_slash'=>[
            [
                'label'=>'ONLY_REAL_DIRS',
                'title'=>'Forced for real directories'
            ],[
                'label'=>'IF_NOT_REAL_FILE',
                'title'=>'Forced if not real file'
            ],[
                'label'=>'NEVER_FORCED',
                'title'=>'Never Forced'
            ],[
                'label'=>'ALWAYS_FORCED',
                'title'=>'Always Forced'
            ],
        ],
        'config_path_remove'=>[
            [
                'label'=>'NEVER',
                'title'=>'Never'
            ],[
                'label'=>'ALWAYS',
                'title'=>'Always'
            ],[
                'label'=>'ONLY_DOMAIN_REDIRECT',
                'title'=>'Only on domain redirection'
            ],
        ],
        'config_queries_remove'=>[
            [
                'label'=>'NEVER',
                'title'=>'Never'
            ],[
                'label'=>'ALWAYS',
                'title'=>'Always'
            ],[
                'label'=>'ONLY_DOMAIN_REDIRECT',
                'title'=>'Only on domain redirection'
            ],
        ],
    ];

    /**
     * @return array An array containing allowed configurations values
     */
    public static function getAllowedConfigs(){
        return self::CONFIGURATIONS_ALLOWED;
    }

    /**
     * Class constructor
     * @param string $host The domain name to test
     * @param array $configs Array containing class configurations
     * @param null|string $redirection_host The (optional) domain name to which requests are redirected to
     * @throws Exception if index_rewrite_ext configuration passed through constructor is not a string or array
     */
    function __construct(String $host, Array $configs, String $redirection_host=null) {
        $this->host = $host;
        $this->redirection_host = $redirection_host;
        foreach($configs as $property=>$config){
            $property = 'config_'.$property;
            if(property_exists($this, $property)){
                $this->$property = $config;
            }
        }
        // if property config_index_rewrite_ext is passed as string then explode them to array
        if(is_string($this->config_index_rewrite_ext)){
            $this->config_index_rewrite_ext=preg_split(
                "/[^a-zA-Z0-9]+/",
                $this->config_index_rewrite_ext,
                null,
                PREG_SPLIT_NO_EMPTY
            );
        }
        if(!is_array($this->config_index_rewrite_ext)){
            throw new Exception('index_rewrite_ext configuration must be an array or a string of separated extensions');
        }
    }

    /**
     * Check if a domain name is correct and without the www prefix.
     * @param String $domain The domain name to verify
     * @return false|string Return false if the domain name is not correct,
     * return the domain name itself without the www otherwise.
     */
    private function verifyDomain(String $domain){
        if(!preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/',$domain)){
            return false;
        }
        if(substr($domain,0,4)==='www.'){
            $domain=substr($domain,4);
        }
        return $domain;
    }

    /**
     * Run all tests
     * @return boolean True in case of success, False if there are some errors to show
     */
    public function run(){
        // Check if domain name and redirection domain name (if present) are correct
        $this->host=$this->verifyDomain($this->host);
        if(!$this->host){
            $this->errors[]='Wrong domain name';
        }
        if($this->redirection_host){
            $this->redirection_host=$this->verifyDomain($this->redirection_host);
            if(!$this->redirection_host){
                $this->errors[]='Wrong redirection domain name';
            }
        }
        // Check if all configurations are correct
        foreach(self::CONFIGURATIONS_ALLOWED as $config=>$values){
            if(!in_array($this->$config, array_column($values,'label'))){
                $this->errors[] = 'Value not allowed for '.substr($config,7).' configuration';
            }
        }
        if(count($this->config_index_rewrite_ext)===0){
            $this->errors[] = 'You must specify almost one extension for index.* hiding rule';
        }
        foreach($this->config_index_rewrite_ext as $extension){
            if(!is_string($extension) || !preg_match('/^[a-zA-Z0-9]+$/',$extension)){
                $this->errors[] = $extension.' is not a valid file extension';
            }
        }

        if(count($this->errors)>0){
            return false;
        }

        if($curlMulti=$this->buildTests()){
            do {
                $status = curl_multi_exec($curlMulti, $active); // Run tests
                if ($active) {
                    curl_multi_select($curlMulti);
                }
            } while ($active && $status == CURLM_OK);
            $this->parseTests();
        }else{
            $this->errors[] = 'Error during tests execution';
        }

        return true;
    }

    /**
     * Builds all tests as cURL handles
     * @return false|resource Return a multi cURL handle in case of success or false otherwise
     */
    private function buildTests(){
        $curlMulti = curl_multi_init();
        $path2test=[
            '',
            '/real_dir',
            '/real_dir/',
            '/not_real_dir',
            '/not_real_dir/',
            '/real_dir/real_file.txt',
            '/real_dir/real_file.txt/',
            '/real_dir/not_real_file.txt',
            '/real_dir/not_real_file.txt/'
        ];
        foreach($this->config_index_rewrite_ext as $extension){
            $path2test[]='/real_dir/index.'.$extension;
        }
        $i=0;
        foreach(['','?key=value'] as $queries_opt){
            foreach($path2test as $path){
                foreach (['','www.'] as $www){
                    foreach(['http://','https://'] as $protocol){
                        $this->curl[$i] = curl_init($protocol.$www.$this->host.$path.$queries_opt);
                        $this->results[$i]['test_url']=$protocol.$www.$this->host.$path.$queries_opt;
                        curl_setopt($this->curl[$i], CURLOPT_FOLLOWLOCATION, true); // Follow redirects
                        curl_setopt($this->curl[$i], CURLOPT_RETURNTRANSFER,true); // Return response as string
                        curl_setopt($this->curl[$i], CURLOPT_NOBODY, true); // Doesn't include response body
                        curl_setopt($this->curl[$i], CURLOPT_HEADER, true); // Include header in the output
                        curl_multi_add_handle($curlMulti,$this->curl[$i]);
                        $i++;
                    }
                }
            }
        }
        return $curlMulti;
    }

    /**
     * Fetches tests results
     */
    private function parseTests(){
        foreach($this->curl as $key=>$curlHandle){
            $final_url = curl_getinfo($curlHandle, CURLINFO_EFFECTIVE_URL);
            $http_code = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            $redirect_count = curl_getinfo($curlHandle, CURLINFO_REDIRECT_COUNT);
            $redirects_time = curl_getinfo($curlHandle, CURLINFO_REDIRECT_TIME);

            $header_size = curl_getinfo($curlHandle, CURLINFO_HEADER_SIZE);
            // Separate single headers and remove empty exploded elements
            $headers = array_filter(explode("\n\r\n",substr(curl_multi_getcontent($curlHandle), 0, $header_size)));

            $headersData=[];
            foreach($headers as $header){
                // Matches http response code
                preg_match('/^(HTTP\/)(.+)([0-9]{3})(.+)$/m',$header,$status_codes);
                // Matches location header for redirects
                preg_match('/^(Location: )(.+)$/mi',$header,$redirections);
                $headersData[]=[
                    'http_code'=>$status_codes[3],
                    'redirect_url'=>$redirections[2]];
            }

            $this->results[$key] = [
                'test_url'=>$this->results[$key][ 'test_url'],
                'final_url'=>$final_url,
                'final_code'=>$http_code,
                'intermediate_data'=>$headersData,
                'n_redirects'=>$redirect_count,
                'redirects_time'=>$redirects_time,
                'test_result'=>$this->getTestResult($this->results[$key][ 'test_url'], $final_url),
            ];
        }
    }

    /**
     * Determines if a test passes or fails
     * @param string $test_url The URL tested
     * @param string $final_url Final URL reached after redirection steps
     * @return boolean True if the test passes, False otherwise
     */
    private function getTestResult(string $test_url, string $final_url){
        $test_url_scheme = parse_url($test_url,PHP_URL_SCHEME);
        $test_url_host = parse_url($test_url,PHP_URL_HOST);
        $test_url_path = parse_url($test_url,PHP_URL_PATH);
        $test_url_query = parse_url($test_url,PHP_URL_QUERY);
        $final_url_scheme = parse_url($final_url,PHP_URL_SCHEME);
        $final_url_host = parse_url($final_url,PHP_URL_HOST);
        $final_url_path = parse_url($final_url,PHP_URL_PATH);
        $final_url_query = parse_url($final_url,PHP_URL_QUERY);
        if(substr($test_url_path,0,1)!=='/'){
            $test_url_path='/'.$test_url_path;
        }
        if(substr($final_url_path,0,1)!=='/'){
            $final_url_path='/'.$final_url_path;
        }

        // https checks
        if($this->config_https==='FORCED' && $final_url_scheme!=='https'){
            return false;
        }elseif($this->config_https==='DISABLED' && $final_url_scheme==='https'){
            return false;
        }elseif($this->config_https==='ENABLED' && $test_url_scheme!==$final_url_scheme){
            return false;
        }

        // www checks
        if($this->config_www==='FORCED_WITHOUT' && substr($final_url_host,0,4)==='www.'){
            return false;
        }elseif($this->config_www==='FORCED_WITH' && substr($final_url_host,0,4)!=='www.'){
            return false;
        }elseif($this->config_www==='NOT_FORCED' && (bool)strpos($test_url,'//www.')!==(bool)strpos($final_url,'//www.')){
            return false;
        }

        // domain redirection checks
        if($this->redirection_host && $this->verifyDomain($final_url_host)!==$this->redirection_host){
            return false;
        }elseif(!$this->redirection_host && $this->verifyDomain($test_url_host)!==$this->verifyDomain($final_url_host)){
            return false;
        }

        // rewrite index.* check
        if($this->config_index_rewrite==='ALWAYS'){
            foreach($this->config_index_rewrite_ext as $extension){
                if(
                    substr($test_url_path,-(7+strlen($extension)))==='/index.'.$extension
                    && substr($test_url_path,0,-(6+strlen($extension)))!==$final_url_path
                ){
                    return false;
                }
            }
        }elseif($this->config_index_rewrite==='NEVER'){
            foreach($this->config_index_rewrite_ext as $extension){
                if(
                    substr($test_url_path,-(7+strlen($extension)))==='/index.'.$extension
                    && $test_url_path!==$final_url_path
                ){
                    return false;
                }
            }
        }

        // trail slash check
        if(substr($test_url_path,0,16)==='/real_dir/index.' && $this->config_index_rewrite==='ALWAYS'){
            $temp_test_url_path = '/real_dir/';
        }else{
            $temp_test_url_path=$test_url_path;
        }
        if($this->config_trail_slash==='NEVER_FORCED' && substr($temp_test_url_path,-1)!==substr($final_url_path,-1)){
            return false;
        }elseif($this->config_trail_slash==='ALWAYS_FORCED' && substr($final_url_path,-1)!=='/'){
            return false;
        }elseif($this->config_trail_slash==='ONLY_REAL_DIRS'
            && (
                ($test_url_path==='/real_dir' && $final_url_path!=='/real_dir/')
                || ($test_url_path!=='/real_dir' && substr($temp_test_url_path,-1)!==substr($final_url_path,-1))
            )
        ){
            return false;
        }elseif($this->config_trail_slash==='IF_NOT_REAL_FILE'
            && (
                (
                    ($test_url_path==='/real_dir/real_file.txt' || substr($test_url_path,0,16)==='/real_dir/index.')
                    && substr($final_url_path,-1)==='/'
                )
                || (
                    $test_url_path!=='/real_dir/real_file.txt'
                    && substr($test_url_path,0,16)!=='/real_dir/index.'
                    && substr($final_url_path,-1)!=='/'
                )
            )
        ){
            return false;
        }

        // remove path check
        if($this->config_path_remove==='ALWAYS' && $final_url_path!=='/'){
            return false;
        }elseif($this->config_path_remove==='NEVER' && $test_url_path!=='/' && $final_url_path==='/'){
            return false;
        }elseif($this->config_path_remove==='ONLY_DOMAIN_REDIRECT'){
            if(!$this->redirection_host && $test_url_path!==$final_url_path){
                return false;
            }elseif($this->redirection_host && $final_url_path!=='/'){
                return false;
            }
        }

        // remove queries check
        if($this->config_queries_remove==='ALWAYS' && $final_url_query){
            return false;
        }elseif($this->config_queries_remove==='NEVER' && $test_url_query!==$final_url_query){
            return false;
        }elseif($this->config_queries_remove==='ONLY_DOMAIN_REDIRECT'){
            if(!$this->redirection_host && $test_url_query!==$final_url_query){
                return false;
            }elseif($this->redirection_host && $final_url_query){
                return false;
            }
        }

        return true;
    }
}
