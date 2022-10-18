<?php


namespace App\Services\Reports;



use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SavePfd implements ReportSaveInterface
{

    private $filename;

    private $loadView;

    private $disk = 'exports';

    private $expansion = 'pdf';

    private $separator = '/';

    public function __construct($loadView, $filename = null) {

        $this->filename = $filename;

        $this->loadView = $loadView;
    }

    public function save($fileData) :string {

        $fileName = $this->determineName($fileData);

        $pdf = $this->addToPdf($fileData);

        $path = $this->makeDirectory().$this->separator.$fileName;

        $pdf->save($path);

        return $path;
    }

    private function addToPdf($file) {

       return Pdf::loadView($this->loadView, [

            'data' => $file
        ]);

    }

    private function determineName($file) {

        $name = $this->filename ?? Str::lower(class_basename($file));

        return $file->id . '#' . $this->appendExtension($name);
    }

    private function appendExtension(string $file): string
    {
        return $file . '.' . $this->expansion;
    }

    private function disk() {

        return $this->disk;
    }

    private function makeDirectory() {

        if(!File::exists($this->disk())){
            File::makeDirectory($this->disk());
        }

        return $this->disk();
    }
}
