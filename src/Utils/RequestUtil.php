<?php

namespace Fastwf\Form\Utils;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fastwf\Api\Http\Frame\HttpRequestInterface;

/**
 * Utility class that help to handle the incomming request.
 */
class RequestUtil
{

    /**
     * Generate data from the fast web framework request.
     *
     * @param HttpRequestInterface $request the request to handle.
     * @return array the data merged from POST and uploaded file.
     */
    public static function dataFromRequest($request)
    {
        // Extract the $_POST and $_FILES global variables
        $post = $request->form->asArray();
        $files = $request->files->asArray();

        // For each key in $files recursivelly:
        //  create the key in the $post array
        //  Set an array or an instance that implements UploadedFileInterface
        self::dataDeepMerge($files, $post);

        return $post;
    }

    /**
     * Generate data from the PSR-7 request.
     * 
     * Warning: the verification about POST method and content type is not performed in this method.
     *
     * @param ServerRequestInterface $request the request to handle.
     * @return array the data merged from POST and uploaded file.
     */
    public static function dataFromPsr7($request)
    {
        $post = $request->getParsedBody();
        $files = $request->getUploadedFiles();

        // Deep merge of $files into $post like ::dataFromRequest
        self::dataDeepMerge($files, $post);

        return $post;
    }

    /**
     * Merge the new data located in $from array in $to and respect deep branches.
     *
     * @param array $from the array to merge in $to.
     * @param array $to the array where values will be merged.
     */
    private static function dataDeepMerge(&$from, &$to)
    {
        foreach ($from as $key => $value)
        {
            if ($value instanceof UploadedFileInterface)
            {
                $to[$key] = $value;
            }
            else
            {
                if (!\array_key_exists($key, $to))
                {
                    $to[$key] = [];
                }

                // Add the array and merge deep key/value pairs
                self::dataDeepMerge($value, $to[$key]);
            }
        }
    }

}
