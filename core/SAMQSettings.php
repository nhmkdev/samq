<?php
////////////////////////////////////////////////////////////////////////////////
// The MIT License (MIT)
//
// Copyright (c) 2018 Tim Stair
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.
////////////////////////////////////////////////////////////////////////////////

class SAMQSettings
{
    private static $enableObfuscationHash = true;
    private static $logMissingRequestIds = false;
    private static $logUnreferencedRequestIds = false;
    private static $logDuplicateRequests = false;
    private static $logAdjustments = false;
    private static $logRequestEvaluation = false;
    private static $logStatistics = false;
    private static $logMapConstruction = false;

    /**
     * @return bool
     */
    public static function isEnableObfuscationHash()
    {
        return self::$enableObfuscationHash;
    }

    /**
     * @param bool $enableObfuscationHash
     */
    public static function setEnableObfuscationHash($enableObfuscationHash)
    {
        self::$enableObfuscationHash = $enableObfuscationHash;
    }

    /**
     * @return bool
     */
    public static function isLogMissingRequestIds()
    {
        return self::$logMissingRequestIds;
    }

    /**
     * @param bool $logMissingRequestIds
     */
    public static function setLogMissingRequestIds($logMissingRequestIds)
    {
        self::$logMissingRequestIds = $logMissingRequestIds;
    }

    /**
     * @return bool
     */
    public static function isLogUnreferencedRequestIds()
    {
        return self::$logUnreferencedRequestIds;
    }

    /**
     * @param bool $logUnreferencedRequestIds
     */
    public static function setLogUnreferencedRequestIds($logUnreferencedRequestIds)
    {
        self::$logUnreferencedRequestIds = $logUnreferencedRequestIds;
    }

    /**
     * @return bool
     */
    public static function isLogDuplicateRequests()
    {
        return self::$logDuplicateRequests;
    }

    /**
     * @param bool $logDuplicateRequests
     */
    public static function setLogDuplicateRequests($logDuplicateRequests)
    {
        self::$logDuplicateRequests = $logDuplicateRequests;
    }

    /**
     * @return bool
     */
    public static function isLogAdjustments()
    {
        return self::$logAdjustments;
    }

    /**
     * @param bool $logAdjustments
     */
    public static function setLogAdjustments($logAdjustments)
    {
        self::$logAdjustments = $logAdjustments;
    }

    /**
     * @return bool
     */
    public static function isLogRequestEvaluation()
    {
        return self::$logRequestEvaluation;
    }

    /**
     * @param bool $logRequestEvaluation
     */
    public static function setLogRequestEvaluation($logRequestEvaluation)
    {
        self::$logRequestEvaluation = $logRequestEvaluation;
    }

    /**
     * @return bool
     */
    public static function isLogStatistics()
    {
        return self::$logStatistics;
    }

    /**
     * @param bool $logStatistics
     */
    public static function setLogStatistics($logStatistics)
    {
        self::$logStatistics = $logStatistics;
    }

    /**
     * @return bool
     */
    public static function isLogMapConstruction()
    {
        return self::$logMapConstruction;
    }

    /**
     * @param bool $logMapConstruction
     */
    public static function setLogMapConstruction($logMapConstruction)
    {
        self::$logMapConstruction = $logMapConstruction;
    }



    public static function enableFullDebug(){
        self::$logAdjustments = true;
        self::$logDuplicateRequests = true;
        self::$logMissingRequestIds = true;
        self::$logUnreferencedRequestIds = true;
        self::$logRequestEvaluation = true;
    }
}