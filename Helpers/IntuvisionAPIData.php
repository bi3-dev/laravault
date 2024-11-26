<?php

namespace App\Helpers;

use App\Exceptions\CustomException;
use App\Models\Detection;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IntuvisionAPIData
{
    private $guid;
    private $device_id;
    private $video_uri;
    private $image_path;
    private Carbon $event_timestamp;

    private $imageDirectory = "event_detection";

    public function __construct($event, $device)
    {
        //* These variables can be accessed directly from device *//
        $this->imageDirectory = $device->site->id;
        $this->device_id = $device->id;

        //* These variables can be accessed directly from event *//
        $this->guid = $event['@guid'];
        $this->video_uri = $this->findAttribute($event['frame']['attribute'], 'video-uri');
        $this->event_timestamp = Carbon::parse($event['frame']['timestamp']['#text'])->setTimezone('Europe/London');

        //* Image needs to be processed *//
        $imagesrc = $event['frame']['img']['@src'];

        //* Co-ordinates for detection box received directly form event *//
        $x  = $event['frame']['object']['x'];
        $y = $event['frame']['object']['y'];
        $width = $event['frame']['object']['width'];
        $height = $event['frame']['object']['height'];

        //* Wrapping it in an array *//
        $boundingCoords = [$x, $y, $width + $x, $height + $y];

        //* Processing Image *//
        $imageFormat = $this->getImageFormat($imagesrc);
        $imageData = $this->convertBase64ToImage($imagesrc);
        $image = $this->addRectangleToImage($imageData, $imageFormat, $boundingCoords);
        $imageName = $this->getImageName($this->guid, $this->event_timestamp, $imageFormat);

        //* Storing Image in AZURE *//
        $this->image_path = $this->storeImageInAzure($imageName, $image);
    }


    // This will look throught the attribute lists and find the value for the given key
    private function findAttribute($attributes, $name, $key = '@name')
    {
        // Log::channel('intuvisionlog')->info('Attributes received:', $attributes);
        foreach ($attributes as $attribute)
        {
            if ($attribute[$key] === $name)
            {
                return $attribute['#text'];
            }
        }

        return "";
    }

    // gets image format from base64 code
    private function getImageFormat($imageData)
    {
        $start = strpos($imageData, '/') + 1;
        $end = strpos($imageData, ';');
        $imageFormat = substr($imageData, $start, $end - $start);
        if (!in_array($imageFormat, ['jpeg', 'png', 'jpg']))
        {
            throw new Exception("Image Format: " . $imageFormat . " not recognised or not accepted. Accepted formats are: jpg, jpeg and png", 400);
        }
        return $imageFormat;
    }

    // converts raw base64 to raw image
    private function convertBase64ToImage($imageData)
    {
        $imageData = substr($imageData, strpos($imageData, ',') + 1);
        return base64_decode($imageData);
    }

    // adds the detection rectangle into the raw image and converts raw image to formatted image
    private function addRectangleToImage($imageData, $imageFormat, $boundingCoords)
    {
        $image = imagecreatefromstring($imageData);
        $red = imagecolorallocate($image, 255, 0, 0);
        imagesetthickness($image, 3);
        imagerectangle($image, $boundingCoords[0], $boundingCoords[1], $boundingCoords[2], $boundingCoords[3], $red);
        ob_start();
        call_user_func("image$imageFormat", $image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        return $imageData;
    }

    // generates and returns the image name
    private function getImageName($guid, $timestamp, $imageFormat)
    {
        return 'image_' . str_replace(['+', ':', 'T', '-', ' '], '_', $timestamp) . '_' . $guid . '.' . $imageFormat;
    }

    // stores in azure and returns the url
    private function storeImageInAzure($imageName, $imageData)
    {
        $imageInDirectory = $this->imageDirectory . "/" . $imageName;
        Storage::disk('public')->put($imageInDirectory, $imageData);
        Log::channel('intuvisionlog')->info("----------------> Image saved in AZURE for GUID: " . $this->guid);
        return "storage/" . $imageInDirectory;
    }

    // saves the event into database as a new detection
    public function saveEventsInDatabase()
    {
        $hasDetection = Detection::where('guid', $this->guid)->first();

        if ($hasDetection)
        {
            // if the event was already saved in database then no need to save again
            return 1;
            Log::channel('intuvisionlog')->info("----------------> Event was already saved for GUID: " . $this->guid . "\n\t" . $this->image_path);
        }
        else
        {
            // Save the new detection event and return if it ws success full, can add some validation here too.
            try
            {
                $detection = Detection::create([
                    'guid' => $this->guid,
                    'device_id' => $this->device_id,
                    'image_path' => $this->image_path,
                    'event_timestamp' => $this->event_timestamp,
                ]);

                Log::channel('intuvisionlog')->info("Event saved in DB for GUID: {$this->guid}\n\t{$this->image_path}");

                return 1;
            }
            catch (Exception $ex)
            {
                Log::channel('intuvisionlog')->error("Failed to save Suilvision job. Error: {$ex->getMessage()}", $this->toArray());

                return 0;
            }
        }
    }

    public function toArray()
    {
        return [
            'guid' => $this->guid,
            'device_id' => $this->device_id,
            'video_uri' => $this->video_uri,
            'image_path' => $this->image_path,
            'event_timestamp' => $this->event_timestamp,
        ];
    }
}
