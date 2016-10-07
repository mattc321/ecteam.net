<?php
/**
 * @file
 * Documents various hook/alter implementations.
 */

/**
 * Alter arguments before the API is called.
 *
 * @param array $args
 *   An array of arguments passed to the API method.
 */
function HOOK_box_view_api_alter(&$args) {

}

/**
 * Alter arguments before the API is called for a specific METHOD.
 *
 * @param array $args
 *   An array of arguments passed to the API method.
 */
function HOOK_box_view_api_METHOD_alter(&$args) {

}

/**
 * Alter the response returned by the API.
 *
 * @param mixed $response
 *   Response from the API.
 */
function HOOK_box_view_api_response_alter(&$response) {

}

/**
 * Alter the response returned by the API for a specific API METHOD.
 *
 * @param mixed $response
 *   Response from the API.
 */
function HOOK_box_view_api_response_METHOD_alter(&$response) {

}

/**
 * Called before the API method is called.
 *
 * @param array $vars
 *   A keyed array consisting of 'method' and 'args'.
 */
function HOOK_box_view_api_call($vars) {

}

/**
 * Called after the API method has been called.
 *
 * @param array $vars
 *   A keyed array consisting of 'method', 'args', and 'response'.
 */
function HOOK_box_view_api_call_finished($vars) {

}