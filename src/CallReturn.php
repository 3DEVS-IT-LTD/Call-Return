<?php


namespace ThreeDevs\CallReturn;
/**
 * A handy object that can be used as common return object for function/method calls
 * The object has status and status code, it can be used as common object for API call returns too
 * It also has success/error messages array and success/error code array, so you can either use text messages to return
 * or codes to return for success and error condition. or you can use both code and string messages
 * Success/error codes allows your application to send codes to third party application or (your own) in return that
 * they can use to write their own messages or take action based on the level of error code
 *
 * This class is developed and offered by 3-devs IT Ltd.
 * https://3-devs.com
 *
 * PHP version 7.4
 *
 * @category    library
 * @package     three_devs/call_return
 * @author      Tanmay Chakrabarty <tanmay@3-devs.com>
 * @owner       3DEVs IT LTD <info@3-devs.com>
 * @access      public
 * @version     2.0.0
 * @license     https://www.apache.org/licenses/LICENSE-2.0.html
 * @link        https://github.com/3DEVS-IT-LTD/Call-Return
 */

class CallReturn
{
    private ?string $status = 'success';
    private int $status_code = 200;
    private array $error_messages = [];
    private array $error_codes = [];
    private array $success_messages = [];
    private array $success_codes = [];
    private $data = null;
    private array $key_value_data = [];

    public function __construct()
    {
    }

    /**
     * This method is privately called when add_error() method is called
     * It clears everything related to success and turn into error
     * such as clears all success messages and codes, sets status to error and status code to 400
     */
    private function eventErrorAdded()
    {
        $this->success_messages = [];
        $this->success_codes = [];
        $this->data = null;
        $this->status = 'error';
        $this->status_code = 400;
    }

    /**
     * This method is privately called when add_success() method is called
     * It clears everything related to error and turn into success
     * such as clears all error messages and codes, sets status to success and status code to 200
     */
    private function eventSuccessAdded()
    {
        $this->error_messages = [];
        $this->error_codes = [];
        $this->data = null;
        $this->status = 'success';
        $this->status_code = 200;
    }

    /**
     * Call this method to add errors
     * it clears everything related to success, if any
     * It doesn't clear previously added error messages or codes, if any, instead pushes new messages to error array
     * @param string[]|string $err send a single error message or an array of strings as error message
     * @param ?int[]|?int $codes codes for error messages in array of ints or a single int
     * @return CallReturn $this
     */
    public function add_error($err, $codes = null): CallReturn
    {
        $this->eventErrorAdded();

        if (is_array($err)) $this->error_messages = array_merge($this->error_messages, $err);
        else $this->error_messages[] = $err;

        if($codes){
            if (is_array($codes)) $this->error_codes = array_merge($this->error_codes, $codes);
            else $this->error_codes[] = $codes;
        }

        return $this;
    }

    /**
     * Call this method to add success
     * it clears everything related to error, if any
     * It doesn't clear previously added success messages or codes, if any, instead pushes new messages to success array
     * However, the data you provide will replace previously added data, if not null
     * @param ?mixed $data
     * @param ?string[]|?string $message
     * @param ?int[]|?int $code
     * @return CallReturn $this
     */
    public function add_success($data = null, $message = null, $code = null): CallReturn
    {
        $this->eventSuccessAdded();

        if(!is_null($data))
            $this->data = $data;

        if ($message) {
            if (is_array($message)) $this->success_messages = array_merge($this->success_messages, $message);
            else $this->success_messages[] = $message;
        }
        if($code){
            if (is_array($code)) $this->success_codes = array_merge($this->success_codes, $code);
            else $this->success_codes[] = $code;
        }

        return $this;
    }

    /**
     * @param mixed $data
     * @return CallReturn $this
     */
    public function add_data($data): CallReturn
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return CallReturn $this
     */
    public function clear_message(): CallReturn
    {
        $this->error_messages = [];
        $this->error_codes = [];
        $this->success_messages = [];
        $this->success_codes = [];

        return $this;
    }

    /**
     * @return CallReturn $this
     */
    public function clear_data(): CallReturn
    {
        $this->data = null;

        return $this;
    }

    /**
     * @param string[]|string $msg
     * @param ?int[]|?int $codes
     * @return CallReturn$this
     */
    public function add_message($msg, $codes = null): CallReturn
    {
        if ($this->status == 'success')
            $this->add_success($this->get_data(), $msg, $codes);
        else
            $this->add_error($msg, $codes);

        return $this;
    }

    /**
     * @return bool
     */
    public function is_error(): bool
    {
        return ($this->status == 'error');
    }

    /**
     * @return bool
     */
    public function is_success(): bool
    {
        return ($this->status == 'success');
    }

    /**
     * @return string[]
     */
    public function get_message(): array
    {
        return ($this->status == 'success' ? $this->get_success_message() : $this->get_error_message());
    }

    /**
     * @return string[]
     */
    public function get_success_message()
    {
        return $this->success_messages;
    }
    /**
     * @return string[]
     */
    public function get_error_message()
    {
        return $this->error_messages;
    }
    /**
     * @return mixed
     */
    public function get_data()
    {
        return $this->data;
    }

    /**
     * @param int $code
     * @return CallReturn $this
     */
    public function setStatusCode(int $code)
    {
        $this->status_code = $code;
        return $this;
    }

    /**
     * @return int current status code
     */
    public function getStatusCode()
    {
        if($this->status_code == 200 && $this->is_error())
            $this->status_code = 400;

        return $this->status_code;
    }

    /**
     * @return array contains all error codes in an numeric array
     */
    public function get_error_codes()
    {
        return $this->error_codes;
    }

    /**
     * @return array contains all success codes in an numeric array
     */
    public function get_success_codes()
    {
        return $this->success_codes;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getKeyValueData(string $key = null): mixed
    {
        return is_null($key) ? $this->key_value_data : ($this->key_value_data[$key] ?? null);
    }


    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setKeyValueData(string $key, mixed $value): CallReturn
    {
        $this->key_value_data[$key] = $value;
        return $this;
    }



    /**
     * @return array [
     *  'status_code' => 200,
     *  'status' => 'success',
     *  'error' => [],
     *  'success' => [],
     *  'data' => null
     *  ]
     */
    public function get_in_array(): array
    {
        $ret = [];
        $ret['status_code'] = (int)$this->getStatusCode();
        $ret['status'] = $this->is_error() ? 'error' : 'success';
        $ret['error'] = is_null($this->get_error_message()) ? [] : $this->get_error_message();
        $ret['error_code'] = is_null($this->get_error_codes()) ? [] : $this->get_error_codes();
        $ret['success'] = is_null($this->get_success_message()) ? [] : $this->get_success_message();
        $ret['success_code'] = is_null($this->get_success_codes()) ? [] : $this->get_success_codes();
        $ret['data'] = is_null($this->get_data()) ? [] : $this->get_data();

        return array_merge($this->key_value_data, $ret);
    }

    public function headerJson()
    {
        header('Content-Type: application/json');
        http_response_code($this->getStatusCode());
        echo json_encode($this->get_in_array());
        exit();
    }

    public function headerJsonWithError($error)
    {
        $this->add_error($error);
        $this->headerJson();
    }

    public function headerJsonWithSuccess($data = null, $message = null)
    {
        $this->add_success($data, $message);
        $this->headerJson();
    }
}