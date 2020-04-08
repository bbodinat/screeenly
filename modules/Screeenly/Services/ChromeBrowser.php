<?php

namespace Screeenly\Services;

use Screeenly\Entities\Url;
use Spatie\Image\Manipulations;
use Screeenly\Entities\Screenshot;
use Spatie\Browsershot\Browsershot;
use Screeenly\Contracts\CanCaptureScreenshot;

class ChromeBrowser extends Browser implements CanCaptureScreenshot
{
    public function capture(Url $url, $storageUrl)
    {
        // $domainsList = array("googletagmanager.com", "googlesyndication.com", "doubleclick.net", "google-analytics.com");

        $browser = Browsershot::url($url->getUrl())
            ->ignoreHttpsErrors()
            ->windowSize($this->width, is_null($this->height) ? 768 : $this->height)
            ->timeout(30)
            ->deviceScaleFactor(2)
            ->waitUntilNetworkIdle()
            // ->setOption('addStyleTag', json_encode(['content' => 'h1{ border: 2px solid red; }']))
            // ->select('h1')
            // ->blockDomains($domainsList)
            ->setDelay($this->delay * 100)
            // ->fit(Manipulations::FIT_CONTAIN, 200, 200)
            ->userAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36')
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
