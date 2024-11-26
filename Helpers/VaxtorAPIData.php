<?php

namespace App\Helpers;

use App\Exceptions\CustomException;
use App\Models\Anpr;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VaxtorAPIData
{
    private $guid;
    private $device_id;
    private $image_path;
    private $plate_path;
    private $plate_number;
    private $direction_id;
    private $event_timestamp;

    private $imageDirectory = "anpr_detection";

    public function __construct($event, $device)
    {

        //* These variables can be accessed directly from event *//
        $this->imageDirectory = $device->site->id;
        $this->device_id = $device->id;
        // Log::channel('vaxtorlog')->info($event);
        $this->plate_number = $event['plate'];
        $this->direction_id = $event['direction'];        // Default
        // $this->event_timestamp = Carbon::parse($event['DateTimeRead'])->setTimezone('Europe/London');
        $this->event_timestamp = date('Y-m-d H:i:s', strtotime($event['timestamp']));;
        $this->guid = $this->plate_number . '-' . Carbon::parse($event['timestamp'],"UTC")->setTimezone("Europe/London")->toDateTimeString();

        //coordinates for creating plate image
        $left  = $event['left'];
        $top = $event['top'];
        $right = $event['right'];
        $bottom = $event['bottom'];

        //* Wrapping it in an array *//
        $boundingCoords = array($left, $top, $right, $bottom);

        //* Image needs to be processed *//
        $imagesrc = $event['image'];

        //* Processing Image *//
        $imageFormat = $this->getImageFormat($imagesrc);

        Log::channel('vaxtorlog')->info("Format " . $imageFormat);

        $imageData = $this->convertBase64ToImage($imagesrc);
        $mainImage = $this->addRectangleToImage($imageData, $imageFormat, $boundingCoords);
        $plateImage = $this->cropImage($imageData, $imageFormat, $boundingCoords);

        $mainImageName = $this->getImageName($this->guid, $this->event_timestamp, $imageFormat);
        $plateImageName = $this->getImageName($this->guid, $this->event_timestamp, $imageFormat, 'plate');

        //* Storing Image in AZURE *//
        $this->image_path = $this->storeImageInAzure($mainImageName, $mainImage);
        $this->plate_path = $this->storeImageInAzure($plateImageName, $plateImage, 'Plate');

        // Log::channel('vaxtorlog')->info("-----image-----------> " . $this->image_path);
        // Log::channel('vaxtorlog')->info("-----plate-----------> " . $this->plate_path);
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

    private function cropImage($imageData, $imageFormat, $boundingCoords)
    {
        $image = imagecreatefromstring($imageData);
        $rectangle = array("x" => $boundingCoords[0], "y" => $boundingCoords[1], "width" => ($boundingCoords[2] - $boundingCoords[0]), "height" => ($boundingCoords[3] - $boundingCoords[1]));
        $cropped_image = imagecrop($image, $rectangle);
        ob_start();
        call_user_func("image$imageFormat", $cropped_image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        return $imageData;
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

    // generates and returns the image name
    private function getImageName($guid, $timestamp, $imageFormat, $imageType = "anpr")
    {
        return $imageType . '_image_' . str_replace(['+', ':', 'T', '-', ' '], '_', $timestamp) . '_' . $guid . '.' . $imageFormat;
    }

    // stores in azure and returns the url
    private function storeImageInAzure($imageName, $imageData, $imageType = 'ANPR')
    {
        $imageInDirectory = $this->imageDirectory . "/" . $this->plate_number . "/" . $imageName;
        Storage::disk('public')->put($imageInDirectory, $imageData);
        Log::channel('vaxtorlog')->info("----------------> " . $imageType . " image saved in AZURE for GUID: " . $this->guid);
        return "storage/" . $imageInDirectory;
    }

    // saves the event into database as a new detection
    public function saveEventsInDatabase()
    {

        $hasANPR = Anpr::where('guid', $this->guid)->first();

        if ($hasANPR)
        {
            // if the event was already saved in database then no need to save again
            Log::channel('vaxtorlog')->info("!!!!!------------> ANPR details was already saved for GUID: " . $this->guid . "\n\t" . $this->image_path . "\n\t" . $this->plate_path);
            return 1;
        }
        else
        {

            // Save the new detection event and return if it ws success full, can add some validation here too.
            try
            {

                $anpr = Anpr::create([
                    'guid' => $this->guid,
                    'device_id' => $this->device_id,
                    'direction_id' => $this->direction_id,
                    'overview_image_path' => $this->image_path,
                    'plate_image_path' => $this->plate_path,
                    'registration_plate' => $this->plate_number,
                    'event_timestamp' => $this->event_timestamp,
                ]);

                Log::channel('vaxtorlog')->info("ANPR details saved for GUID: {$this->guid}\n\t{$this->image_path}\n\t{$this->plate_path}");

                return 1;
            }
            catch (Exception $ex)
            {
                Log::channel('vaxtorlog')->error("Failed to save ANPR details. Error: {$ex->getMessage()}", $this->toArray());

                return 0;
            }
        }
    }

    public function toArray()
    {
        return [
            'guid' => $this->guid,
            'device_id' => $this->device_id,
            'image_path' => $this->image_path,
            'plate_path' => $this->plate_path,
            'plate_number' => $this->plate_number,
            'direction_id' => $this->direction_id,
            'event_timestamp' => $this->event_timestamp,
        ];
    }
}
