<?php
/**
 * SimplyCast API PHP Wrapper. Usage instructions and examples are available
 * via the SimplyCast website.
 *
 * This version of the PHP wrapper differs from previous versions in that its
 * helper functions call to the new Contact Manager API, and not to the now
 * deprecated Contact List API. It is not compatible at all with existing
 * versions of the wrapper.
 *
 * The Contact Manager API allows for everything the previous Contact List API
 * did, plus support for metadata on contacts and direct manipulation of the 
 * contact database. Contact operations do not necessarily involve a list 
 * anymore.
 *
 * This wrapper uses the TLS basic authentication scheme for API auth by 
 * default.
 *
 * The PHP wrapper is only compatible with PHP version 5.3 and later.
 * It makes use of namespaces and the native JSON functions.
 * 
 * @package   SimplyCast
 * @author    SimplyCast <apisupport@simplycast.com>
 * @copyright 2012-2014 SimplyCast
 * @licence   MIT License (see included LICENCE file)
 */

namespace SimplyCast;

/**
 * SimplyCast API base class.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
class API {
  /**
   * The API URL.
   * @var string
   */
  private $apiURL = 'api.simplycast.com';

  /**
   * Key pair storage.
   * @var array
   */
  private $keys;

  /**
   * If true, make all requests over SSL/TLS.
   * @var boolean
   */
  private $useTLS = true;

  /**
   * The type of auth scheme to use.
   * @var string
   */
  private $authScheme = 'basic';

  /**
   * The library to use for making HTTP requests. cURL by default, falls
   * back to sockets if cURL is unavailable.
   * @var string
   */
  private $lib = '';

  /**
   * If true, throw an exception on error states.
   * @var boolean
   */
  private $exceptionOnError = true;

  /**
   * Store the last response.
   * @var array
   */
  private $lastResponse = array();

  /**
   * An array of API subresource handles, initialized as they're used.
   * @var array
   */
  private $apiHandles = array();

  /**
   * Constructor; accepts the public and secret keys for authentication.
   *
   * @param string $publicKey The public key (somewhat like a username).
   * @param string $secretKey The secret key (somewhat like a password).
   */
  public function __construct($publicKey, $secretKey) {
    $this->keys = array(
      'public' => $publicKey,
      'secret' => $secretKey,
    );
  }

  /**
   * Magic get. Loads API handles.
   *
   * @param string $var The handle to load.
   *
   * @return API handle.
   */
  public function __get($var) {
    $ovar = $var;
    $var = strtolower($var);

    $classes = '';
    foreach (array_reverse(get_declared_classes()) as $class) {
      if (is_subclass_of("\\$class", '\SimplyCast\APIResource')) {
        if (strtolower(str_replace('SimplyCast\\', '', $class)) == $var) {
          if (!array_key_exists($class, $this->apiHandles)) {
            $this->apiHandles[$class] = new $class($this);
          }
          return $this->apiHandles[$class];
        }

        $classes .= "\t- " . str_replace('SimplyCast\\', '', $class) . "\n";
      }
    }

    throw new \Exception("Resource class '$ovar' not found. Available classes are: \n$classes\n");
  }

  /**
   * Override the API url.
   * 
   * @param string $url The new API url.
   * 
   * @return void
   */
  public function setURL($url) {
    $this->apiURL = $url;
  }

  /**
   * Place a request to the API. This is pretty low level, but can be called
   *   directly.
   *
   * @param string $httpMethod      The HTTP method of the request (GET, POST, 
   *   DELETE).
   * @param string $resource        The resource that is going to be called.
   * @param array  $queryParameters A key / value array of query parameters 
   *   (will be urlencoded automatically.
   * @param string $data            The data to send along with the request. 
   *   This must be an array of request body data ready to be JSON encoded, 
   *   or a JSON string. Also, is only applicable for POST requests.
   *
   * @return array An array of response data, containing status & status code,
   *   the decoded response, headers and the raw response.
   */
  public function request($httpMethod, $resource, $queryParameters = array(), $data = false) {
    if ($this->authScheme == 'basic') {
      $authStr = 'Basic ' . base64_encode("{$this->keys['public']}:{$this->keys['secret']}");
    }

    $headers = array(
      "Connection: close",
      "Accept: application/json",
      "Authorization: $authStr",
    );

    if (is_array($queryParameters) && count($queryParameters) > 0) {
      $qStr = '?';
      foreach ($queryParameters as $k => $v) {
        $qStr .= urlencode($k) . '=' . urlencode($v) . '&';
      }
      $queryParameters = trim($qStr, '&');
    } else {
      $queryParameters = '';
    }
    
    if ($httpMethod == 'POST') {
      if (is_array($data)) {
        $data = json_encode($data);
      }

      $data = trim($data);

      if (strlen($data) > 0) {
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($data);
      }
    } else {
      $data = false;
    }

    $content = '';
    if ($this->lib == 'curl' && function_exists('curl_init')) {
      $content = $this->_curlRequest($httpMethod, $headers, $resource, $queryParameters, $data);
    } else {
      $content = $this->_fsockRequest($httpMethod, $headers, $resource, $queryParameters, $data);
    }

    $response = self::_decodeResponse($content);

    if ($this->exceptionOnError && $response['code'] >= 400) {
      if ($response['code'] >= 500) {
        throw new \Exception(
            "'{$response['code']} {$response['status']}' error encountered. This is a server level error; " . 
            "the API team will be notified and will resolve the issue as soon as possible."
        );
      } else {
        $errorMsg = (is_array($response['response']) && array_key_exists('error', $response['response'])) ? 
          $response['response']['error'] : '';
        throw new \Exception("'{$response['code']} {$response['status']}' error encountered: $errorMsg");
      }
    }

    $this->lastResponse = $response;

    if ($response['code'] == 204) {
      return null;
    } else if ($response['code'] < 400 && is_array($response['response'])) {
      return $response['response'];
    } else {  
      return false;
    }
  }

  /**
   * Internal function for making requests via cURL.
   *
   * @param string $httpMethod      HTTP method.
   * @param array  $headers         HTTP headers.
   * @param string $resource        Resource URI.
   * @param string $queryParameters Query parameter array.
   * @param string $data            POST request data
   *
   * @return string The raw response from the API.
   */
  private function _curlRequest($httpMethod, $headers, $resource, $queryParameters, $data) {
    $url = trim($this->apiURL, '/') . "/$resource$queryParameters";
    $url = ($this->useTLS ? 'https://' : 'http://') . preg_replace('|https?://|', '', $url);
    
    $ch = curl_init($url);
    curl_setopt_array(
        $ch, array(
          CURLOPT_CUSTOMREQUEST  => $httpMethod,
          CURLOPT_HTTPHEADER     => $headers,
          CURLOPT_HEADER         => true,
          CURLOPT_RETURNTRANSFER => true,
        )
    );

    if ($httpMethod == 'POST' && $data) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
  }

  /**
   * Internal function for making requests via a socket connection.
   *
   * @param string $httpMethod      HTTP method.
   * @param array  $headers         HTTP headers.
   * @param string $resource        Resource URI.
   * @param string $queryParameters Query parameter array.
   * @param string $data            POST request data
   *
   * @return string The raw response from the API.
   */
  private function _fsockRequest($httpMethod, $headers, $resource, $queryParameters, $data) {
    $host = $apiURL = $this->apiURL;
    $base = '';
    if (strpos($apiURL, '/')) {
      list($host, $base) = explode('/', $apiURL, 2);
    }

    array_unshift($headers, "Host: {$host}");

    $port = 80;
    if ($this->useTLS) {
      $port = 443;
      $url = "tls://$host";
    } else {
      $url = "tcp://$host";
    }

    $resource = '/' . trim($base, '/') . '/' . trim($resource, '/') . $queryParameters;

    $request = "$httpMethod $resource HTTP/1.1\r\n" . implode("\r\n", $headers) . "\r\n\r\n$data"; 
    
    $content = '';
    
    $resource = fsockopen($url, $port, $errno, $errstr);
    if (!$resource) {
      return false;
    }
    fwrite($resource, $request);
    while (!feof($resource)) {
      $content .= fread($resource, 8192);
    }
    fclose($resource);

    return $content;
  }

  /**
   * Decode a raw response.
   *
   * @param string $content The raw response content.
   *
   * @return array An array containing the the parsed response, including the 
   * status code and message, the headers and the response body.
   */
  private static function _decodeResponse($content) {
    if (strpos($content, "\r\n\r\n") === false) {
      return false;
    }

    list($headers, $contentBody) = explode("\r\n\r\n", $content, 2);
    $headers = explode("\r\n", $headers);
    $statusLine = array_shift($headers);

    list($protocol, $code, $status) = explode(' ', $statusLine, 3);

    $response = array(
      'code'     => $code, 
      'status'   => $status,
      'headers'  => $headers,
      'response' => strlen(trim($contentBody)) > 0 ? json_decode(self::decodeChunks($contentBody), true) : false,
      'raw'      => $content,
    );

    return $response;
  }

  /**
   * Get the response array of the last API call. This will contain the
   * response headers, response body, HTTP response code & status and the 
   * raw response.
   *
   * @return array The last API response.
   */
  public function getLastResponse() {
    return $this->lastResponse;
  }

  /**
   * Decode a chunked API response (responses with Transfer-Encoding: chunked).
   *
   * @param string $string The string to decode.
   *
   * @return string The decoded response.
   */
  public static function decodeChunks($string) {
    $chunks = explode("\r\n", trim($string));
    $decoded = '';
    if (count($chunks) > 1) {
      for ($i = 0; $i < count($chunks) - 1; $i += 2) {
        if (hexdec($chunks[$i]) != strlen($chunks[$i + 1])) {
          return $string;
        } else {
          $decoded .= $chunks[$i + 1];
        }
      }
      return $decoded;
    } else {
      return $string;
    }
  }
}

/**
 * Contact manager resource class.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
class ContactManager extends APIResource {
  /**
   * Get all available contact lists, optionally filtered by a query.
   *
   * @param int          $offset The zero-based offset to start the page at.
   * @param int          $limit  The number of entries to retrieve past the offset.
   * @param Query|string $query  An optional query to filter the results by. 
   *   Can either be a query string or a Query object. More details on what
   *   values can be queried upon are available in the API reference docs.
   *
   * @return array An array of contact list representations, or null 
   *   if there are no lists.
   */
  public function getLists($offset = 0, $limit = 100, $query = false) {
    $params = array('offset' => $offset, 'limit' => $limit);
    if ($query) {
      $params['query'] = ($query instanceof \SimplyCast\Query) ? $query->build() : $query;
    }
    
    return $this->request('GET', 'contactmanager/lists', $params);
  }

  /**
   * Create a new list.
   *
   * @param string $name The name of the list to create.
   *
   * @return array A representation of the created list, including the 
   *   list ID.
   */
  public function createList($name) {
    return $this->request('POST', 'contactmanager/lists', false, array('list' => array('name' => $name)));
  }

  /**
   * Retrieve a list by its unique identifier.
   *
   * @param int $listId The ID of the list to retrieve.
   *
   * @return array A representation of the retrieved list.
   */
  public function getList($listId) {
    return $this->request('GET', "contactmanager/lists/$listId");
  }

  /**
   * Helper function to retrieve lists by a specific list name. As list names
   *   aren't necessarily unique (although the API enforces uniqueness on list
   *   creation), this method can return zero, one, or more lists.
   *
   * @param string $listName The list name to search for.
   *
   * @return array An array of one or more lists that have the given name. Will
   *   return null if the name isn't found.
   */
  public function getListsByName($listName) {
    return $this->getLists(0, 100, new Query(new Condition('name', '=', $listName)));
  }

  /**
   * Rename a list, given its list ID. Keep in mind that the API enforces 
   *   uniqueness of list names, and will throw an error if the given list name
   *   already exists.
   *
   * @param int    $listId The ID of the list to rename.
   * @param string $name   The new list name.
   *
   * @return array A list representation containing the new name.
   */
  public function renameList($listId, $name) {
    return $this->request('POST', "contactmanager/lists/$listId", false, array('list' => array('name' => $name)));
  }

  /**
   * Delete a list. This only deletes the list, the contacts on the list will
   *   still exist in the system, and may belong to other lists.
   *
   * @param int $listId The ID of the list to delete.
   *
   * @return void
   */
  public function deleteList($listId) {
    $this->request('DELETE', "contactmanager/lists/$listId") !== false ? true : false;
  }

  /**
   * Retrieve all contacts that belong to a list, optionally filtered by a query.
   *
   * @param int          $listId The ID of the list to retrieve contacts from.
   * @param int          $offset The zero-based offset to start the page at.
   * @param int          $limit  The number of entries to retrieve past the offset.
   * @param Query|string $query  An optional query to filter the results by.
   *   Can either be a query string or a Query object. More details on what
   *   values can be queried upon are available in the API reference docs.
   *
   * @return array An array of contact representations, or null if 
   *   there are no contacts.
   */
  public function getContactsFromList($listId, $offset = 0, $limit = 100, $query = false) {
    $params = array('offset' => $offset, 'limit' => $limit);
    if ($query) {
      $params['query'] = ($query instanceof \SimplyCast\Query) ? $query->build() : $query;
    }

    return $this->request('GET', "contactmanager/lists/$listId/contacts", $params);
  }

  /**
   * Given a list ID and an array of contact IDs, add the contacts to the list.
   *
   * @param int     $listId     The ID of the list to add the contacts to.
   * @param array   $contactIds An array of contact IDs (integers).
   * @param boolean $strict     If true, this method will throw an error if any of
   *   the contacts to add to the list don't exist. If false, the contacts 
   *   that don't exist will be ignored.
   *
   * @return array An array of contact representations, or null if
   *   no contacts were added.
   */
  public function addContactsToList($listId, $contactIds, $strict = true) {
    if (is_scalar($contactIds)) {
      $contactIds = array($contactIds);
    }
    return $this->request(
        'POST', "contactmanager/lists/$listId/contacts", 
        array('strict' => ($strict ? '1' : '0')), 
        array('contacts' => $contactIds)
    );
  }

  /**
   * Given a list ID and a contact ID, delete the contact from the list.
   *   Note that this only removes the contact from the list; the contact will
   *   still exist in the system and may belong to other lists.
   *
   * @param int $listId    The ID of the list to delete the contact from.
   * @param int $contactId The ID of the contact to remove from the list.
   *
   * @return void
   */
  public function deleteContactFromList($listId, $contactId) {
    $this->request('DELETE', "contactmanager/lists/$listId/contacts/$contactId") !== false ? true : false;
  }

  /**
   * Contact retrieval and manipulation methods.
   */

  /**
   * Get a collection of contacts from the system contact database.
   *
   * @param int          $offset            The zero-based offset to start the page at.
   * @param int          $limit             The number of entries to retrieve past the offset.
   *
   * @param Query|string $query             An optional query to filter the results by.
   *   Can either be a query string or a Query object. More details on what
   *   values can be queried upon are available in the API reference docs.
   *
   * @param boolean      $ignoreEmptyFields If true, any fields with an empty value
   *   will not be returned in the response. The default is to return all
   *   fields.
   *
   * @param boolean      $getExtendedFields If true, this method will return a
   *   series of system fields as well as the basic contact fields. This is
   *   not the default.
   *
   * @return array An array of contact representations, or null if
   *   there are no contacts to return.
   */
  public function getContacts(
      $offset = 0, $limit = 100, $query = false, $ignoreEmptyFields = false, $getExtendedFields = false
  ) {
    $params = array(
      'offset'            => $offset, 
      'limit'             => $limit, 
      'ignoreEmptyFields' => $ignoreEmptyFields, 
      'extended'          => $getExtendedFields
    );

    if ($query) {
      $params['query'] = ($query instanceof \SimplyCast\Query) ? $query->build() : $query;
    }

    return $this->request('GET', 'contactmanager/contacts', $params);
  }

  /**
   * Create a new contact.
   *
   * @param array $fields  An array of arrays with keys 'id' and 'value', 
   *   where 'id' is a column ID and value is the value to set. 
   * @param array $listIds If provided, allows a way to easily assign the 
   *   new contact to one or more lists, by list ID.
   *
   * @return array A representation of the newly created contact.
   */
  public function createContact($fields, $listIds = array()) {
    if (is_scalar($listIds)) {
      $listIds = array($listIds);
    }
    $data = array('contact' => array('fields' => $fields));
    if (count($listIds) > 0) {
      $data['contact']['lists'] = $listIds;
    }
    return $this->request('POST', 'contactmanager/contacts', false, $data);
  }

  /**
   * Get a contact from the contact database by its ID.
   *
   * @param int     $contactId         The ID of the contact to retrieve.
   *
   * @param boolean $ignoreEmptyFields If true, any fields with an empty value
   *   will not be returned in the response. The default is to return all
   *   fields.
   *
   * @param boolean $getExtendedFields If true, this method will return a
   *   series of system fields as well as the basic contact fields. This is
   *   not the default.
   *
   * @return array A representation of the retrieved contact.
   */
  public function getContact($contactId, $ignoreEmptyFields = false, $getExtendedFields = false) {
    $queryParams = array(
      'ignoreEmptyFields' => $ignoreEmptyFields,
      'extended'          => $getExtendedFields 
    );
    return $this->request('GET', "contactmanager/contacts/$contactId", $queryParams);
  }

  /**
   * Update the field values of the given contact.
   *
   * @param int   $contactId The ID of the contact to update.
   * @param array $fields    An array of arrays with keys 'id' and 'value',
   *   where 'id' is a column ID and value is the value to set.
   *
   * @return array A representation of the updated contact.
   */
  public function updateContact($contactId, $fields) {
    return $this->request(
        'POST', 
        "contactmanager/contacts/$contactId", 
        false, 
        array('contact' => array('fields' => $fields))
    );
  }

  /**
   * Permanently delete a contact from the system (and all lists).
   *
   * @param int $contactId The ID of the contact to delete.
   *
   * @return void
   */
  public function deleteContact($contactId) {
    $this->request('DELETE', "contactmanager/contacts/$contactId") !== false ? true : false;
  }

  /**
   * Get all metadata fields for the specified contact.
   *
   * @param int $contactId The ID of the contact to get metadata fields for.
   * @param int $offset    The zero-based offset to start the page at.
   * @param int $limit     The number of entries to retrieve past the offset.
   *
   * @return array An array of metadata field representations.
   */
  public function getContactMetadata($contactId, $offset = 0, $limit = 100) {
    $params = array('offset' => $offset, 'limit' => $limit);
    return $this->request('GET', "contactmanager/contacts/$contactId/metadata", $params);
  }

  /**
   * Get a metadata field for a contact by ID.
   *
   * @param int    $contactId       The ID of the contact to get the metadata field 
   *   from.
   * @param string $metadataFieldId The ID of the metadata field to get. System
   *   metadata columns are typically strings. User-defined metadata columns,
   *   however, are autoincrementing unique integers.
   *
   * @return array A representation of the selected metadata field.
   */
  public function getContactMetadataField($contactId, $metadataFieldId) {
    return $this->request('GET', "contactmanager/contacts/$contactId/metadata/$metadataFieldId");
  }

  /**
   * Helper function to retrieve metadata columns by name. As this is not 
   *   guaranteed to be unique, this method can return multiple fields.
   *
   * @param int    $contactId         The ID of the contact to get the 
   *   metadata field from.
   * @param string $metadataFieldName The name to search for.
   *
   * @return array An array of one or more matching metadata fields, or an 
   *   null if there were no matches.
   */
  public function getContactMetadataFieldsByName($contactId, $metadataFieldName) {
    $query = new Query(new Condition('name', '=', $metadataFieldName));
    return $this->request('GET', "contactmanager/contacts/$contactId/metadata", array('query' => $query->build()));
  }

  /**
   * Update the value of a metadata field. Be sure that the field you are
   *   trying to update is editable.
   *
   * @param int    $contactId       The ID of the contact that owns the metadata field.
   * @param string $metadataFieldId The ID of the metadata field to update.
   * @param mixed  $value           The value to set. For an explanation of metadata field
   *   types and values, see the reference documentation on the API docs site.
   *
   * @return array A representation of the updated metadata field.
   */
  public function updateContactMetadataField($contactId, $metadataFieldId, $value) {
    return $this->request(
        'POST', 
        "contactmanager/contacts/$contactId/metadata/$metadataFieldId", 
        false, 
        array('metadataField' => array('values' => is_scalar($value) ? array($value) : $value))
    );
  }

  /**
   * Metadata column methods.
   */

  /**
   * Get a collection of metadata columns.
   *
   * @param int $offset The zero-based offset to start the page at.
   * @param int $limit  The number of entries to retrieve past the offset.
   *
   * @return array An array of metadata column representations.
   */
  public function getMetadataColumns($offset = 0, $limit = 100) {
    $params = array('offset' => $offset, 'limit' => $limit);
    return $this->request('GET', 'contactmanager/metadata', $params);
  }

  /**
   * Create a new metadata column. 
   *
   * @param string $name The name of the metadata column.
   * @param string $type The type of metadata column to create. One of: 
   *   ['single number', 'multi number', 'single string', 'multi string', 
   *   'sum number']. 'single *' columns store a scalar value, 'multi *' fields
   *   store more than one. 'sum number' columns are integer columns that will
   *   add/subtract on update, rather than overwrite (like a score, for 
   *   example).
   *
   * @return array A representation of the newly created metadata column.
   */
  public function createMetadataColumn($name, $type) {
    return $this->request(
        'POST', 
        "contactmanager/metadata", 
        false, 
        array('metadataColumn' => array('name' => $name, 'type' => $type))
    );
  }

  /**
   * Retrieve a metadata column by ID.
   *
   * @param string $columnId The ID of the column to retrieve.
   *
   * @return array A representation of the retrieved metadata column.
   */
  public function getMetadataColumn($columnId) {
    return $this->request('GET', "contactmanager/metadata/$columnId");
  }

  /**
   * Helper function to retrieve metadata columns by name. As there may be
   *   more than one column with a given name, this method can return more
   *   than one column.
   *
   * @param string $columnName The metadata column name to search for.
   * 
   * @return array An array of one or more metadata column representations,
   *   or null if the name doesn't exist.
   */
  public function getMetadataColumnByName($columnName) {
    $query = new Query(new Condition('name', '=', $columnName));
    return $this->request('GET', 'contactmanager/metadata', array('query' => $query->build()));
  }

  /**
   * Rename a metadata column.
   *
   * @param string $columnId The ID of the column to rename.
   * @param string $name     The new name of the metadata column.
   *
   * @return array A representation of the renamed metadata column.
   */
  public function renameMetadataColumn($columnId, $name) {
    return $this->request(
        'POST', 
        "contactmanager/metadata/$columnId", 
        false, 
        array('metadataColumn' => array('name' => $name))
    );
  }

  /**
   * Delete a metadata column. After deleting a metadata column, the 
   *   corresponding fields on every contact will be gone as well.
   *
   * @param string $columnId The ID of the column to delete.
   *
   * @return void
   */
  public function deleteMetadataColumn($columnId) {
    $this->request('DELETE', "contactmanager/metadata/$columnId") !== false ? true : false; 
  }

  /**
   * Column methods.
   */

  /**
   * Retrieve a collection of columns.
   *
   * @param int $offset The zero-based offset to start the page at.
   * @param int $limit  The number of entries to retrieve past the offset.
   *
   * @return array An array of column representations.
   */
  public function getColumns($offset = 0, $limit = 100) {
    $params = array('offset' => $offset, 'limit' => $limit);
    return $this->request('GET', 'contactmanager/columns', $params);
  }

  /**
   * Helper function to retrieve columns by name. As this in not guaranteed 
   *   to be unique, this method can return multiple columns with the same name.
   *
   * @param string|array $columnNames The column name(s) to search for.
   *
   * @return array An array of one or more matching columns, or null
   *   if there were no matches.
   */
  public function getColumnsByName($columnNames) {
    $query = '';
    if (is_scalar($columnNames)) {
      $query = new Query(new Condition('name', '=', $columnNames));
    } else {
      $query = new Query(new Condition('name', '=', array_shift($columnNames)));
      if (count($columnNames) > 0) {
        foreach ($columnNames as $name) {
          $query->addOr(new Condition('name', '=', $name));
        }
      }
    }

    return $this->request('GET', 'contactmanager/columns', array('query' => $query->build()));
  }

  /**
   * Create a new column.
   *
   * @param string $name      The name to give to the new column. Column names 
   *   must be unique; an error will be thrown if the requested name already
   *   exists.
   * @param string $type      The column type, one of ['string', 'date'].
   * @param array  $mergeTags An optional collection of merge tags to give the
   *   column. Each merge tag should be a string enclosed by dual percent signs
   *   (like %%TAG%%).
   *
   * @return array A representation of the new column.
   */
  public function createColumn($name, $type = 'string', $mergeTags = array()) {
    $data = array('column' => array('name' => $name, 'type' => $type));
    if (is_scalar($mergeTags)) {
      $mergeTags = array($mergeTags);
    }
    if (is_array($mergeTags) && count($mergeTags)) {
      $data['column']['mergeTags'] = $mergeTags;
    }
    
    return $this->request('POST', 'contactmanager/columns', false, $data);
  }

  /**
   * Retrieve a column by ID.
   *
   * @param string $columnId The ID of the column to retrieve.
   *
   * @return array A representation of the requested column.
   */
  public function getColumn($columnId) {
    return $this->request('GET', "contactmanager/columns/$columnId");
  }

  /**
   * Change the merge tags on the given column. 
   *
   * @param string  $columnId  The ID of the column to update.
   * @param array   $mergeTags An array of merge tags to assign to the column. Each merge
   *   tag should be a string enclosed by dual percent signs (like %%TAG%%).
   * @param boolean $append    If true, append the provided tags to the existing 
   *   tags; otherwise, overwrite all existing merge tags.
   *
   * @return array
   */
  public function updateMergeTags($columnId, $mergeTags, $append = false) {
    if (is_scalar($mergeTags)) {
      $mergeTags = array($mergeTags);
    }

    $data = array('column' => array('mergeTags' => $mergeTags));

    return $this->request(
        'POST', 
        "contactmanager/columns/$columnId", 
        array('append' => $append ? '1' : '0'), $data
    );
  }

  /*
   * Suppression list methods.
   */

  /**
   * Get suppression list entries for the given suppression list type.
   *
   * @param string       $listType The suppression list type; one of ['email', 
   *   'phone', 'mobile', 'fax'].
   *
   * @param int          $offset   The zero-based offset to start the page at.
   * @param int          $limit    The number of entries to retrieve past the offset.
   *
   * @param Query|string $query    An optional query to filter the results by.
   *   Can either be a query string or a Query object. More details on what
   *   values can be queried upon are available in the API reference docs.
   *
   * @return array An array of suppression list entries matching the given 
   *   criteria.
   */
  public function getSuppressionListEntries($listType, $offset = 0, $limit = 100, $query = false) {
    $params = array('offset' => $offset, 'limit' => $limit);
    if ($query) {
      $params['query'] = ($query instanceof \SimplyCast\Query) ? $query->build() : $query;
    }

    return $this->request('GET', "suppression/$listType", $params);
  }

  /**
   * Add new entries to a suppression list.
   *
   * @param string $listType The suppression list type; one of ['email',
   *   'phone', 'mobile', 'fax'].
   *
   * @param array  $entries  An array of one or more entries to add to the
   *   suppression list.
   *
   * @return array An array containing the suppressed entries.
   */
  public function addToSuppressionList($listType, $entries) {
    if (is_scalar($entries)) {
      $entries = array($entries);
    }
    return $this->request('POST', "suppression/$listType", false, array('suppressionEntries' => $entries));
  }
}

/**
 * SimplyCast 360 API resources.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
class SimplyCast360 extends APIResource {
  /**
   * Get a 360 project. The ID of a 360 project can be retrieved from 
   * the user interface of the 360 project.
   *
   * @param int $projectId The ID of the project to retrieve.
   *
   * @return array A representation of a 360 project (and the API connections
   *   contained within.
   */
  public function getProject($projectId) {
    return $this->request('GET', "crossmarketer/$projectId");
  }

  /**
   * Get a connection endpoint by ID. The ID of a connection endpoint can be
   * retrieved from the user interface of the 360 project.
   *
   * @param int    $projectId    The ID of the project containing the connection.
   * @param string $type         The connection endpoint type ('inbound' or 'outbound').
   * @param int    $connectionId The connection endpoint ID.
   *
   * @return array A representation of the connection endpoint.
    */
  public function getConnection($projectId, $type, $connectionId) {
    return $this->request('GET', "crossmarketer/$projectId/$type/$connectionId");   
  }

  /**
   * Retrieve a collection of contacts from an outbound connection.
   *
   * @param int $projectId    A 360 project ID.
   * @param int $connectionId An outbound API connection ID.
   *
   * @return array A collection of representations for a contact entry in the 360, or
   *   null if there aren't any to process.
   */
  public function getOutboundContacts($projectId, $connectionId) {
    $data = $this->request('GET', "crossmarketer/$projectId/outbound/$connectionId", array('showprocessed' => 1));
    if (array_key_exists('connection', $data) && count($data['connection']['contacts']) > 0) {
      return $data['connection']['contacts'];
    }
    return null;
  }

  /**
   * Retrieve a single contact entity from an outbound connection.
   *
   * @param int $projectId    A 360 project ID.
   * @param int $connectionId An outbound API connection ID.
   * @param int $contactRefId The ID of the contact to remove. This must
   *   be the 360 contact reference ID, not the contact manager contact ID.
   *
   * @return array A representation for a contact entry in the 360.
   */
  public function getOutboundContact($projectId, $connectionId, $contactRefId) {
    return $this->request('GET', "crossmarketer/$projectId/outbound/$connectionId/$contactRefId");
  }

  /**
   * Submit a contact list to an inbound API connection endpoint.
   * 
   * @param int $projectId    A 360 project ID.
   * @param int $connectionId An inbound API connection ID.
   * @param int $listId       The ID of the contact list to submit to a 360 workflow.
   *
   * @return void
   */
  public function pushList($projectId, $connectionId, $listId) {
    $this->request(
        'POST',
        "crossmarketer/$projectId/inbound/$connectionId",
        false,
        array('list' => array('list' => $listId))
    );
  }

  /**
   * Submit a contact to an inbound API connection endpoint.
   *
   * @param int $projectId    A 360 project ID.
   * @param int $connectionId An inbound API connection ID.
   * @param int $listId       The ID of a contact list that the contact belongs to.
   * @param int $contactId    The contact manager ID of the contact to submit.
   *
   * @return void
   */
  public function pushContact($projectId, $connectionId, $listId, $contactId) {
    $this->request(
        'POST', 
        "crossmarketer/$projectId/inbound/$connectionId", 
        false, 
        array('row' => array('list' => $listId, 'row' => $contactId))
    );
  }

  /**
   * Delete a contact from an outbound node. This only removes the
   *   contact from the outbound processing queue (permanently); it does not 
   *   delete the contact.
   *
   * @param int $projectId    A 360 project ID.
   * @param int $connectionId The ID of the outbound connection to remove a contact from.
   * @param int $contactRefId The ID of the contact to remove. This must
   *   be the 360 contact ID, not the contact manager contact ID.
   *
   * @return void
   */
  public function deleteContact($projectId, $connectionId, $contactRefId) {
    $this->request('DELETE', "crossmarketer/$projectId/outbound/$connectionId/$contactRefId");
  }
}

/**
 * API Resource base class.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
abstract class APIResource {
  /**
   * A handle to the API connection class.
   * @var SimplyCastAPI
   */
  private $apiHandle = false;

  /**
   * Constructor.
   *
   * @param SimplyCastAPI $apiHandle A handle to the API connection class.
   */
  public function __construct($apiHandle) {
    $this->apiHandle = $apiHandle;
  }

  /**
   * Pass-thru to the API class' request function.
   *
   * @return array
   */
  protected function request() {
    return call_user_func_array(array($this->apiHandle, 'request'), func_get_args());
  }
}

/**
 * Query builder Condition class.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
class Condition {
  /**
   * The field to query upon.
   * @var string
   */
  public $field;

  /**
   * The operator of the condition.
   * @var string
   */
  public $operator;

  /**
   * The value to query for.
   * @var string
   */
  public $value;

  /**
   * Condition constructor.
   *
   * @param string $field    The field to query upon.
   * @param string $operator The condition operator (=, <, >, etc).
   * @param stirng $value    The value to query for.
   */
  public function __construct($field, $operator, $value = false) {
    $this->field = $field;
    $this->operator = $operator;
    $this->value = $value;
  }

  /**
   * Build the string representation of the condition.
   *
   * @return string The built query.
   */
  public function build() {
    return '`' . str_replace('`', '\`', $this->field) . "` $this->operator" . 
      ($this->value ? (" '" . addcslashes($this->value, "'") . "'") : '');
  }
}

/**
 * Query builder helper class.
 *
 * @package SimplyCast
 * @author  SimplyCast <apisupport@simplycast.com>
 */
class Query {
  /**
   * Internal query storage.
   * @var array
   */
  private $query = array();

  /**
   * Query constructor.
   *
   * @param Query|Condition $condition A starting subquery or condition.
   */
  public function __construct($condition) {
    $this->query[] = $condition;
  }

  /**
   * Append a condition or subquery to the query conjuncted by an OR.
   *
   * @param Query|Condition $condition Either a Query object or a Condition object.
   *
   * @return void
   */
  public function addOr($condition) {
    $this->query[] = 'OR';
    $this->query[] = $condition;
  }

  /**
   * Append a condition or subquery to the query conjuncted by an AND.
   *
   * @param Query|Condition $condition Either a Query object or a Condition object.
   *
   * @return void
   */
  public function addAnd($condition) {
    $this->query[] = 'AND';
    $this->query[] = $condition;
  }

  /**
   * Build the query string from the current set of conditions and queries.
   *
   * @return string The built API-compatible query.
   */
  public function build() {
    $queryString = '';
    foreach ($this->query as $c) {
      if (is_string($c)) {
        $queryString .= " $c ";
      } else if ($c instanceof \SimplyCast\Query) {
        $queryString .= '(' . $c->build() . ')';
      } else if ($c instanceof \SimplyCast\Condition) {
        $queryString .= $c->build();
      }
    }
    return $queryString;
  }
}
