<?php

namespace Screeenly\Services;

use Screeenly\Entities\Url;
use Screeenly\Entities\Screenshot;
use Spatie\Browsershot\Browsershot;
use Screeenly\Contracts\CanCaptureScreenshot;

class ChromeBrowser extends Browser implements CanCaptureScreenshot
{
    public function capture(Url $url, $storageUrl)
    {
        $browser = Browsershot::url($url->getUrl())
            ->ignoreHttpsErrors()
            ->windowSize($this->width, is_null($this->height) ? 768 : $this->height)
            ->timeout(30)
            ->deviceScaleFactor(2)
            ->setDelay($this->delay * 100)
            ->setOption('args', ['--disable-web-security']);


        if (config('screeenly.disable_sandbox')) {
            $browser->noSandbox();
        }

        if (is_null($this->height)) {
            $browser->fullPage();
        }

        $browser->save($storageUrl);

        return new Screenshot($storageUrl);
    }
}
