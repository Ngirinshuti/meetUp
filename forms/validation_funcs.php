<?php

/**
 * The file for validating php fields
 *
 * PHP Version 8
 * 
 * @category Validation
 * @package  Unknown
 * @author   "ISHIMWE Valentin" <ishimwevalentin3@gmail.com>
 * @license  mitlicense.org MIT
 * @link     link
 */


/**
 * Error for failed validation field
 * 
 * @category Validation
 * @package  Unknown
 * @author   "ISHIMWE Valentin" <ishimwevalentin3@gmail.com>
 * @license  mitlicense.org MIT
 * @link     link
 */
class FieldError extends Exception
{
    // Something here if necessary
}


/**
 * Validator to validatate empty fields
 * 
 * @param string $field_name the name of the field to validate
 * @param bool   $bool       States whether field can be empty or not
 * @param $arr        The field's values array typically $_POST | $_GET
 * 
 * @return void
 */
function notEmpty(string $field_name, bool $bool, $arr)
{
    $value = isset($arr[$field_name]) ? trim(strval($arr[$field_name])) : "";
    $is_empty = $bool && $value !== "0" && empty($value);

    if ($is_empty) {
        throw new FieldEmpty("$field_name can't be empty");
    }
}

/**
 * Makes a given field required or not 
 * If required an not exist throws FieldDoesNotExist Exeption
 * 
 * @param string $field_name exists in an array
 * @param bool   $bool       States whether the field is required or not
 * @param array  $arr        the array where the field should exist 
 *                           typically $_POST|$_GET
 * 
 * @return void
 */
function required(string $field_name, bool $bool, $arr)
{
    $is_not_set = !isset($arr[$field_name]);
    if ($bool && $is_not_set) {
        throw new FieldDoesNotExist("$field_name is required");
    }
}

/**
 * Forces a given field value to start with a number or vice verse
 * 
 * @param string $field_name exists in an array
 * @param bool   $bool       States whether the field should 
 *                           start with number or not
 * @param array  $arr        the array where the field should exist 
 *                           typically $_POST|$_GET
 * 
 * @return void
 */
function startWithNum($field_name, bool $bool, $arr)
{
    $start_with_num = preg_match("/^\d/", $arr[$field_name]);
    throwError(!$bool && $start_with_num, "$field_name can't start with a number");
    throwError($bool && !$start_with_num, "$field_name must start with a number");
}

/**
 * Forces a given field value to be no longer than $max_len
 * 
 * @param string $field_name to be validated
 * @param int    $max_len    Maximum value length
 * @param array  $arr        the array where the field should exist 
 *                           typically $_POST|$_GET
 * 
 * @return void
 */
function maxLength($field_name, int $max_len, $arr)
{
    $length = strlen($arr[$field_name]);
    $more_than_len = $length > $max_len;
    throwError(
        $more_than_len,
        "$field_name must have no more than $max_len characters"
    );
}

/**
 * Forces a given field value to have at least $min_len chars
 * 
 * @param string $field_name to be validated
 * @param int    $min_len    Manimum value length
 * @param array  $arr        the array where the field should exist 
 *                           typically $_POST|$_GET
 * 
 * @return void
 */
function minLength($field_name, int $min_len, $arr)
{
    $length = strlen($arr[$field_name]);
    $less_than_len = $length < $min_len;
    throwError(
        $less_than_len,
        "$field_name must have at least $min_len characters"
    );
}

/**
 * Forces a given field value to contains only numbers or not contain only numbers
 * 
 * @param string $field_name to be validated
 * @param bool   $bool       States if the value must contain only numbers or 
 *                           can't contain only numbers 
 * @param array  $arr        the array where the field should exist 
 *                           typically $_POST|$_GET
 * 
 * @return void
 */
function isNumber($field_name, bool $bool, array $arr)
{
    $is_a_number = !preg_match("#\D#", $arr[$field_name]);
    throwError($bool && !$is_a_number, "$field_name should contain numbers only");
    throwError(!$bool && $is_a_number, "$field_name can't contain numbers only");
}

/**
 * Checks if a certain field value matches another field value
 * 
 * @param string $field_name       the field to be validated
 * @param string $match_field_name the field to be validated aginst
 * @param array  $arr              the array where both fields should exist 
 *                                 typically $_POST|$_GET 
 * 
 * @return void
 */
function shouldMatch(string $field_name, string $match_field_name, array $arr)
{
    $value_mismatch = isset($arr[$match_field_name]) ? !($arr[$field_name] ===  $arr[$match_field_name]) : true;

    throwError(
        $value_mismatch,
        "$field_name should match $match_field_name"
    );
}

function nullable(string $field_name, string $rule_name, array $data) {
    // 
}



function exists(string $field_name, string $query_data, array $data)
{
    $table = $query_data[0];
    $col = isset($query_data[1]) ? $query_data[1] : "";
    $col_value = isset($query_data[2]) ? $query_data[2] : "";

    $query = "SELECT * FROM `$table` WHERE `$col` = ?";
    $stmt = DB::conn()->prepare($query);
    $stmt->execute([$col_value]);
    $exists = $stmt->rowCount() > 0;

    
    throwError(!$exists, "$field_name doesn't exist in $table");
}


function unique(string $field_name, array $query_data, array $data)
{
    $table = $query_data[0];
    $col = isset($query_data[1]) ? $query_data[1] : "";
    $except_col = isset($query_data[2]) ? $query_data[2] : "";
    $except_value = isset($query_data[3]) ? $query_data[3] : "";

    $query = "SELECT * FROM `$table` WHERE `$col` = ?";
    $query .= empty($except_col) ? "" : " AND `$except_col` != ?";
    $stmt = DB::conn()->prepare($query);

    if (!empty($except_col)) {
        $stmt->execute([$data[$field_name], $except_value]);
    } else {
        $stmt->execute([$data[$field_name]]);
    }

    $exists = $stmt->rowCount() > 0;

    throwError($exists, "$field_name already exist in $table");

}

/**
 * Validate email
 *
 * @param string $field_name
 * @param string $rule_name
 * @param array $data 
 * 
 * @throws FieldError
 * @return void
 */
function email(string $field_name, string $rule_name, array $data)
{
    $value = filter_var($data[$field_name], FILTER_VALIDATE_EMAIL);
    throwError(!$value, "Invalid email address");
}

/**
 * Undocumented function
 *
 * @param string $field_name
 * @param string $upload_dir
 * @param array $data
 * @return void
 */
function isFile(string $field_name, string $upload_dir, array &$data)
{
    $file_name = uploadFile($field_name, $upload_dir, $uploaded);

    $data[$field_name] = $file_name;

    if (empty($file_name)) {
        return;
    }

    // var_dump($file_name);
    if (!$uploaded) {
        throwError(true, "Failed to upload file");
    }
}


function excludeIf(string $field_name, array $field_data, array &$data)
{
    $key = array_key_first($field_data);
    $matched = isset($data[$key]) && $data[$key] === $field_data[$key];
    
    if ($matched) {
        unset($data[$field_name]);
    }
}


function includeIf(string $field_name, array $field_data, array &$data)
{
    $key = array_key_first($field_data);
    $matched = isset($data[$key]) && $data[$key] === $field_data[$key];
    
    if (!$matched) {
        unset($data[$field_name]);
    }
}

/**
 * Check if a value is in provided array
 *
 * @param string $field_name
 * @param array $values
 * @param array $data
 * @return void
 */
function in(string $field_name, array $values, array $data)
{
    $value = $data[$field_name];

    throwError(!in_array($value, $values), "$field_name is not allowed");
}

/**
 * Check if date is valid
 *
 * @param string $field_name
 * @param array $rule_name
 * @param array $data
 * @return void
 */
function validDate(string $field_name, array $rule_name, array $data)
{
    list($day, $month, $year) = explode("/", $data[$field_name]);
    $valid = checkdate($month, $day, $year);
    throwError(!$valid, "$field_name should be like DD/MM/YYYY");
}



function requiredWithout(string $field_name, string|array $fields, array &$data)
{
    $none_is_set = true;
    $field_available = isset($data[$field_name]) && !empty($data[$field_name]);

    if ($field_available) {
        return;
    }
    
    if (is_string($fields)) {
        $fields = $fields !== "*" ? [$fields] : $data;
    }

    if (is_array($fields)) {
        foreach ($fields as $key => $value) {
            if (isset($data[$value]) && !empty($data[$value])) {
                if (isset($data[$value]['name'])){
                    $none_is_set = empty($data[$value]['name']);
                    break;
                }

                $none_is_set = false;
                break;
            }
        }
    }

    throwError($none_is_set, "At least $field_name should be provided");
}

/**
 * Checks if a hash matches
 *
 * @param string $field_name field name
 * @param string $hash       hashed string
 * @param array $data        data
 * @return boolean
 */
function hashMatch(string $field_name, string $hash, array $data)
{
    $hash_dont_match = !(password_verify($data[$field_name], $hash));
    throwError($hash_dont_match, "$field_name was incorrect");
}

/**
 * Throws field error whenever error occurs
 * 
 * @param $condition --- Should execption be thrown or not
 * @param $msg       -- error message of what just happened
 * 
 * @throws FieldError
 * @return void
 */
function throwError(bool $condition, string $msg)
{
    if ($condition) {
        throw new FieldError($msg);
    }
}





/**
 * File upload function
 *
 * @param string  $field_name  name of form field containing the file
 * @param string  $destination file upload folder
 * @param boolean $uploaded    reference variable true if file was uploaded
 *
 * @return string uploaded file name
 */
function uploadFile(
    string $field_name,
    $destination = "./uploads",
    &$uploaded = false
): string {
    if (!$_FILES[$field_name]['name']) {
        return "";
    }
    $random_number = strval(rand(100000, 9999999));
    $name_array = explode(".", $_FILES[$field_name]['name']);
    $file_name = $random_number . "." . end($name_array);
    $file_destination = $destination . "/$file_name";

    $uploaded = move_uploaded_file(
        $_FILES[$field_name]['tmp_name'],
        $file_destination
    );

    $file_name = basename(!$uploaded  ? "" : $file_destination);
    return $file_name;
}
